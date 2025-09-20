<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Exceptions\ApiException;

// Replace with your API key
$apiKey = 'your-api-key';

// Replace with the operation ID you want to modify
$operationId = 123;

// Create a client
$client = new Client($apiKey, [
    'baseUri' => 'https://api.forsign.digital', // Use the appropriate API URL
]);

try {
    // Example 1: Set automatic completion
    echo "Setting automatic completion for operation {$operationId}..." . PHP_EOL;
    
    // Set the operation to be automatically completed after 30 days
    $endDate = new \DateTime();
    $endDate->add(new \DateInterval('P30D'));
    
    $response = $client->operations()->setAutomaticCompletion($operationId, $endDate);
    
    if ($response->isSuccess()) {
        echo "Operation set to automatic completion successfully." . PHP_EOL;
        echo "End date: " . $response->getEndDate()->format('Y-m-d H:i:s') . PHP_EOL;
        echo "Completion type: " . $response->getCompletionType() . PHP_EOL;
        
        // Verify that the completion type is automatic
        if ($response->isAutomaticCompletion()) {
            echo "Confirmed: The operation is set to automatic completion." . PHP_EOL;
        } else {
            echo "Warning: The operation is not set to automatic completion." . PHP_EOL;
        }
    } else {
        echo "Failed to set automatic completion." . PHP_EOL;
        echo "Message: " . $response->getMessage() . PHP_EOL;
    }
    
    // Wait a bit before changing the completion mode
    echo PHP_EOL . "Waiting 5 seconds before changing the completion mode..." . PHP_EOL;
    sleep(5);
    
    // Example 2: Set manual completion
    echo PHP_EOL . "Setting manual completion for operation {$operationId}..." . PHP_EOL;
    
    $response = $client->operations()->setManualCompletion($operationId);
    
    if ($response->isSuccess()) {
        echo "Operation set to manual completion successfully." . PHP_EOL;
        echo "Completion type: " . $response->getCompletionType() . PHP_EOL;
        
        // Verify that the completion type is manual
        if ($response->isManualCompletion()) {
            echo "Confirmed: The operation is set to manual completion." . PHP_EOL;
        } else {
            echo "Warning: The operation is not set to manual completion." . PHP_EOL;
        }
    } else {
        echo "Failed to set manual completion." . PHP_EOL;
        echo "Message: " . $response->getMessage() . PHP_EOL;
    }
    
    // Wait a bit before completing the operation
    echo PHP_EOL . "Waiting 5 seconds before completing the operation..." . PHP_EOL;
    sleep(5);
    
    // Example 3: Complete the operation
    echo PHP_EOL . "Completing operation {$operationId}..." . PHP_EOL;
    
    $response = $client->operations()->complete($operationId);
    
    if ($response->isSuccess()) {
        echo "Operation completed successfully." . PHP_EOL;
        echo "Operation ID: " . $response->getOperationId() . PHP_EOL;
        echo "Operation name: " . $response->getOperationName() . PHP_EOL;
        echo "Operation status: " . $response->getOperationStatus() . PHP_EOL;
        
        if ($response->getCompletionDate() !== null) {
            echo "Completion date: " . $response->getCompletionDate()->format('Y-m-d H:i:s') . PHP_EOL;
        }
    } else {
        echo "Failed to complete the operation." . PHP_EOL;
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
