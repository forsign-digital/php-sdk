<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

use ForSign\Api\Enums\AuthenticationChannel;
use ForSign\Api\Enums\NotificationChannel;
use ForSign\Api\Enums\SignatureType;

/**
 * Request for an operation member.
 */
class OperationMemberRequest implements \JsonSerializable
{
    /**
     * @var string The name of the member
     */
    private string $name;

    /**
     * @var string|null The role of the member
     */
    private ?string $role = null;
    
    /**
     * @var string The email of the member
     */
    private string $email;
    
    /**
     * @var string|null The phone number of the member
     */
    private ?string $phone = null;
    
    /**
     * @var string|null The document number of the member
     */
    private ?string $document = null;

    /**
     * @var bool Whether the member is an observer
     */
    private bool $observer = false;
    
    /**
     * @var int The order position of the member
     */
    private int $orderPosition = 0;
    
    /**
     * @var NotificationChannel The notification channel for the member.
     */
    private NotificationChannel $notificationChannel = NotificationChannel::Email;
    
    /**
     * @var AuthenticationChannel|null The authentication channel for the member.
     */
    private ?AuthenticationChannel $authenticationChannel = null;
    
    /**
     * @var SignatureType The signature type for the member.
     */
    private SignatureType $signatureType = SignatureType::Draw;
    
    /**
     * @var array<AttachmentOperationMemberDto> The attachments for the member.
     */
    private array $attachments = [];

    /**
     * @var array<string, mixed> The form fields for the member.
     */
    private array $formFields = [];

    /**
     * @var array<string, mixed> The signature information for the member.
     */
    private array $signatureInformation = [];
    
    /**
     * @var array<string, mixed> The signature positions for the member
     */
    private array $signatures = [];

    /**
     * @var array<string, mixed> The rubric positions for the member.
     */
    private array $rubrics = [];
    
    /**
     * @var string|null The tag pattern for signature position
     */
    private ?string $signPositionTag = null;
    /**
     * @var bool Whether a signature tag is used
     */
    private bool $hasSignatureTag = false;
    /**
     * @var string|null The title for the form fields section
     */
    private ?string $formTitle = null;
    /**
     * @var string|null The description for the form fields section
     */
    private ?string $formDescription = null;
    /**
     * @param string $name The name of the member
     * @param string $email The email of the member
     */
    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
    
