<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response model for the document upload endpoint.
 */
class DocumentUploadResponse extends BaseResponse
{
    /**
     * Gets the meta information from the response.
     *
     * @return array{message: string}
     */
    public function getMeta(): array
    {
        return $this->data['meta'] ?? ['message' => ''];
    }

    /**
     * Gets the main data of the uploaded document.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data['data'] ?? [];
    }

    /**
     * Gets the unique identifier for the uploaded document.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getData()['id'] ?? null;
    }

    /**
     * Gets the name of the file as stored in the system.
     *
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->getData()['fileName'] ?? null;
    }

    /**
     * Gets the total number of pages in the document.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return (int) ($this->getData()['totalPages'] ?? 0);
    }

    /**
     * Gets detailed information about each page in the document.
     *
     * @return array
     */
    public function getImagesDetail(): array
    {
        return $this->getData()['imagesDetail'] ?? [];
    }

    /**
     * Gets the page details for a specific page number.
     *
     * @param int $pageNumber
     *
     * @return array|null
     */
    public function getPageDetail(int $pageNumber): ?array
    {
        foreach ($this->getImagesDetail() as $page) {
            if (isset($page['page']) && (int) $page['page'] === $pageNumber) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Gets the original size of a specific page.
     *
     * @param int $pageNumber
     *
     * @return array{width: float, height: float}|null
     */
    public function getPageSize(int $pageNumber): ?array
    {
        $pageDetail = $this->getPageDetail($pageNumber);

        if ($pageDetail && isset($pageDetail['originalSize'])) {
            return [
                'width' => (float) ($pageDetail['originalSize']['width'] ?? 0.0),
                'height' => (float) ($pageDetail['originalSize']['height'] ?? 0.0),
            ];
        }

        return null;
    }
}
