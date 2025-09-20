<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Enums\Language;
use ForSign\Api\Enums\SignatureType;
use ForSign\Api\Requests\FileInformation;
use ForSign\Api\Requests\Signer;
use ForSign\Api\NotificationTypes\EmailNotification;
use ForSign\Api\AuthenticationTypes\EmailDoubleAuthentication;
use ForSign\Api\SignatureTypes\DefaultSignatureType;
use ForSign\Api\SignaturePositionTypes\TagPosition;

// Replace with your API key
$apiKey = 'your-api-key';

// Create and configure the client
$client = new Client($apiKey);

echo "Exemplo: Criando uma operação com assinatura posicionada por TAG." . PHP_EOL;

try {
    // Step 1: Upload a document that contains a tag pattern, e.g., "{{assinatura_cliente}}"
    echo "Uploading document with tags..." . PHP_EOL;
    $filePath = __DIR__ . '/../tests/fixtures/document_with_tags.pdf'; // Create a dummy PDF with a tag
    if (!file_exists($filePath)) {
        // In a real scenario, this PDF would have visible text like "{{assinatura_cliente}}"
        file_put_contents($filePath, '%PDF-1.4 ... PDF content with tag ...');
    }
    $uploadResponse = $client->uploadFile($filePath);
    $fileInfo = new FileInformation($uploadResponse->getId(), $uploadResponse->getFileName());
    echo "Document Uploaded! ID: " . $fileInfo->getFileId() . PHP_EOL;

    // Step 2: Define a signer
    $signer = new Signer();
    $signer->setName('Jane Smith')
           ->setEmail('jane.smith@example.com')
           ->setRole('Testemunha')
           ->setDoubleAuthenticationMethod(new EmailDoubleAuthentication('jane.smith@example.com'))
           ->setNotificationType(new EmailNotification('jane.smith@example.com'))
           ->setSignatureType(new DefaultSignatureType(SignatureType::Draw));

    // Step 3: Set the signature position using the tag pattern from the document
    $tagPosition = new TagPosition($fileInfo, '{{assinatura_cliente}}');
    $signer->setTagSignaturePosition($tagPosition);
    echo "Signature position set to tag: '{$tagPosition->getTagPattern()}'" . PHP_EOL;

    // Step 4: Use the OperationBuilder to create the operation
    echo "Building operation..." . PHP_EOL;
    $operationRequest = $client->createOperationBuilder('Acordo de Confidencialidade (Tags)')
        ->setLanguage(Language::Portuguese)
        ->setSignersOrderRequirement(false)
        ->setExpirationDate((new DateTime())->add(new DateInterval('P30D')))
        ->addSigner($signer)
        ->build();

    // Step 5: Send the request to the API
    echo "Creating operation on ForSign API..." . PHP_EOL;
    $response = $client->operations()->create($operationRequest);

    // Print the operation ID and signing URLs
    echo "Operation created successfully with ID: " . $response->getId() . PHP_EOL;
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