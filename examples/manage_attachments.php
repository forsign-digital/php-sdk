<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Exceptions\ApiException;
use ForSign\Api\Exceptions\ValidationException;

// Replace with your API key
$apiKey = 'your-api-key';

// Replace with the member ID you want to get attachments for
$memberId = 123;

// Replace with the operation member ID for approving/rejecting attachments
$operationMemberId = 456;

// Create a client
$client = new Client($apiKey, [
    'baseUri' => 'https://api.forsign.digital', // Use the appropriate API URL
]);

try {
    // Get the list of attachments for a member
    echo "Getting attachments for member {$memberId}..." . PHP_EOL;
    $attachments = $client->operations()->getMemberAttachments($memberId);
    
    echo "Found " . count($attachments) . " attachments:" . PHP_EOL;
    
    $attachmentIds = [];
    $rejectedAttachments = [];
    
    foreach ($attachments as $index => $attachment) {
        echo PHP_EOL;
        echo "Attachment #" . ($index + 1) . ":" . PHP_EOL;
        echo "  ID: " . $attachment->getId() . PHP_EOL;
        echo "  Name: " . $attachment->getName() . PHP_EOL;
        echo "  Description: " . $attachment->getDescription() . PHP_EOL;
        echo "  Required: " . ($attachment->isRequired() ? 'Yes' : 'No') . PHP_EOL;
        echo "  Status: " . $attachment->getStatus() . PHP_EOL;
        
        if ($attachment->hasUploadedFiles()) {
            echo "  Uploaded files: " . count($attachment->getUploadedFiles()) . PHP_EOL;
            
            foreach ($attachment->getUploadedFiles() as $fileIndex => $file) {
                echo "    File #" . ($fileIndex + 1) . ":" . PHP_EOL;
                echo "      ID: " . $file['id'] . PHP_EOL;
                echo "      Name: " . $file['name'] . PHP_EOL;
                echo "      Status: " . $file['status'] . PHP_EOL;
            }
        } else {
            echo "  No files uploaded yet." . PHP_EOL;
        }
        
        // For demonstration purposes, we'll approve some attachments and reject others
        if ($index % 2 === 0) {
            // Approve even-indexed attachments
            $attachmentIds[] = $attachment->getId();
            echo "  Action: Will approve this attachment" . PHP_EOL;
        } else {
            // Reject odd-indexed attachments
            $rejectedAttachments[] = [
                'id' => $attachment->getId(),
                'reason' => 'Document is not clear or is incomplete',
            ];
            echo "  Action: Will reject this attachment" . PHP_EOL;
        }
    }
    
    // Approve attachments
    if (!empty($attachmentIds)) {
        echo PHP_EOL . "Approving " . count($attachmentIds) . " attachments..." . PHP_EOL;
        $client->operations()->approveAttachments($operationMemberId, $attachmentIds);
        echo "Attachments approved successfully." . PHP_EOL;
    }
    
    // Reject attachments
    if (!empty($rejectedAttachments)) {
        echo PHP_EOL . "Rejecting " . count($rejectedAttachments) . " attachments..." . PHP_EOL;
        $client->operations()->rejectAttachments($operationMemberId, $rejectedAttachments);
        echo "Attachments rejected successfully." . PHP_EOL;
    }
    
    // Download an attachment (using the first attachment ID if available)
    if (!empty($attachments) && $attachments[0]->hasUploadedFiles()) {
        $firstFile = $attachments[0]->getUploadedFiles()[0];
        $attachmentId = $firstFile['id'];
        
        echo PHP_EOL . "Downloading attachment {$attachmentId}..." . PHP_EOL;
        $response = $client->operations()->downloadAttachment($attachmentId);
        
        $fileName = $response->getFileName() ?? 'attachment.pdf';
        $fileSize = $response->getFileSize();
        $humanReadableSize = $response->getHumanReadableFileSize();
        
        echo "File name: {$fileName}" . PHP_EOL;
        echo "File size: {$humanReadableSize} ({$fileSize} bytes)" . PHP_EOL;
        
        // Save the attachment
        $savePath = __DIR__ . '/' . $fileName;
        if ($response->saveToFile($savePath)) {
            echo "Attachment saved to: {$savePath}" . PHP_EOL;
        } else {
            echo "Failed to save attachment." . PHP_EOL;
        }
    }
} catch (ValidationException $e) {
    echo "Validation Error: {$e->getMessage()}" . PHP_EOL;
    
    $errors = $e->getValidationErrors();
    foreach ($errors as $field => $error) {
        echo "  {$field}: {$error}" . PHP_EOL;
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
