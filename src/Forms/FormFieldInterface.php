<?php

declare(strict_types=1);

namespace ForSign\Api\Forms;

interface FormFieldInterface
{
    /**
     * Converts the form field object into the array structure required by the API.
     * @return array
     */
    public function convert(): array;
}
