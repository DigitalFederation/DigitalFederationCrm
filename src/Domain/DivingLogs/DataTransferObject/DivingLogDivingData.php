<?php

namespace Domain\DivingLogs\DataTransferObject;

use Domain\DivingLogs\Models\DivingLogDiving;

class DivingLogDivingData
{
    public function __construct(
        public ?int $diving_log_id = null,
        public ?array $speciality_dive = null,
        public ?int $duration_minutes = null,
        public ?int $depth = null,
        public ?string $depth_unit = null,
        public ?int $nitrox_percentage = null,
        public ?string $tank_type = null,
        public ?int $tank_volume = null,
        public ?string $tank_volume_unit = null,
        public ?int $start_pressure = null,
        public ?string $start_pressure_unit = null,
        public ?int $end_pressure = null,
        public ?string $end_pressure_unit = null,
        public ?int $average_depth = null,
        public ?string $average_depth_unit = null,
        public ?string $equipment_suit = null,
        public ?string $equipment_mask = null,
        public ?string $equipment_fins = null,
        public ?string $equipment_bcd_wing_sidemount = null,
        public ?string $equipment_first_stage = null,
        public ?string $equipment_second_stage = null,
        public ?string $equipment_dive_computer = null,
        public ?string $equipment_lights = null,
        public ?string $equipment_other = null,
        public ?int $equipment_weight = null,
        public ?string $equipment_weight_unit = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['diving_log_id'] ?? null,
            $data['speciality_dive'] ?? null,
            $data['duration_minutes'] ?? null,
            $data['depth'] ?? null,
            $data['depth_unit'] ?? null,
            $data['nitrox_percentage'] ?? null,
            $data['tank_type'] ?? null,
            $data['tank_volume'] ?? null,
            $data['tank_volume_unit'] ?? null,
            $data['start_pressure'] ?? null,
            $data['start_pressure_unit'] ?? null,
            $data['end_pressure'] ?? null,
            $data['end_pressure_unit'] ?? null,
            $data['average_depth'] ?? null,
            $data['average_depth_unit'] ?? null,
            $data['equipment_suit'] ?? null,
            $data['equipment_mask'] ?? null,
            $data['equipment_fins'] ?? null,
            $data['equipment_bcd_wing_sidemount'] ?? null,
            $data['equipment_first_stage'] ?? null,
            $data['equipment_second_stage'] ?? null,
            $data['equipment_dive_computer'] ?? null,
            $data['equipment_lights'] ?? null,
            $data['equipment_other'] ?? null,
            $data['equipment_weight'] ?? null,
            $data['equipment_weight_unit'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'diving_log_id' => $this->diving_log_id,
            'speciality_dive' => $this->speciality_dive,
            'duration_minutes' => $this->duration_minutes,
            'depth' => $this->depth,
            'depth_unit' => $this->depth_unit,
            'nitrox_percentage' => $this->nitrox_percentage,
            'tank_type' => $this->tank_type,
            'tank_volume' => $this->tank_volume,
            'tank_volume_unit' => $this->tank_volume_unit,
            'start_pressure' => $this->start_pressure,
            'start_pressure_unit' => $this->start_pressure_unit,
            'end_pressure' => $this->end_pressure,
            'end_pressure_unit' => $this->end_pressure_unit,
            'average_depth' => $this->average_depth,
            'average_depth_unit' => $this->average_depth_unit,
            'equipment_suit' => $this->equipment_suit,
            'equipment_mask' => $this->equipment_mask,
            'equipment_fins' => $this->equipment_fins,
            'equipment_bcd_wing_sidemount' => $this->equipment_bcd_wing_sidemount,
            'equipment_first_stage' => $this->equipment_first_stage,
            'equipment_second_stage' => $this->equipment_second_stage,
            'equipment_dive_computer' => $this->equipment_dive_computer,
            'equipment_lights' => $this->equipment_lights,
            'equipment_other' => $this->equipment_other,
            'equipment_weight' => $this->equipment_weight,
            'equipment_weight_unit' => $this->equipment_weight_unit,
        ];
    }

    public static function toModel(DivingLogDivingData $dto, ?int $id = null): DivingLogDiving
    {
        if ($id) {
            $model = DivingLogDiving::findOrFail($id);
        } else {
            $model = new DivingLogDiving;
        }

        $model->diving_log_id = $dto->diving_log_id;
        $model->speciality_dive = $dto->speciality_dive ? json_encode($dto->speciality_dive) : null;
        $model->duration_minutes = $dto->duration_minutes;
        $model->depth = $dto->depth;
        $model->depth_unit = $dto->depth_unit;
        $model->nitrox_percentage = $dto->nitrox_percentage;
        $model->tank_type = $dto->tank_type;
        $model->tank_volume = $dto->tank_volume;
        $model->tank_volume_unit = $dto->tank_volume_unit;
        $model->start_pressure = $dto->start_pressure;
        $model->start_pressure_unit = $dto->start_pressure_unit;
        $model->end_pressure = $dto->end_pressure;
        $model->end_pressure_unit = $dto->end_pressure_unit;
        $model->average_depth = $dto->average_depth;
        $model->average_depth_unit = $dto->average_depth_unit;
        $model->equipment_suit = $dto->equipment_suit;
        $model->equipment_mask = $dto->equipment_mask;
        $model->equipment_fins = $dto->equipment_fins;
        $model->equipment_bcd_wing_sidemount = $dto->equipment_bcd_wing_sidemount;
        $model->equipment_first_stage = $dto->equipment_first_stage;
        $model->equipment_second_stage = $dto->equipment_second_stage;
        $model->equipment_dive_computer = $dto->equipment_dive_computer;
        $model->equipment_lights = $dto->equipment_lights;
        $model->equipment_other = $dto->equipment_other;
        $model->equipment_weight = $dto->equipment_weight;
        $model->equipment_weight_unit = $dto->equipment_weight_unit;

        return $model;
    }
}
