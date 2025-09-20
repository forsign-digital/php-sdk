<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Exceptions\ApiException;

// Replace with your API key
$apiKey = 'your-api-key';

// Replace with the operation ID you want to download
$operationId = 123;

// Create a client
$client = new Client($apiKey, [
    'baseUri' => 'https://api.forsign.digital', // Use the appropriate API URL
]);

try {
    // Download the operation as a ZIP file
    $response = $client->operations()->downloadZip($operationId);
    
    // Get the ZIP file name
    $fileName = $response->getName();
    
    // Get the ZIP file content
    $content = $response->getFileContent();
    
    // Get the file size
    $fileSize = $response->getFileSize();
    $humanReadableSize = $response->getHumanReadableFileSize();
    
    echo "Downloading operation {$operationId} as ZIP file: {$fileName}" . PHP_EOL;
    echo "File size: {$humanReadableSize} ({$fileSize} bytes)" . PHP_EOL;
    
    // Save the ZIP file
    $savePath = __DIR__ . '/' . $fileName;
    if ($response->saveToFile($savePath)) {
        echo "ZIP file saved to: {$savePath}" . PHP_EOL;
    } else {
        echo "Failed to save ZIP file." . PHP_EOL;
    }
} catch (ApiException $e) {
    echo "API Error: {$e->getMessage()}" . PHP_EOL;
    echo "Status Code: {$e->getStatusCode()}" . PHP_EOL;
    
    if ($e->getMessages() !== null) {
        echo "Error Messages:" . PHP_EOL;
        foreach ($e->getMessages() as $message) {
            echo "- {$message}" . PHP_EOL;
        }
    }
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}" . PHP_EOL;
}
