<?php

declare(strict_types=1);

namespace ForSign\Api\Builders;

use DateTime;
use ForSign\Api\Enums\AuthenticationChannel;
use ForSign\Api\Enums\Language;
use ForSign\Api\Enums\NotificationChannel;
use ForSign\Api\Enums\SignatureType;
use ForSign\Api\Requests\ManualFinishRequest;
use ForSign\Api\Requests\OperationDocumentRequest;
use ForSign\Api\Requests\OperationMemberRequest;
use ForSign\Api\Requests\OperationRequest;
use ForSign\Api\Requests\Signer;
use ForSign\Api\AuthenticationTypes\EmailDoubleAuthentication;
use ForSign\Api\AuthenticationTypes\SmsDoubleAuthentication;
use ForSign\Api\AuthenticationTypes\WhatsappDoubleAuthentication;
use ForSign\Api\SignaturePositionTypes\TagPosition;
use ForSign\Api\NotificationTypes\EmailNotification;
use ForSign\Api\SignatureTypes\AutomaticStampSignatureType;
use ForSign\Api\SignatureTypes\SignaturePosition;
use ForSign\Api\SignatureTypes\RubricPosition;
use InvalidArgumentException;
use LogicException;

/**
 * Builder for creating an OperationRequest fluently.
 */
class OperationBuilder
{
    private OperationRequest $request;

    private function __construct(string $operationName)
    {
        $this->request = new OperationRequest($operationName);
    }

    public static function initializeWithName(string $operationName): self
    {
        return new self($operationName);
    }

    public function setSignersOrderRequirement(bool $isOrdered): self
    {
        $this->request->setOrder($isOrdered);
        return $this;
    }

    public function setExpirationDate(DateTime $date): self
    {
        $this->request->setExpirationDate($date);
        return $this;
    }

    public function withExternalId(string $externalId): self
    {
        $this->request->setExternalId($externalId);
        return $this;
    }

    public function setInPersonSigning(bool $inPersonSigning): self
    {
        $this->request->setOnPremises($inPersonSigning);
        return $this;
    }

    public function withOptionalMessage(string $optionalMessage): self
    {
        $this->request->setOptionalMessage($optionalMessage);
        return $this;
    }

    public function addSigner(Signer $signer): self
    {
        $member = new OperationMemberRequest($signer->getName(), $signer->getEmail());
        $member->setRole($signer->getRole());
        $member->setFormTitle($signer->getFormTitle());
        $member->setFormDescription($signer->getFormDescription());
        $member->setPhone($signer->getPhone());
        $member->setDocument($signer->getDocument());
        $member->setSignatureType($signer->getSignatureType()->getSignatureType());

        $this->setNotificationAndAuthenticationChannels($signer, $member);

        foreach ($signer->getAttachmentRequests() as $attachment) {
            $member->addAttachment($attachment->convert());
        }
        
        // Add form fields to the member
        foreach ($signer->getFormFields() as $formField) {
            $member->addFormFields($formField->convert());
        }

        if ($signer->getSignatureType() instanceof AutomaticStampSignatureType) {
            $member->setSignatureType(SignatureType::Click);
            // $member->setStampId($signer->getSignatureType()->getStampId());
        }

        // Add signature positions and ensure documents are listed
        foreach ($signer->getSignaturePositions() as $position) {
            $fileInfo = $position->getFileInformation();
            $this->addDocumentIfNotExists($fileInfo->getFileId(), $fileInfo->getFileName());
            $member->addSignature($this->convertPositionToApiFormat($position));
        }

        // Add rubric positions and ensure documents are listed
        foreach ($signer->getRubricPositions() as $position) {
            $fileInfo = $position->getFileInformation();
            $this->addDocumentIfNotExists($fileInfo->getFileId(), $fileInfo->getFileName());
            $member->addRubric($this->convertPositionToApiFormat($position));
        }

        // Handle tag-based signature position
        if ($signer->getTagSignaturePosition() instanceof TagPosition) {
            $tagPosition = $signer->getTagSignaturePosition();
            $this->addDocumentIfNotExists($tagPosition->getFileInformation()->getFileId(), $tagPosition->getFileInformation()->getFileName());
            $member->setHasSignatureTag(true)->setSignPositionTag($tagPosition->getTagPattern());
        }

        $this->request->addMember($member);

        return $this;
    }

    /**
     * Converts a SignaturePosition or RubricPosition object to the array structure required by the API.
     * @param SignaturePosition|RubricPosition $position
     * @return array
     */
    private function convertPositionToApiFormat($position): array
    {
        return [
            'DocumentId' => $position->getFileInformation()->getFileId(),
            'PrintSignature' => $position->shouldPrintSignature(),
            'Positions' => [
                [
                    'Page' => $position->getPage(),
                    'CoordenateX' => $position->getCoordinateX(),
                    'CoordenateY' => $position->getCoordinateY(),
                ],
            ],
        ];
    }

    private function setNotificationAndAuthenticationChannels(Signer $signer, OperationMemberRequest $member): void
    {
        $notification = $signer->getNotificationType();
        if ($notification instanceof EmailNotification) {
            $member->setNotificationChannel(NotificationChannel::Email);
            $member->setEmail($notification->getEmail());
        } else {
            $member->setNotificationChannel(NotificationChannel::None);
        }

        $auth = $signer->getDoubleAuthenticationMethod();
        if ($auth instanceof EmailDoubleAuthentication) {
            $member->setAuthenticationChannel(AuthenticationChannel::Email);
            $member->setEmail($auth->getEmail());
        } elseif ($auth instanceof SmsDoubleAuthentication) {
            $member->setAuthenticationChannel(AuthenticationChannel::SMS);
            $member->setPhone($auth->getPhone());
        } elseif ($auth instanceof WhatsappDoubleAuthentication) {
            $member->setAuthenticationChannel(AuthenticationChannel::WhatsApp);
            $member->setPhone($auth->getPhone());
        }
    }
    
    private function addDocumentIfNotExists(string $fileId, string $fileName): void
    {
        $exists = false;
        foreach ($this->request->getFiles() as $file) {
            if ($file->getId() === $fileId) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $this->request->addFile(new OperationDocumentRequest($fileId, $fileName));
        }
    }

    public function addMetadata(string $key, string $value): self
    {
        $this->request->addMetadataEntry($key, $value);
        return $this;
    }
    
    public function withRedirectUrl(string $url): self
    {
        if (empty($url)) {
            throw new InvalidArgumentException('Redirect URL cannot be empty.');
        }
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            throw new InvalidArgumentException('Redirect URL must start with http:// or https://.');
        }
        if (!str_starts_with($url, 'https://')) {
            trigger_error('For security reasons, it is recommended to use HTTPS for redirect URLs.', E_USER_WARNING);
        }

        return $this->addMetadata('@module/redirect-url', $url);
    }
    
    public function setLanguage(Language $language): self
    {
        $this->request->setLanguage($language->value);
        return $this;
    }

    public function setDisplayCover(bool $displayCover): self
    {
        $this->request->setDisplayCover($displayCover);
        return $this;
    }

    public function build(): OperationRequest
    {
        $this->validateRequest();
        return $this->request;
    }

    private function validateRequest(): void
    {
        if (empty($this->request->getName())) {
            throw new LogicException('Operation name is required.');
        }
        if (empty($this->request->getMembers())) {
            throw new LogicException('At least one member is required.');
        }
        if(empty($this->request->getLanguage())) {
            throw new LogicException('Language is required.');
        }
    }
}
