<?php

namespace Domain\Products\DataTransferObject;

use Domain\Products\Models\Product;

class ProductData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $description,
        public readonly ?float $price,
        public readonly ?float $tax_value,
        public readonly ?float $tax_percentage
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['code'] ?? null,
            $data['description'] ?? null,
            $data['price'] ?? 0,
            $data['price'] * ($data['tax_percentage'] / 100) ?? $data['price'],
            $data['tax_percentage'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'price' => $this->price,
            'tax_value' => $this->tax_value,
            'tax_percentage' => $this->tax_percentage,
        ];
    }

    public static function toModel(ProductData $dto): Product
    {
        $model = new Product;
        $model->name = $dto->name;
        $model->code = $dto->code;
        $model->description = $dto->description;
        $model->price = $dto->price;
        $model->tax_value = $dto->tax_value;
        $model->tax_percentage = $dto->tax_percentage;

        return $model;
    }
}