    /**
     * Gets the name of the member.
     *
     * @return string The name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Sets the name of the member.
     *
     * @param string $name The name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the role of the member.
     *
     * @return string|null The role
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Sets the role of the member.
     *
     * @param string|null $role The role
     * @return self
     */
    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }
    
    /**
     * Gets the email of the member.
     *
     * @return string The email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Sets the email of the member.
     *
     * @param string $email The email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * Gets the phone number of the member.
     *
     * @return string|null The phone number
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    /**
     * Sets the phone number of the member.
     *
     * @param string|null $phone The phone number
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * Gets the document number of the member.
     *
     * @return string|null The document number
     */
    public function getDocument(): ?string
    {
        return $this->document;
    }
    
    /**
     * Sets the document number of the member.
     *
     * @param string|null $document The document number
     * @return self
     */
    public function setDocument(?string $document): self
    {
        $this->document = $document;
        return $this;
    }

    /**
     * Gets whether the member is an observer.
     *
     * @return bool Whether the member is an observer
     */
    public function isObserver(): bool
    {
        return $this->observer;
    }
    
    /**
     * Sets whether the member is an observer.
     *
     * @param bool $observer Whether the member is an observer
     * @return self
     */
    public function setObserver(bool $observer): self
    {
        $this->observer = $observer;
        return $this;
    }
    
    /**
     * Gets the order position of the member.
     *
     * @return int The order position
     */
    public function getOrderPosition(): int
    {
        return $this->orderPosition;
    }
    
    /**
     * Sets the order position of the member.
     *
     * @param int $orderPosition The order position
     * @return self
     */
    public function setOrderPosition(int $orderPosition): self
    {
        $this->orderPosition = $orderPosition;
        return $this;
    }
    
    /**
     * Gets the notification channel for the member.
     *
     * @return NotificationChannel The notification channel
     */
    public function getNotificationChannel(): NotificationChannel
    {
        return $this->notificationChannel;
    }
    
    /**
     * Sets the notification channel for the member.
     *
     * @param NotificationChannel $notificationChannel The notification channel
     * @return self
     */
    public function setNotificationChannel(NotificationChannel $notificationChannel): self
    {
        $this->notificationChannel = $notificationChannel;
        return $this;
    }
    
    /**
     * Gets the authentication channel for the member. Can be null if not set.
     *
     * @return AuthenticationChannel|null The authentication channel.
     */
    public function getAuthenticationChannel(): ?AuthenticationChannel
    {
        return $this->authenticationChannel;
    }
    
    /**
     * Sets the authentication channel for the member.
     * @param AuthenticationChannel|null $authenticationChannel The authentication channel
     * @return self
     */
    public function setAuthenticationChannel(?AuthenticationChannel $authenticationChannel): self
    {
        $this->authenticationChannel = $authenticationChannel;
        return $this;
    }
    
    /**
     * Gets the signature type for the member.
     *
     * @return SignatureType The signature type
     */
    public function getSignatureType(): SignatureType
    {
        return $this->signatureType;
    }
    
    /**
     * Sets the signature type for the member.
     *
     * @param SignatureType $signatureType The signature type
     * @return self
     */
    public function setSignatureType(SignatureType $signatureType): self
    {
        $this->signatureType = $signatureType;
        return $this;
    }
    
    /**
     * Gets the attachments for the member.
     *
     * @return array<AttachmentOperationMemberDto> The attachments
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
    
    /**
     * Sets the attachments for the member.
     *
     * @param array<AttachmentOperationMemberDto> $attachments The attachments
     * @return self
     */
    public function setAttachments(array $attachments): self
    {
        $this->attachments = $attachments;
        return $this;
    }
    
    /**
     * Adds an attachment for the member.
     *
     * @param AttachmentOperationMemberDto $attachment The attachment
     * @return self
     */
    public function addAttachment(AttachmentOperationMemberDto $attachment): self
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * Gets the form fields for the member.
     *
     * @return array<string, mixed> The form fields
     */
    public function getFormFields(): array
    {
        return $this->formFields;
    }

    /**
     * Sets the form fields for the member.
     *
     * @param array<string, mixed> $formFields The form fields
     * @return self
     */
    public function setFormFields(array $formFields): self
    {
        $this->formFields = $formFields;
        return $this;
    }

    /**
     * Adds a form field for the member.
     *
     * @param array<string, mixed> $formField The form field
     * @return self
     */
    public function addFormField(array $formField): self
    {
        $this->formFields[] = $formField;
        return $this;
    }

    /**
     * Merges an array of form fields into the existing list.
     *
     * @param array<string, mixed> $formFields The form fields to add
     * @return self
     */
    public function addFormFields(array $formFields): self
    {
        if (!empty($formFields)) {
            $this->formFields = array_merge($this->formFields, $formFields);
        }
        return $this;
    }

    /**
     * Gets the signature information for the member.
     *
     * @return array<string, mixed> The signature information
     */
    public function getSignatureInformation(): array
    {
        return $this->signatureInformation;
    }
    
    /**
     * Sets the signature information for the member.
     *
     * @param array<string, mixed> $signatureInformation The signature information
     * @return self
     */
    public function setSignatureInformation(array $signatureInformation): self
    {
        $this->signatureInformation = $signatureInformation;
        return $this;
    }
    
    /**
     * Gets the signature positions for the member.
     *
     * @return array<string, mixed> The signatures
     */
    public function getSignatures(): array
    {
        return $this->signatures;
    }
    
    /**
     * Adds a signature for the member.
     * @param array<string, mixed> $signature The signature data.
     * @return self
     */
    public function addSignature(array $signature): self
    {
        $this->signatures[] = $signature;
        return $this;
    }

    /**
     * Gets the rubric positions for the member.
     *
     * @return array<string, mixed> The rubrics
     */
    public function getRubrics(): array
    {
        return $this->rubrics;
    }
    
    /**
     * Adds a rubric for the member.
     * @param array<string, mixed> $rubric The rubric data.
     * @return self
     */
    public function addRubric(array $rubric): self
    {
        $this->rubrics[] = $rubric;
        return $this;
    }

    /**
     * Gets the signature position tag.
     * @return string|null
     */
    public function getSignPositionTag(): ?string
    {
        return $this->signPositionTag;
    }

    /**
     * Sets the signature position tag.
     * @param string|null $signPositionTag
     * @return self
     */
    public function setSignPositionTag(?string $signPositionTag): self
    {
        $this->signPositionTag = $signPositionTag;
        return $this;
    }

    /**
     * Gets whether a signature tag is used.
     * @return bool
     */
    public function hasSignatureTag(): bool
    {
        return $this->hasSignatureTag;
    }

    /**
     * Sets whether a signature tag is used.
     * @param bool $hasSignatureTag
     * @return self
     */
    public function setHasSignatureTag(bool $hasSignatureTag): self
    {
        $this->hasSignatureTag = $hasSignatureTag;
        return $this;
    }

    public function getFormTitle(): ?string
    {
        return $this->formTitle;
    }

    public function setFormTitle(?string $formTitle): self
    {
        $this->formTitle = $formTitle;
        return $this;
    }

    public function getFormDescription(): ?string
    {
        return $this->formDescription;
    }

    public function setFormDescription(?string $formDescription): self
    {
        $this->formDescription = $formDescription;
        return $this;
    }
    
    /**
     * Converts the request to an array for API requests.
     *
     * @return array<string, mixed> The request as an array
     */
    public function toArray(): array
    {
        $data = [
            'Role' => $this->role,
            'Name' => $this->name,
            'Email' => $this->email,
            'Observer' => $this->observer,
            'OrderPosition' => $this->orderPosition,
            'NotificationChannel' => $this->notificationChannel->value,
            'SignatureType' => $this->signatureType->value,
            'Attachments' => array_map(fn($attachment) => $attachment->toArray(), $this->attachments),
            'FormFields' => $this->formFields,
            'Signatures' => $this->signatures,
            'Rubrics' => $this->rubrics,
            'HasSignatureTag' => $this->hasSignatureTag,
            'SignPositionTag' => $this->signPositionTag,
            'FormTitle' => $this->formTitle,
            'FormDescription' => $this->formDescription,
        ];
        
        if ($this->authenticationChannel !== null) {
            $data['AuthenticationChannel'] = $this->authenticationChannel->value;
        }

        if ($this->phone !== null) {
            $data['Phone'] = $this->phone;
        }

        if ($this->document !== null) {
            $data['Document'] = $this->document;
        }

        if (!empty($this->signatureInformation)) {
            $data['SignatureInformation'] = $this->signatureInformation;
        }
        
        return $data;
    }
    
    /**
     * Serializes the request to JSON.
     *
     * @return array<string, mixed> The request as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
