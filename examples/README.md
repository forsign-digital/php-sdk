# ForSign PHP SDK Examples

This directory contains examples of how to use the ForSign PHP SDK.

## Prerequisites

Before running these examples, make sure you have:

1. Installed the SDK via Composer
2. Set up your API key from the ForSign platform

## Running the Examples

To run an example, navigate to the examples directory and run the PHP script:

```bash
cd examples
php create_operation.php
```

Remember to replace the placeholder API key and other values in the examples with your actual values.

## Available Examples

### Creating an Operation

[create_operation.php](create_operation.php) - Shows how to create a new operation with signers, observers, and attachments.

[create_operation_with_tags.php](create_operation_with_tags.php) - Shows how to upload a document and position the signature using a text tag inside the PDF.

### Downloading an Operation as ZIP

[download_operation_zip.php](download_operation_zip.php) - Shows how to download all files of an operation as a ZIP archive.

### Managing Attachments

[manage_attachments.php](manage_attachments.php) - Shows how to get, approve, reject, and download attachments.

### Setting Completion Modes

[set_completion_mode.php](set_completion_mode.php) - Shows how to set an operation to automatic or manual completion, and how to complete an operation.

### Canceling an Operation

[cancel_operation.php](cancel_operation.php) - Shows how to cancel an operation.

## Customizing the Examples

Feel free to modify these examples to suit your needs. You can change the API key, operation IDs, and other parameters to match your environment.

## Error Handling

All examples include error handling to demonstrate how to handle exceptions thrown by the SDK. You should implement similar error handling in your own code.

## Further Reading

For more information on how to use the ForSign PHP SDK, see the [main README](../README.md) file.
