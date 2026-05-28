<?php

namespace Domain\DivingLogs\DataTransferObject;

use Domain\DivingLogs\Models\DivingLogFreediving;

class DivingLogFreedivingData
{
    public function __construct(
        public ?int $diving_log_id = null,
        public ?string $freedive_discipline = null,
        public ?int $warm_ups = null,
        public ?int $max_time = null,
        public ?int $contraction_time = null,
        public ?int $time = null,
        public ?int $max_distance = null,
        public ?string $max_distance_unit = null,
        public ?int $max_depth = null,
        public ?string $max_depth_unit = null,
        public ?string $equipment_suit = null,
        public ?string $equipment_mask = null,
        public ?string $equipment_fins = null,
        public ?string $equipment_dive_computer = null,
        public ?string $equipment_other = null,
        public ?int $equipment_weight = null,
        public ?string $equipment_weight_unit = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['diving_log_id'] ?? null,
            $data['freedive_discipline'] ?? null,
            $data['warm_ups'] ?? null,
            $data['max_time'] ?? null,
            $data['contraction_time'] ?? null,
            $data['time'] ?? null,
            $data['max_distance'] ?? null,
            $data['max_distance_unit'] ?? null,
            $data['max_depth'] ?? null,
            $data['max_depth_unit'] ?? null,
            $data['equipment_suit'] ?? null,
            $data['equipment_mask'] ?? null,
            $data['equipment_fins'] ?? null,
            $data['equipment_dive_computer'] ?? null,
            $data['equipment_other'] ?? null,
            $data['equipment_weight'] ?? null,
            $data['equipment_weight_unit'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'diving_log_id' => $this->diving_log_id,
            'freedive_discipline' => $this->freedive_discipline,
            'warm_ups' => $this->warm_ups,
            'max_time' => $this->max_time,
            'contraction_time' => $this->contraction_time,
            'time' => $this->time,
            'max_distance' => $this->max_distance,
            'max_distance_unit' => $this->max_distance_unit,
            'max_depth' => $this->max_depth,
            'max_depth_unit' => $this->max_depth_unit,
            'equipment_suit' => $this->equipment_suit,
            'equipment_mask' => $this->equipment_mask,
            'equipment_fins' => $this->equipment_fins,
            'equipment_dive_computer' => $this->equipment_dive_computer,
            'equipment_other' => $this->equipment_other,
            'equipment_weight' => $this->equipment_weight,
            'equipment_weight_unit' => $this->equipment_weight_unit,
        ];
    }

    public static function toModel(DivingLogFreedivingData $dto, ?int $id = null): DivingLogFreediving
    {
        if ($id) {
            $model = DivingLogFreediving::findOrFail($id);
        } else {
            $model = new DivingLogFreediving;
        }

        $model->diving_log_id = $dto->diving_log_id;
        $model->freedive_discipline = $dto->freedive_discipline;
        $model->warm_ups = $dto->warm_ups;
        $model->max_time = $dto->max_time;
        $model->contraction_time = $dto->contraction_time;
        $model->time = $dto->time;
        $model->max_distance = $dto->max_distance;
        $model->max_distance_unit = $dto->max_distance_unit;
        $model->max_depth = $dto->max_depth;
        $model->max_depth_unit = $dto->max_depth_unit;
        $model->equipment_suit = $dto->equipment_suit;
        $model->equipment_mask = $dto->equipment_mask;
        $model->equipment_fins = $dto->equipment_fins;
        $model->equipment_dive_computer = $dto->equipment_dive_computer;
        $model->equipment_other = $dto->equipment_other;
        $model->equipment_weight = $dto->equipment_weight;
        $model->equipment_weight_unit = $dto->equipment_weight_unit;

        return $model;
    }
}
