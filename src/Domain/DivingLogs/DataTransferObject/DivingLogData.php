<?php

namespace Domain\DivingLogs\DataTransferObject;

use Carbon\Carbon;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\States\DraftDivingLogState;
use Livewire\Wireable;

class DivingLogData implements Wireable
{
    public function __construct(
        public ?int $id = null,
        public string $individual_id = '',
        public ?int $dive_type = null,
        public ?string $category = null,
        public ?int $buddy_id = null,
        public ?int $diving_location_id = null,
        public ?DivingLocation $location = null,
        public string $date_and_time = '',
        public ?int $dive_site_score = null,
        public ?string $environment_entry = null,
        public ?string $environment_water_type = null,
        public ?string $environment_current = null,
        public ?string $environment_surface = null,
        public ?int $environment_water_temperature = null,
        public ?string $environment_water_temperature_unit = null,
        public ?int $environment_air_temperature = null,
        public ?string $environment_air_temperature_unit = null,
        public ?int $environment_water_visibility = null,
        public ?string $environment_water_visibility_unit = null,
        public ?string $wildlife = null,
        public ?string $notes = null,
        public string $status_class = DraftDivingLogState::class,
        public ?array $freedivingData = null,
        public ?array $divingData = null,
        public ?array $extendedRangeData = null,
        public ?array $rebreatherSCRData = null,
        public ?array $rebreatherCCRData = null
    ) {
        $this->date_and_time = $date_and_time ?: Carbon::now()->toDateTimeString();
    }

    public static function fromArray(array $data): self
    {
        $dto = new self(
            $data['id'] ?? null,
            $data['individual_id'],
            $data['dive_type'],
            $data['category'] ?? null,
            $data['buddy_id'] ?? null,
            $data['diving_location_id'] ?? null,
            null,
            $data['date_and_time'],
            $data['dive_site_score'] ?? null,
            $data['environment_entry'] ?? null,
            $data['environment_water_type'] ?? null,
            $data['environment_current'] ?? null,
            $data['environment_surface'] ?? null,
            $data['environment_water_temperature'] ?? null,
            $data['environment_water_temperature_unit'] ?? null,
            $data['environment_air_temperature'] ?? null,
            $data['environment_air_temperature_unit'] ?? null,
            $data['environment_water_visibility'] ?? null,
            $data['environment_water_visibility_unit'] ?? null,
            $data['wildlife'] ?? null,
            $data['notes'] ?? null,
            $data['status_class'] ?? DraftDivingLogState::class,
            $data['freedivingData'] ?? null,
            $data['divingData'] ?? null,
            $data['extendedRangeData'] ?? null,
            $data['rebreatherSCRData'] ?? null,
            $data['rebreatherCCRData'] ?? null
        );

        if (isset($data['diving_location_id'])) {
            $dto->location = DivingLocation::find($data['diving_location_id']);
        }

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'individual_id' => $this->individual_id,
            'dive_type' => $this->dive_type,
            'category' => $this->category,
            'buddy_id' => $this->buddy_id,
            'diving_location_id' => $this->diving_location_id,
            // 'location' => $this->location, // Intentionally commented out for saving DivingLog
            'date_and_time' => $this->date_and_time,
            'dive_site_score' => $this->dive_site_score,
            'environment_entry' => $this->environment_entry,
            'environment_water_type' => $this->environment_water_type,
            'environment_current' => $this->environment_current,
            'environment_surface' => $this->environment_surface,
            'environment_water_temperature' => $this->environment_water_temperature,
            'environment_water_temperature_unit' => $this->environment_water_temperature_unit,
            'environment_air_temperature' => $this->environment_air_temperature,
            'environment_air_temperature_unit' => $this->environment_air_temperature_unit,
            'environment_water_visibility' => $this->environment_water_visibility,
            'environment_water_visibility_unit' => $this->environment_water_visibility_unit,
            'wildlife' => $this->wildlife,
            'notes' => $this->notes,
            'status_class' => $this->status_class,
            'freedivingData' => $this->freedivingData,
            'divingData' => $this->divingData,
            'extendedRangeData' => $this->extendedRangeData,
            'rebreatherSCRData' => $this->rebreatherSCRData,
            'rebreatherCCRData' => $this->rebreatherCCRData,
        ];
    }

    public static function toModel(DivingLogData $dto): DivingLog
    {
        $model = new DivingLog;

        $model->id = $dto->id;
        $model->individual_id = $dto->individual_id;
        $model->dive_type = $dto->dive_type;
        $model->category = $dto->category;
        $model->buddy_id = $dto->buddy_id;
        $model->diving_location_id = $dto->diving_location_id;
        $model->date_and_time = $dto->date_and_time;
        $model->dive_site_score = $dto->dive_site_score;
        $model->environment_entry = $dto->environment_entry;
        $model->environment_water_type = $dto->environment_water_type;
        $model->environment_current = $dto->environment_current;
        $model->environment_surface = $dto->environment_surface;
        $model->environment_water_temperature = $dto->environment_water_temperature;
        $model->environment_water_temperature_unit = $dto->environment_water_temperature_unit;
        $model->environment_air_temperature = $dto->environment_air_temperature;
        $model->environment_air_temperature_unit = $dto->environment_air_temperature_unit;
        $model->environment_water_visibility = $dto->environment_water_visibility;
        $model->environment_water_visibility_unit = $dto->environment_water_visibility_unit;
        $model->wildlife = $dto->wildlife;
        $model->notes = $dto->notes;
        $model->status_class = $dto->status_class;

        return $model;
    }

    public function toLivewire()
    {
        return $this->toArray();
    }

    public static function fromLivewire($data)
    {
        return self::fromArray($data);
    }
}
