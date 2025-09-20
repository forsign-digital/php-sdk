<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Enums\Language;
use ForSign\Api\Enums\SignatureType;
use ForSign\Api\Requests\Attachment;
use ForSign\Api\Types\AttachmentFileType;
use ForSign\Api\Enums\InputAttachmentType;
use ForSign\Api\Requests\FileInformation;
use ForSign\Api\Requests\Signer;
use ForSign\Api\NotificationTypes\EmailNotification;
use ForSign\Api\AuthenticationTypes\EmailDoubleAuthentication;
use ForSign\Api\SignatureTypes\DefaultSignatureType;
use ForSign\Api\Forms\TextFormField;
use ForSign\Api\Forms\FormFieldPosition;

// Replace with your API key
$apiKey = 'SUA_API_KEY_AQUI';

// Create and configure the client
$client = new Client($apiKey);

try {
    echo "Exemplo: Criando uma operação com upload de arquivo, campos de formulário e anexo." . PHP_EOL;

    // Step 1: Upload the document
    echo "Uploading document..." . PHP_EOL;
    $filePath = __DIR__ . '/../tests/fixtures/document.pdf'; // Create a dummy PDF here
    if (!file_exists($filePath)) {
        file_put_contents($filePath, '%PDF-1.4 ... minimal PDF content ...');
    }
    $uploadResponse = $client->uploadFile($filePath);
    $fileInfo = new FileInformation($uploadResponse->getId(), $uploadResponse->getFileName());
    echo "Document Uploaded! ID: " . $fileInfo->getFileId() . PHP_EOL;

    // Step 2: Define a signer with all configurations
    $signer = new Signer();
    $signer->setName('John Doe')
           ->setEmail('john.doe@example.com')
           ->setPhone('+1234567890')
           ->setNotificationType(new EmailNotification('john.doe@example.com'))
           ->setRole('Contratante')
           ->setFormTitle('Informações Adicionais')
           ->setFormDescription('Por favor, preencha os campos abaixo.')
           ->setDoubleAuthenticationMethod(new EmailDoubleAuthentication('john.doe@example.com'))
           ->setSignatureType(new DefaultSignatureType(SignatureType::UserChoice));

    // Step 3: Configure signer actions (signature positions, forms, attachments)
    // Add a signature position
    $signer->addSignatureInPosition($fileInfo, 1, '70%', '80%');

    // Add a form field
    $formField = TextFormField::withName('CPF')
        ->withInstructions('Please enter your CPF')
        ->isRequired()

        ->onPosition(new FormFieldPosition($fileInfo, 1, '30%', '50%'));
    $signer->addFormField($formField);

    // Request an attachment
    $idAttachment = new Attachment('ID Document', 'Please upload your ID', true);
    $idAttachment->permitFileType(AttachmentFileType::PDF())
                 ->permitFileType(AttachmentFileType::JPG())
                 ->permitAttachmentByInput(InputAttachmentType::UploadFile);
    $signer->requestAttachment($idAttachment);

    // Step 4: Use the OperationBuilder to create the operation
    echo "Building operation..." . PHP_EOL;
    $operationRequest = $client->createOperationBuilder('New Contract via PHP SDK Builder')
        ->setLanguage(Language::English)
        ->setSignersOrderRequirement(true)
        ->setExpirationDate((new DateTime())->add(new DateInterval('P15D')))
        ->withExternalId('php-sdk-test-123')
        ->withRedirectUrl('https://your-website.com/thank-you/{operationId}')
        ->addSigner($signer)
        ->setLanguage(Language::Portuguese)
        ->build();
    
    // Step 5: Send the request to the API
    echo "Creating operation on ForSign API..." . PHP_EOL;
    $response = $client->operations()->create($operationRequest);
    
    // Print the operation ID
    echo "Operation created successfully with ID: " . $response->getId() . PHP_EOL;
    
    // Print the signing URLs for each member
    foreach ($response->getMembers() as $member) {
        echo "-> Signing URL for {$member['name']}: {$member['signUrl']}" . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    
    if ($e instanceof \ForSign\Api\Exceptions\ValidationException) {
        echo "Validation errors:" . PHP_EOL;
        foreach ($e->getValidationErrors() as $field => $error) {
            echo "  {$field}: {$error}" . PHP_EOL;
        }
    }
}