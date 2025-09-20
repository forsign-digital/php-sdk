<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

use ForSign\Api\AuthenticationTypes\DoubleAuthenticationInterface;
use ForSign\Api\Forms\FormFieldInterface;
use ForSign\Api\NotificationTypes\NotificationInterface;
use ForSign\Api\SignaturePositionTypes\TagPosition;
use ForSign\Api\SignatureTypes\SignatureInformationType;
use ForSign\Api\SignatureTypes\SignaturePosition;
use ForSign\Api\SignatureTypes\RubricPosition;
use InvalidArgumentException;

/**
 * Represents a signer in an operation, encapsulating all their configuration.
 */
class Signer
{
    private string $formTitle = '';
    private string $formDescription = '';
    private string $name = '';
    private string $role = '';
    private string $email = '';
    private ?string $phone = null;
    private ?string $document = null;
    private ?NotificationInterface $notificationType = null;
    private ?TagPosition $tagSignaturePosition = null;
    private ?DoubleAuthenticationInterface $doubleAuthenticationMethod = null;
    private ?SignatureInformationType $signatureType = null;

    /** @var SignaturePosition[] */
    private array $signaturePositions = [];
    /** @var RubricPosition[] */
    private array $rubricPositions = [];
    /** @var Attachment[] */
    private array $attachmentRequests = [];
    /** @var FormFieldInterface[] */
    private array $formFields = [];

    public function getFormTitle(): string
    {
        return $this->formTitle;
    }

    public function setFormTitle(string $formTitle): self
    {
        $this->formTitle = $formTitle;
        return $this;
    }

    public function getFormDescription(): string
    {
        return $this->formDescription;
    }

    public function setFormDescription(string $formDescription): self
    {
        $this->formDescription = $formDescription;
        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function getNotificationType(): ?NotificationInterface
    {
        return $this->notificationType;
    }

    public function setNotificationType(?NotificationInterface $notificationType): self
    {
        $this->notificationType = $notificationType;
        return $this;
    }

    public function getTagSignaturePosition(): ?TagPosition
    {
        return $this->tagSignaturePosition;
    }

    public function setTagSignaturePosition(TagPosition $tagSignaturePosition): self
    {
        $this->tagSignaturePosition = $tagSignaturePosition;
        return $this;
    }

    public function getDoubleAuthenticationMethod(): ?DoubleAuthenticationInterface
    {
        return $this->doubleAuthenticationMethod;
    }

    public function setDoubleAuthenticationMethod(?DoubleAuthenticationInterface $doubleAuthenticationMethod): self
    {
        $this->doubleAuthenticationMethod = $doubleAuthenticationMethod;
        return $this;
    }

    public function getSignatureType(): ?SignatureInformationType
    {
        return $this->signatureType;
    }

    public function setSignatureType(?SignatureInformationType $signatureType): self
    {
        $this->signatureType = $signatureType;
        return $this;
    }
    
    public function getSignaturePositions(): array
    {
        return $this->signaturePositions;
    }

    public function addSignatureInPosition(FileInformation $fileInformation, int $page, string $coordinateX, string $coordinateY): self
    {
        $this->signaturePositions[] = new SignaturePosition($fileInformation, $page, $coordinateX, $coordinateY);
        return $this;
    }
    
    public function getRubricPositions(): array
    {
        return $this->rubricPositions;
    }
    
    public function addRubricInPosition(FileInformation $fileInformation, int $page, string $coordinateX, string $coordinateY): self
    {
        $this->rubricPositions[] = new RubricPosition($fileInformation, $page, $coordinateX, $coordinateY);
        return $this;
    }

    public function getAttachmentRequests(): array
    {
        return $this->attachmentRequests;
    }

    public function requestAttachment(Attachment $attachment): self
    {
        $this->attachmentRequests[] = $attachment;
        return $this;
    }

    public function getFormFields(): array
    {
        return $this->formFields;
    }

    public function addFormField(FormFieldInterface $formField): self
    {
        $this->formFields[] = $formField;
        return $this;
    }
}
