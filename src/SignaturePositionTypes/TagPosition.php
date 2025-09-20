<?php

declare(strict_types=1);

namespace ForSign\Api\SignaturePositionTypes;

use ForSign\Api\Requests\FileInformation;
use InvalidArgumentException;

/**
 * Represents the position of a signature based on a tag pattern within a document.
 */
class TagPosition implements SignaturePositionInterface
{
    private FileInformation $fileInformation;
    private string $tagPattern;

    /**
     * @param FileInformation $fileInformation The file where the tag is located.
     * @param string $tagPattern The pattern of the tag (e.g., "{{assinatura}}").
     */
    public function __construct(FileInformation $fileInformation, string $tagPattern)
    {
        if (empty($tagPattern)) {
            throw new InvalidArgumentException('Tag pattern cannot be empty.');
        }

        $this->fileInformation = $fileInformation;
        $this->tagPattern = $tagPattern;
    }

    public function getFileInformation(): FileInformation
    {
        return $this->fileInformation;
    }

    public function getTagPattern(): string
    {
        return $this->tagPattern;
    }
}
