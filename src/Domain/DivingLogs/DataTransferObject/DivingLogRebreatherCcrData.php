<?php

namespace Domain\DivingLogs\DataTransferObject;

use Domain\DivingLogs\Models\DivingLogRebreatherCcr;

class DivingLogRebreatherCcrData
{
    public function __construct(
        public ?int $diving_log_id = null,
        public ?int $runtime = null,
        public ?int $ccr_total_deco_time = null,
        public ?int $depth = null,
        public ?string $depth_unit = null,
        public ?int $bailout_sac = null,
        public ?int $deco_sac = null,
        public ?string $diluent_tank_type = null,
        public ?int $diluent_tank_volume = null,
        public ?string $diluent_tank_volume_unit = null,
        public ?int $diluent_oxygen_percentage = null,
        public ?int $diluent_helium_percentage = null,
        public ?int $diluent_start_pressure = null,
        public ?string $diluent_start_pressure_unit = null,
        public ?int $diluent_end_pressure = null,
        public ?string $diluent_end_pressure_unit = null,
        public ?array $bailout_gases = [],
        public ?string $equipment_suit = null,
        public ?string $equipment_mask = null,
        public ?string $equipment_fins = null,
        public ?string $equipment_bcd_wing_sidemount = null,
        public ?string $equipment_rebreather_unit = null,
        public ?string $equipment_dive_computer = null,
        public ?string $equipment_lights = null,
        public ?string $equipment_other = null,
        public ?int $equipment_weight = null,
        public ?string $equipment_weight_unit = null
    ) {}

    public static function fromArray(array $data): self
    {
        $bailout_gases = [];
        for ($i = 1; $i <= 3; $i++) {
            $bailout_gases[$i] = [
                'tank_type' => $data["bailout_gas_{$i}_tank_type"] ?? null,
                'tank_volume' => $data["bailout_gas_{$i}_tank_volume"] ?? null,
                'tank_volume_unit' => $data["bailout_gas_{$i}_tank_volume_unit"] ?? null,
                'oxygen_percentage' => $data["bailout_gas_{$i}_oxygen_percentage"] ?? null,
                'helium_percentage' => $data["bailout_gas_{$i}_helium_percentage"] ?? null,
                'start_pressure' => $data["bailout_gas_{$i}_start_pressure"] ?? null,
                'start_pressure_unit' => $data["bailout_gas_{$i}_start_pressure_unit"] ?? null,
                'end_pressure' => $data["bailout_gas_{$i}_end_pressure"] ?? null,
                'end_pressure_unit' => $data["bailout_gas_{$i}_end_pressure_unit"] ?? null,
            ];
        }

        return new self(
            $data['diving_log_id'] ?? null,
            $data['runtime'] ?? null,
            $data['ccr_total_deco_time'] ?? null,
            $data['depth'] ?? null,
            $data['depth_unit'] ?? null,
            $data['bailout_sac'] ?? null,
            $data['deco_sac'] ?? null,
            $data['diluent_tank_type'] ?? null,
            $data['diluent_tank_volume'] ?? null,
            $data['diluent_tank_volume_unit'] ?? null,
            $data['diluent_oxygen_percentage'] ?? null,
            $data['diluent_helium_percentage'] ?? null,
            $data['diluent_start_pressure'] ?? null,
            $data['diluent_start_pressure_unit'] ?? null,
            $data['diluent_end_pressure'] ?? null,
            $data['diluent_end_pressure_unit'] ?? null,
            $bailout_gases,
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

    public static function toModel(DivingLogRebreatherCcrData $dto, ?int $id = null): DivingLogRebreatherCcr
    {
        if ($id) {
            $model = DivingLogRebreatherCCR::findOrFail($id);
        } else {
            $model = new DivingLogRebreatherCCR;
        }

        $model->runtime = $dto->runtime;
        $model->ccr_total_deco_time = $dto->ccr_total_deco_time;
        $model->depth = $dto->depth;
        $model->depth_unit = $dto->depth_unit;
        $model->bailout_sac = $dto->bailout_sac;
        $model->deco_sac = $dto->deco_sac;
        $model->diluent_tank_type = $dto->diluent_tank_type;
        $model->diluent_tank_volume = $dto->diluent_tank_volume;
        $model->diluent_tank_volume_unit = $dto->diluent_tank_volume_unit;
        $model->diluent_oxygen_percentage = $dto->diluent_oxygen_percentage;
        $model->diluent_helium_percentage = $dto->diluent_helium_percentage;
        $model->diluent_start_pressure = $dto->diluent_start_pressure;
        $model->diluent_start_pressure_unit = $dto->diluent_start_pressure_unit;
        $model->diluent_end_pressure = $dto->diluent_end_pressure;
        $model->diluent_end_pressure_unit = $dto->diluent_end_pressure_unit;

        for ($i = 1; $i <= 3; $i++) {
            $model->{"bailout_gas_{$i}_tank_type"} = $dto->bailout_gases[$i]['tank_type'];
            $model->{"bailout_gas_{$i}_tank_volume"} = $dto->bailout_gases[$i]['tank_volume'];
            $model->{"bailout_gas_{$i}_tank_volume_unit"} = $dto->bailout_gases[$i]['tank_volume_unit'];
            $model->{"bailout_gas_{$i}_oxygen_percentage"} = $dto->bailout_gases[$i]['oxygen_percentage'];
            $model->{"bailout_gas_{$i}_helium_percentage"} = $dto->bailout_gases[$i]['helium_percentage'];
            $model->{"bailout_gas_{$i}_start_pressure"} = $dto->bailout_gases[$i]['start_pressure'];
            $model->{"bailout_gas_{$i}_start_pressure_unit"} = $dto->bailout_gases[$i]['start_pressure_unit'];
            $model->{"bailout_gas_{$i}_end_pressure"} = $dto->bailout_gases[$i]['end_pressure'];
            $model->{"bailout_gas_{$i}_end_pressure_unit"} = $dto->bailout_gases[$i]['end_pressure_unit'];
        }

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
