<?php

namespace Domain\DivingLogs\DataTransferObject;

use Domain\DivingLogs\Models\DivingLogRebreatherScr;

class DivingLogRebreatherScrData
{
    public function __construct(
        public ?int $diving_log_id = null,
        public ?int $runtime = null,
        public ?int $scr_total_deco_time = null,
        public ?int $depth = null,
        public ?string $depth_unit = null,
        public ?int $weight = null,
        public ?string $weight_unit = null,
        public ?int $bailout_sac = null,
        public ?int $deco_sac = null,
        public ?int $tank_volume = null,
        public ?string $tank_volume_unit = null,
        public ?int $oxygen_percentage = null,
        public ?int $setpoint = null,
        public ?int $start_pressure = null,
        public ?string $start_pressure_unit = null,
        public ?int $end_pressure = null,
        public ?string $end_pressure_unit = null,
        public ?int $deco_tank_volume = null,
        public ?string $deco_tank_volume_unit = null,
        public ?int $deco_oxygen_percentage = null,
        public ?int $deco_setpoint = null,
        public ?int $deco_start_pressure = null,
        public ?string $deco_start_pressure_unit = null,
        public ?int $deco_end_pressure = null,
        public ?string $deco_end_pressure_unit = null,
        public ?string $equipment_suit = null,
        public ?string $equipment_mask = null,
        public ?string $equipment_fins = null,
        public ?string $equipment_bcd_wing_sidemount = null,
        public ?string $equipment_rebreather_unit = null,
        public ?string $equipment_dive_computer = null,
        public ?string $equipment_lights = null,
        public ?string $equipment_other = null,
        public ?int $equipment_weight = null,
        public ?string $equipment_weight_unit = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['diving_log_id'] ?? null,
            $data['runtime'] ?? null,
            $data['scr_total_deco_time'] ?? null,
            $data['depth'] ?? null,
            $data['depth_unit'] ?? null,
            $data['weight'] ?? null,
            $data['weight_unit'] ?? null,
            $data['bailout_sac'] ?? null,
            $data['deco_sac'] ?? null,
            $data['tank_volume'] ?? null,
            $data['tank_volume_unit'] ?? null,
            $data['oxygen_percentage'] ?? null,
            $data['setpoint'] ?? null,
            $data['start_pressure'] ?? null,
            $data['start_pressure_unit'] ?? null,
            $data['end_pressure'] ?? null,
            $data['end_pressure_unit'] ?? null,
            $data['deco_tank_volume'] ?? null,
            $data['deco_tank_volume_unit'] ?? null,
            $data['deco_oxygen_percentage'] ?? null,
            $data['deco_setpoint'] ?? null,
            $data['deco_start_pressure'] ?? null,
            $data['deco_start_pressure_unit'] ?? null,
            $data['deco_end_pressure'] ?? null,
            $data['deco_end_pressure_unit'] ?? null,
            $data['equipment_suit'] ?? null,
            $data['equipment_mask'] ?? null,
            $data['equipment_fins'] ?? null,
            $data['equipment_bcd_wing_sidemount'] ?? null,
            $data['equipment_rebreather_unit'] ?? null,
            $data['equipment_dive_computer'] ?? null,
            $data['equipment_lights'] ?? null,
            $data['equipment_other'] ?? null,
            $data['equipment_weight'] ?? null,
            $data['equipment_weight_unit'] ?? null
        );
    }

    public static function toModel(DivingLogRebreatherScrData $dto, ?int $id = null): DivingLogRebreatherSCR
    {
        if ($id) {
            $model = DivingLogRebreatherSCR::findOrFail($id);
        } else {
            $model = new DivingLogRebreatherSCR;
        }

        $model->diving_log_id = $dto->diving_log_id;
        $model->runtime = $dto->runtime;
        $model->scr_total_deco_time = $dto->scr_total_deco_time;
        $model->depth = $dto->depth;
        $model->depth_unit = $dto->depth_unit;
        $model->weight = $dto->weight;
        $model->weight_unit = $dto->weight_unit;
        $model->bailout_sac = $dto->bailout_sac;
        $model->deco_sac = $dto->deco_sac;
        $model->tank_volume = $dto->tank_volume;
        $model->tank_volume_unit = $dto->tank_volume_unit;
        $model->oxygen_percentage = $dto->oxygen_percentage;
        $model->setpoint = $dto->setpoint;
        $model->start_pressure = $dto->start_pressure;
        $model->start_pressure_unit = $dto->start_pressure_unit;
        $model->end_pressure = $dto->end_pressure;
        $model->end_pressure_unit = $dto->end_pressure_unit;
        $model->deco_tank_volume = $dto->deco_tank_volume;
        $model->deco_tank_volume_unit = $dto->deco_tank_volume_unit;
        $model->deco_oxygen_percentage = $dto->deco_oxygen_percentage;
        $model->deco_setpoint = $dto->deco_setpoint;
        $model->deco_start_pressure = $dto->deco_start_pressure;
        $model->deco_start_pressure_unit = $dto->deco_start_pressure_unit;
        $model->deco_end_pressure = $dto->deco_end_pressure;
        $model->deco_end_pressure_unit = $dto->deco_end_pressure_unit;
        $model->equipment_suit = $dto->equipment_suit;
        $model->equipment_mask = $dto->equipment_mask;
        $model->equipment_fins = $dto->equipment_fins;
        $model->equipment_bcd_wing_sidemount = $dto->equipment_bcd_wing_sidemount;
        $model->equipment_rebreather_unit = $dto->equipment_rebreather_unit;
        $model->equipment_dive_computer = $dto->equipment_dive_computer;
        $model->equipment_lights = $dto->equipment_lights;
        $model->equipment_other = $dto->equipment_other;
        $model->equipment_weight = $dto->equipment_weight;
        $model->equipment_weight_unit = $dto->equipment_weight_unit;

        return $model;
    }
}
