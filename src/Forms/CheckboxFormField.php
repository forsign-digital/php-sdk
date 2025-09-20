<?php

declare(strict_types=1);

namespace ForSign\Api\Forms;

use InvalidArgumentException;

class CheckboxFormField implements FormFieldInterface
{
    private string $name;
    private string $instructions = '';
    private bool $required = false;
    private array $options = [];
    private ?string $value = null;
    private float $height = 2.48;
    private float $width = 24.66;
    /** @var FormFieldPosition[] */
    private array $positions = [];

    private function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Field name cannot be empty.');
        }
        $this->name = $name;
    }

    public static function withName(string $name): self
    {
        return new self($name);
    }

    public function withInstructions(string $instructions): self
    {
        $this->instructions = $instructions;
        return $this;
    }

    public function isRequired(bool $required = true): self
    {
        $this->required = $required;
        return $this;
    }

    public function withOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function onPosition(FormFieldPosition $position): self
    {
        $this->positions[] = $position;
        return $this;
    }

    public function withSize(float $height, float $width): self
    {
        $this->height = $height;
        $this->width = $width;
        return $this;
    }

    public function convert(): array
    {
        $result = [];
        foreach ($this->positions as $position) {
            $optionsData = [];
            foreach ($this->options as $option) {
                $optionsData[] = [
                    'Name' => $option,
                    'CoordenateX' => $position->getCoordinateX(),
                    'CoordenateY' => $position->getCoordinateY(),
                    'Height' => number_format($this->height, 2, '.', '') . '%',
                    'Width' => number_format($this->width, 2, '.', '') . '%',
                    'Value' => ($option === $this->value) ? 'X' : '',
                ];
            }

            $result[] = [
                'Name' => $this->name,
                'Description' => $this->instructions,
                'Required' => $this->required,
                'Type' => 'Others',
                'Variant' => 'Checkbox',
                'FieldType' => 'Select',
                'Value' => $this->value,
                'DocumentId' => $position->getFileInfo()->getFileId(),
                'Positions' => [
                    [
                        'Page' => $position->getPage(),
                        'CoordenateX' => $position->getCoordinateX(),
                        'CoordenateY' => $position->getCoordinateY(),
                        'Height' => number_format($this->height, 2, '.', '') . '%',
                        'Width' => number_format($this->width, 2, '.', '') . '%',
                    ],
                ],
                'Options' => $optionsData
            ];
        }

        return $result;
    }
}