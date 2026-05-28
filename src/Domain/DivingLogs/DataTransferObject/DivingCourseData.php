<?php

declare(strict_types=1);

namespace Domain\DivingLogs\DataTransferObject;

use Illuminate\Http\Request;

class DivingCourseData
{
    public function __construct(
        public readonly int $entity_id,
        public readonly ?string $name = null,
        public readonly ?string $certification_system = null,
        public readonly ?int $district_id = null,
        public readonly ?string $location = null,
        public readonly ?int $certification_id = null,
        public readonly ?string $start_date = null,
        public readonly ?string $about = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            entity_id: (int) $request->input('entity_id'),
            name: $request->input('name'),
            certification_system: $request->input('certification_system'),
            district_id: $request->filled('district_id') ? (int) $request->input('district_id') : null,
            location: $request->input('location'),
            certification_id: $request->filled('certification_id') ? (int) $request->input('certification_id') : null,
            start_date: $request->filled('start_date') ? $request->input('start_date') : null,
            about: $request->input('about')
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            entity_id: $data['entity_id'],
            name: $data['name'] ?? null,
            certification_system: $data['certification_system'] ?? null,
            district_id: isset($data['district_id']) ? (int) $data['district_id'] : null,
            location: $data['location'] ?? null,
            certification_id: isset($data['certification_id']) ? (int) $data['certification_id'] : null,
            start_date: $data['start_date'] ?? null,
            about: $data['about'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'entity_id' => $this->entity_id,
            'name' => $this->name,
            'certification_system' => $this->certification_system,
            'district_id' => $this->district_id,
            'location' => $this->location,
            'certification_id' => $this->certification_id,
            'start_date' => $this->start_date,
            'about' => $this->about,
        ];
    }
}
