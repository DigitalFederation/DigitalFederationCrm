<?php

namespace Domain\DivingLogs\DataTransferObject;

use Domain\DivingLogs\Models\DivingLogExtendedRange;

class DivingLogExtendedRangeData
{
    public function __construct(
        public ?int $diving_log_id = null,
        public ?int $total_runtime = null,
        public ?int $total_deco_time = null,
        public ?int $depth = null,
        public ?string $depth_unit = null,
        public ?string $configuration = null,
        public ?int $sac_bottom_sac = null,
        public ?int $sac_sac = null,
        public ?int $sac_deco_sac = null,
        public ?string $details_si_before = null,
        public ?string $details_gf_set = null,
        public ?string $details_gradient_factor_end = null,
        public ?string $details_cns_start = null,
        public ?string $details_cns_end = null,
        public ?string $details_otu_start = null,
        public ?string $details_otu_end = null,
        public ?string $back_gas_tank_type = null,
        public ?int $back_gas_tank_volume = null,
        public ?string $back_gas_tank_volume_unit = null,
        public ?bool $back_gas_oxygen_percentage = null,
        public ?bool $back_gas_helium_percentage = null,
        public ?int $back_gas_start_pressure = null,
        public ?string $back_gas_start_pressure_unit = null,
        public ?int $back_gas_end_pressure = null,
        public ?string $back_gas_end_pressure_unit = null,
        public ?array $gases = []
    ) {}

    public static function fromArray(array $data): self
    {
        $gases = [];
        for ($i = 1; $i <= 3; $i++) {
            $gases[$i] = [
                'tank_volume' => $data["deco_gas_{$i}_tank_volume"] ?? null,
                'tank_volume_unit' => $data["deco_gas_{$i}_tank_volume_unit"] ?? null,
                'start_pressure' => $data["deco_gas_{$i}_start_pressure"] ?? null,
                'start_pressure_unit' => $data["deco_gas_{$i}_start_pressure_unit"] ?? null,
                'end_pressure' => $data["deco_gas_{$i}_end_pressure"] ?? null,
                'end_pressure_unit' => $data["deco_gas_{$i}_end_pressure_unit"] ?? null,
                'tank_type' => $data["deco_gas_{$i}_tank_type"] ?? null,
                'oxygen_percentage' => $data["deco_gas_{$i}_oxygen_percentage"] ?? null,
                'helium_percentage' => $data["deco_gas_{$i}_helium_percentage"] ?? null,
            ];
        }

        return new self(
            $data['diving_log_id'] ?? null,
            $data['total_runtime'] ?? null,
            $data['total_deco_time'] ?? null,
            $data['depth'] ?? null,
            $data['depth_unit'] ?? null,
            $data['configuration'] ?? null,
            $data['sac_bottom_sac'] ?? null,
            $data['sac_sac'] ?? null,
            $data['sac_deco_sac'] ?? null,
            $data['details_si_before'] ?? null,
            $data['details_gf_set'] ?? null,
            $data['details_gradient_factor_end'] ?? null,
            $data['details_cns_start'] ?? null,
            $data['details_cns_end'] ?? null,
            $data['details_otu_start'] ?? null,
            $data['details_otu_end'] ?? null,
            $data['back_gas_tank_type'] ?? null,
            $data['back_gas_tank_volume'] ?? null,
            $data['back_gas_tank_volume_unit'] ?? null,
            $data['back_gas_oxygen_percentage'] ?? null,
            $data['back_gas_helium_percentage'] ?? null,
            $data['back_gas_start_pressure'] ?? null,
            $data['back_gas_start_pressure_unit'] ?? null,
            $data['back_gas_end_pressure'] ?? null,
            $data['back_gas_end_pressure_unit'] ?? null,
            $gases
        );
    }

    public static function toModel(DivingLogExtendedRangeData $dto, ?int $id = null): DivingLogExtendedRange
    {
        if ($id) {
            $model = DivingLogExtendedRange::findOrFail($id);
        } else {
            $model = new DivingLogExtendedRange;
        }
        $model->diving_log_id = $dto->diving_log_id;
        $model->total_runtime = $dto->total_runtime;
        $model->total_deco_time = $dto->total_deco_time;
        $model->depth = $dto->depth;
        $model->depth_unit = $dto->depth_unit;
        $model->configuration = $dto->configuration;
        $model->sac_bottom_sac = $dto->sac_bottom_sac;
        $model->sac_sac = $dto->sac_sac;
        $model->sac_deco_sac = $dto->sac_deco_sac;
        $model->details_si_before = $dto->details_si_before;
        $model->details_gf_set = $dto->details_gf_set;
        $model->details_gradient_factor_end = $dto->details_gradient_factor_end;
        $model->details_cns_start = $dto->details_cns_start;
        $model->details_cns_end = $dto->details_cns_end;
        $model->details_otu_start = $dto->details_otu_start;
        $model->details_otu_end = $dto->details_otu_end;

        $model->back_gas_tank_type = $dto->back_gas_tank_type;
        $model->back_gas_tank_volume = $dto->back_gas_tank_volume;
        $model->back_gas_tank_volume_unit = $dto->back_gas_tank_volume_unit;
        $model->back_gas_oxygen_percentage = $dto->back_gas_oxygen_percentage;
        $model->back_gas_helium_percentage = $dto->back_gas_helium_percentage;
        $model->back_gas_start_pressure = $dto->back_gas_start_pressure;
        $model->back_gas_start_pressure_unit = $dto->back_gas_start_pressure_unit;
        $model->back_gas_end_pressure = $dto->back_gas_end_pressure;
        $model->back_gas_end_pressure_unit = $dto->back_gas_end_pressure_unit;

        for ($i = 1; $i <= 3; $i++) {
            $model->{"deco_gas_{$i}_tank_type"} = $dto->gases[$i]['tank_type'];
            $model->{"deco_gas_{$i}_tank_volume"} = $dto->gases[$i]['tank_volume'];
            $model->{"deco_gas_{$i}_tank_volume_unit"} = $dto->gases[$i]['tank_volume_unit'];
            $model->{"deco_gas_{$i}_oxygen_percentage"} = $dto->gases[$i]['oxygen_percentage'];
            $model->{"deco_gas_{$i}_helium_percentage"} = $dto->gases[$i]['helium_percentage'];
            $model->{"deco_gas_{$i}_start_pressure"} = $dto->gases[$i]['start_pressure'];
            $model->{"deco_gas_{$i}_start_pressure_unit"} = $dto->gases[$i]['start_pressure_unit'];
            $model->{"deco_gas_{$i}_end_pressure"} = $dto->gases[$i]['end_pressure'];
            $model->{"deco_gas_{$i}_end_pressure_unit"} = $dto->gases[$i]['end_pressure_unit'];
        }

        return $model;
    }
}
