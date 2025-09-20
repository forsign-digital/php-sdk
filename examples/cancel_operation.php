<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Exceptions\ApiException;

// Replace with your API key
$apiKey = 'SUA_API_KEY_AQUI';

// Replace with the operation ID you want to cancel
$operationId = 6725;

// Create a client
$client = new Client($apiKey, [
    'baseUri' => 'https://api.forsign.digital', // Use the appropriate API URL
]);

try {
    // Cancel the operation
    echo "Canceling operation {$operationId}..." . PHP_EOL;
    
    // Provide a reason for cancellation
    $cancellationReason = 'The document needs to be revised before signing.';
    
    $response = $client->operations()->cancel($operationId, $cancellationReason);
    
    if ($response->isSuccess()) {
        echo "Operation canceled successfully." . PHP_EOL;
        echo "Operation ID: " . $response->getOperationId() . PHP_EOL;
        echo "Operation name: " . $response->getOperationName() . PHP_EOL;
        echo "Operation status: " . $response->getOperationStatus() . PHP_EOL;
        
        // Get cancellation details
        echo "Cancellation reason: " . $response->getCancellationReason() . PHP_EOL;
        
        if ($response->getCancellationDate() !== null) {
            echo "Cancellation date: " . $response->getCancellationDate()->format('Y-m-d H:i:s') . PHP_EOL;
        }
        
        if ($response->getCanceledBy() !== null) {
            echo "Canceled by: " . $response->getCanceledBy() . PHP_EOL;
        }
    } else {
        echo "Failed to cancel the operation." . PHP_EOL;
        echo "Message: " . $response->getMessage() . PHP_EOL;
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
