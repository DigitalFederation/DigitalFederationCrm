<?php

namespace App\Livewire;

use App\Enums\DivingLogCategoryEnum;
use App\Enums\DivingLogCurrentEnum;
use App\Enums\DivingLogDiveTypeEnum;
use App\Enums\DivingLogEntryEnum;
use App\Enums\DivingLogFreediveDisciplineEnum;
use App\Enums\DivingLogFreediveDistanceDisciplineEnum;
use App\Enums\DivingLogFreediveStaticEnum;
use App\Enums\DivingLogSpecialityDiveEnum;
use App\Enums\DivingLogSurfaceEnum;
use App\Enums\DivingLogTankTypeEnum;
use App\Enums\DivingLogWaterTypeEnum;
use App\Enums\UnitDistanceEnum;
use App\Enums\UnitPressureEnum;
use App\Enums\UnitTemperatureEnum;
use App\Enums\UnitVolumeEnum;
use App\Enums\UnitWeightEnum;
use Domain\DivingLogs\Actions\SaveDivingLogAction;
use Domain\DivingLogs\DataTransferObject\DivingLogData;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogIndividualSequence;
use Domain\DivingLogs\States\PendingDivingLogState;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class DivingLogForm extends Component
{
    public $individual;

    public int $formStep = 1;

    public ?int $existing_record_id = null;

    public bool $isEditMode = false;

    public DivingLogData $divingLog;

    public array $divingLogArray;

    public DivingLog $oldDivingLog;

    public $location;

    public $isFirstDive = false;

    protected $rules = [
        'divingLogArray.freediving.equipment_suit' => 'nullable|string|max:255',
        'divingLogArray.individual_id' => 'required|string',
        'divingLogArray.dive_type' => 'required|int',
        'divingLogArray.category' => 'required|string',
        'divingLogArray.buddy_id' => 'nullable|integer',
        'divingLogArray.diving_location_id' => 'nullable|integer',
        'divingLogArray.date_and_time' => 'required|string',
        'divingLogArray.dive_site_score' => 'nullable|integer',
        'divingLogArray.environment_entry' => 'nullable|string',
        'divingLogArray.environment_water_type' => 'nullable|string',
        'divingLogArray.environment_current' => 'nullable|string',
        'divingLogArray.environment_surface' => 'nullable|string',
        'divingLogArray.environment_water_temperature' => 'nullable|integer',
        'divingLogArray.environment_water_temperature_unit' => 'nullable|string',
        'divingLogArray.environment_air_temperature' => 'nullable|integer',
        'divingLogArray.environment_air_temperature_unit' => 'nullable|string',
        'divingLogArray.environment_water_visibility' => 'nullable|integer',
        'divingLogArray.environment_water_visibility_unit' => 'nullable|string',
        'divingLogArray.wildlife' => 'nullable|string',
        'divingLogArray.notes' => 'nullable|string',
        // 'divingLogArray.status' => 'required|string',
        // divingData
        'divingLogArray.divingData.speciality_dive' => 'nullable|array',
        'divingLogArray.divingData.duration_minutes' => 'nullable|integer',
        'divingLogArray.divingData.depth' => 'nullable|integer',
        'divingLogArray.divingData.depth_unit' => 'nullable|string',
        'divingLogArray.divingData.nitrox_percentage' => 'nullable|integer',
        'divingLogArray.divingData.tank_type' => 'nullable|string',
        'divingLogArray.divingData.tank_volume' => 'nullable|integer',
        'divingLogArray.divingData.tank_volume_unit' => 'nullable|string',
        'divingLogArray.divingData.start_pressure' => 'nullable|integer',
        'divingLogArray.divingData.start_pressure_unit' => 'nullable|string',
        'divingLogArray.divingData.end_pressure' => 'nullable|integer',
        'divingLogArray.divingData.end_pressure_unit' => 'nullable|string',
        'divingLogArray.divingData.average_depth' => 'nullable|integer',
        'divingLogArray.divingData.average_depth_unit' => 'nullable|string',
        'divingLogArray.divingData.equipment_suit' => 'nullable|string',
        'divingLogArray.divingData.equipment_mask' => 'nullable|string',
        'divingLogArray.divingData.equipment_fins' => 'nullable|string',
        'divingLogArray.divingData.equipment_bcd_wing_sidemount' => 'nullable|string',
        'divingLogArray.divingData.equipment_first_stage' => 'nullable|string',
        'divingLogArray.divingData.equipment_second_stage' => 'nullable|string',
        'divingLogArray.divingData.equipment_dive_computer' => 'nullable|string',
        'divingLogArray.divingData.equipment_lights' => 'nullable|string',
        'divingLogArray.divingData.equipment_other' => 'nullable|string',
        'divingLogArray.divingData.equipment_weight' => 'nullable|integer',
        'divingLogArray.divingData.equipment_weight_unit' => 'nullable|string',
        // DivingLogExtendedRange
        'divingLogArray.extendedRangeData.total_runtime' => 'nullable|integer',
        'divingLogArray.extendedRangeData.total_deco_time' => 'nullable|integer',
        'divingLogArray.extendedRangeData.depth' => 'nullable|integer',
        'divingLogArray.extendedRangeData.depth_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.configuration' => 'nullable|string',
        'divingLogArray.extendedRangeData.sac_bottom_sac' => 'nullable|integer',
        'divingLogArray.extendedRangeData.sac_sac' => 'nullable|integer',
        'divingLogArray.extendedRangeData.sac_deco_sac' => 'nullable|integer',
        'divingLogArray.extendedRangeData.details_si_before' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_gf_set' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_gradient_factor_end' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_cns_start' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_cns_end' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_otu_start' => 'nullable|string',
        'divingLogArray.extendedRangeData.details_otu_end' => 'nullable|string',
        'divingLogArray.extendedRangeData.back_gas_tank_type' => 'nullable|string',
        'divingLogArray.extendedRangeData.back_gas_tank_volume' => 'nullable|integer',
        'divingLogArray.extendedRangeData.back_gas_tank_volume_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.back_gas_oxygen_percentage' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.back_gas_helium_percentage' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.back_gas_start_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.back_gas_start_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.back_gas_end_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.back_gas_end_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_1_tank_type' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_1_tank_volume' => 'nullable|integer',
        'divingLogArray.extendedRangeData.deco_gas_1_tank_volume_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_1_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_1_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_1_start_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_1_start_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_1_end_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_1_end_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_2_tank_type' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_2_tank_volume' => 'nullable|integer',
        'divingLogArray.extendedRangeData.deco_gas_2_tank_volume_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_2_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_2_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_2_start_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_2_start_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_2_end_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_2_end_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_3_tank_type' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_3_tank_volume' => 'nullable|integer',
        'divingLogArray.extendedRangeData.deco_gas_3_tank_volume_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_3_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_3_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.extendedRangeData.deco_gas_3_start_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_3_start_pressure_unit' => 'nullable|string',
        'divingLogArray.extendedRangeData.deco_gas_3_end_pressure' => 'nullable|numeric',
        'divingLogArray.extendedRangeData.deco_gas_3_end_pressure_unit' => 'nullable|string',
        // DivingLogFreedivingData
        'divingLogArray.freedivingData.freedive_discipline' => 'nullable|string',
        'divingLogArray.freedivingData.warm_ups' => 'nullable|integer',
        'divingLogArray.freedivingData.max_time' => 'nullable|integer',
        'divingLogArray.freedivingData.contraction_time' => 'nullable|integer',
        'divingLogArray.freedivingData.time' => 'nullable|integer',
        'divingLogArray.freedivingData.max_distance' => 'nullable|integer',
        'divingLogArray.freedivingData.max_distance_unit' => 'nullable|string',
        'divingLogArray.freedivingData.max_depth' => 'nullable|integer',
        'divingLogArray.freedivingData.max_depth_unit' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_suit' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_mask' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_fins' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_dive_computer' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_other' => 'nullable|string',
        'divingLogArray.freedivingData.equipment_weight' => 'nullable|integer',
        'divingLogArray.freedivingData.equipment_weight_unit' => 'nullable|string',
        // DivingLogRebreatherCcrData
        'divingLogArray.rebreatherCCRData.runtime' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.ccr_total_deco_time' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.depth' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.depth_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_sac' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.deco_sac' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_tank_type' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_start_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_end_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_1_end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_tank_type' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_start_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_end_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_2_end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_tank_type' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_start_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_end_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.bailout_gas_3_end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.diluent_tank_type' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.diluent_tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.diluent_tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.diluent_oxygen_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.diluent_helium_percentage' => 'nullable|numeric|max:100',
        'divingLogArray.rebreatherCCRData.diluent_start_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.diluent_start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.diluent_end_pressure' => 'nullable|numeric',
        'divingLogArray.rebreatherCCRData.diluent_end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_suit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_mask' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_fins' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_bcd_wing_sidemount' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_rebreather_unit' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_dive_computer' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_lights' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_other' => 'nullable|string',
        'divingLogArray.rebreatherCCRData.equipment_weight' => 'nullable|integer',
        'divingLogArray.rebreatherCCRData.equipment_weight_unit' => 'nullable',
        // DivingLogRebreatherScrData
        'divingLogArray.rebreatherSCRData.runtime' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.scr_total_deco_time' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.depth' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.depth_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.weight' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.weight_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.bailout_sac' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.deco_sac' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.oxygen_percentage' => 'nullable|integer|max:100',
        'divingLogArray.rebreatherSCRData.setpoint' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.start_pressure' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.end_pressure' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.deco_tank_volume' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.deco_tank_volume_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.deco_oxygen_percentage' => 'nullable|integer|max:100',
        'divingLogArray.rebreatherSCRData.deco_setpoint' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.deco_start_pressure' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.deco_start_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.deco_end_pressure' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.deco_end_pressure_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.entry' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.water_type' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.current' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.surface' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_suit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_mask' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_fins' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_bcd_wing_sidemount' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_rebreather_unit' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_dive_computer' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_lights' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_other' => 'nullable|string',
        'divingLogArray.rebreatherSCRData.equipment_weight' => 'nullable|integer',
        'divingLogArray.rebreatherSCRData.equipment_weight_unit' => 'nullable|string',
    ];

    public function mount(): void
    {

        $this->divingLog = new DivingLogData(null, $this->individual->id);
        $this->divingLogArray = $this->divingLog->toArray();

        // Ensure diving_location_id is always set
        $this->divingLogArray['diving_location_id'] = $this->divingLogArray['diving_location_id'] ?? null;

        // Old data to be filled
        if (! empty($this->oldDivingLog)) {
            $this->divingLogArray = $this->oldDivingLog->toArray();
            // Ensure diving_location_id is set even when loading old data
            $this->divingLogArray['diving_location_id'] = $this->divingLogArray['diving_location_id'] ?? null;
        }

        if (! isset($this->divingLogArray['location'])) {
            $country = $this->individual->country;
            if ($country != null) {
                $this->location = (object) [
                    'lat' => $country->lat,
                    'lng' => $country->lng,
                    'zoom' => 6,
                    'country_id' => $country->id,
                ];
            }
        } else {
            $location = DivingLocation::find($this->divingLogArray['diving_location_id']);
            $this->location = (object) [
                'lat' => $location->lat,
                'lng' => $location->lng,
                'zoom' => 11,
                'country_id' => $location->country_id,
            ];
        }
        $this->fillDefaultSelections();
    }

    public function goToStep(int $step)
    {
        if ($step >= 3 && is_null($this->divingLogArray['dive_type'])) {
            session()->flash('validationErrors', ['Select a Dive Type before proceeding to this Step.']);

            return;
        }
        $this->formStep = $step;

        $this->dispatch('initializeMap');
    }

    public function updated($name, $value)
    {
        // info($name);
        // info($value);
    }

    public function getDiveTypes()
    {
        return $this->getEnumOptions(DivingLogDiveTypeEnum::class);
    }

    private function getEnumOptions(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(function ($case) {
                return [
                    $case->value => method_exists($case, 'toString') ? $case->toString() : $case->value,
                ];
            })
            ->toArray();
    }

    /**
     * Show the sequence number
     *
     * @return void
     */
    private function checkIfFirstDive()
    {
        if (! empty($this->divingLogArray['dive_type'])) {
            $sequence = DivingLogIndividualSequence::where('individual_id', $this->individual->id)
                ->where('dive_type', $this->divingLogArray['dive_type'])
                ->orderBy('log_number', 'desc')
                ->first();

            $this->isFirstDive = ! $sequence;

            if ($this->isFirstDive && isset($this->divingLogArray['dive_sequence_number'])) {
                $this->divingLogArray['dive_sequence_number'] = (int) $this->divingLogArray['dive_sequence_number'];
            } else {
                // Get the next sequence number from the latest sequence
                $this->divingLogArray['dive_sequence_number'] = $sequence ? $sequence->log_number + 1 : 1;
            }
        } else {
            $this->isFirstDive = false;
            $this->divingLogArray['dive_sequence_number'] = 1;
        }
    }

    private function manageDiveSequence(): void
    {
        // If it's the first dive and we have a custom sequence number
        if ($this->isFirstDive && isset($this->divingLogArray['dive_sequence_number'])) {
            $sequence = DivingLogIndividualSequence::create([
                'individual_id' => $this->individual->id,
                'dive_type' => $this->divingLogArray['dive_type'],
                'log_number' => $this->divingLogArray['dive_sequence_number'],
                'initial_log_number' => $this->divingLogArray['dive_sequence_number'],
            ]);
        } else {
            // Get the latest sequence for this individual and dive type
            $latestSequence = DivingLogIndividualSequence::where([
                'individual_id' => $this->individual->id,
                'dive_type' => $this->divingLogArray['dive_type'],
            ])->orderBy('log_number', 'desc')->first();

            // Calculate the next sequence number
            $nextSequenceNumber = $latestSequence ? $latestSequence->log_number + 1 : 1;

            // Create new sequence entry
            $sequence = DivingLogIndividualSequence::create([
                'individual_id' => $this->individual->id,
                'dive_type' => $this->divingLogArray['dive_type'],
                'log_number' => $nextSequenceNumber,
                'initial_log_number' => $latestSequence?->initial_log_number,
            ]);
        }

        $this->divingLogArray['dive_sequence_number'] = $sequence->log_number;
    }

    /**
     * Save the diving log as a draft on the first step
     */
    public function saveAsDraft(): void
    {

        $validated = $this->validate();

        $saveDivingLogAction = new SaveDivingLogAction;
        $record = $saveDivingLogAction($this->divingLogArray);

        $this->divingLogArray = $record->toArray();
        $this->existing_record_id = $record->id;
    }

    public function updatedDivingLogArrayDiveType($value): void
    {
        if ($value) {
            $this->checkIfFirstDive();
        } else {
            $this->isFirstDive = false;
        }
    }
    public function saveAsComplete()
    {
        $validated = $this->validate();
        if ($this->isFirstDive) {
            $this->validate(['divingLogArray.dive_sequence_number' => 'required|numeric']);
        }

        $saveDivingLogAction = new SaveDivingLogAction;
        if (! empty($this->oldDivingLog)) {
            $id = $this->oldDivingLog->id;
        } else {
            $id = null;
            $this->divingLogArray['status_class'] = PendingDivingLogState::class;
        }
        $saveDivingLogAction($this->divingLogArray, $id);

        return redirect()->route('individual.diving-log.index');
    }

    /**
     * Enums for Current Types
     */
    public function getEnvironmentCurrentOptions(): array
    {
        return $this->getEnumOptions(DivingLogCurrentEnum::class);
    }

    /**
     * Enums for Water Types
     */
    public function getEnvironmentWaterTypeOptions(): array
    {
        return $this->getEnumOptions(DivingLogWaterTypeEnum::class);
    }

    /**
     * Enums for Tank Types
     */
    public function getTankTypeOptions(): array
    {
        return $this->getEnumOptions(DivingLogTankTypeEnum::class);
    }

    /**
     * Enum for Envionment Entry
     */
    public function getEnvironmentEntryOptions(): array
    {
        return $this->getEnumOptions(DivingLogEntryEnum::class);
    }

    /**
     * For ENUMS, get the values and labels for the form
     */
    public function getEnvironmentSurfaceOptions(): array
    {
        return $this->getEnumOptions(DivingLogSurfaceEnum::class);
    }

    /**
     * Enums for Units
     */
    public function getTemperatureUnitOptions(): array
    {
        return $this->getEnumOptions(UnitTemperatureEnum::class);
    }

    /**
     * Enums for Pressure Units
     */
    public function getPressureUnitOptions(): array
    {
        return $this->getEnumOptions(UnitPressureEnum::class);
    }

    /**
     * Enums for Distance Units
     */
    public function getDistanceUnitOptions(): array
    {
        return $this->getEnumOptions(UnitDistanceEnum::class);
    }

    /**
     * Enums for Weight Units
     */
    public function getWeightUnitOptions(): array
    {
        return $this->getEnumOptions(UnitWeightEnum::class);
    }

    /**
     * Enums for Volume Units
     */
    public function getVolumeUnitOptions(): array
    {
        return $this->getEnumOptions(UnitVolumeEnum::class);
    }

    /**
     * Enums for Speciality Dive
     */
    public function getSpecialityDiveOptions(): array
    {
        return $this->getEnumOptions(DivingLogSpecialityDiveEnum::class);
    }

    /**
     * Enums for Dive Categories
     */
    public function getDiveCategoryOptions(): array
    {
        return DivingLogCategoryEnum::toArray();
    }

    /**
     * Enum for Freediving Disciplines
     */
    public function getFreedivingDisciplineOptions(): array
    {
        return $this->getEnumOptions(DivingLogFreediveDisciplineEnum::class);
    }

    /**
     * Enum for Freediving Static
     */
    public function getFreedivingStaticOptions(): array
    {
        return $this->getEnumOptions(DivingLogFreediveStaticEnum::class);
    }

    /**
     * Enum for Freediving Distance Disciplines
     */
    public function getFreedivingDistanceDisciplineOptions(): array
    {
        return $this->getEnumOptions(DivingLogFreediveDistanceDisciplineEnum::class);
    }

    /**
     * SetLocation
     */
    #[On('setLocation')]
    public function setLocation($id): void
    {
        $this->divingLogArray['diving_location_id'] = $id;
    }

    private function fillDefaultSelections(): void
    {
        $this->setDefaultDivingLogProperties();
        $this->setDefaultDivingDataProperties();
        $this->setDefaultFreedivingDataProperties();
        $this->setDefaultExtendedRangeDataProperties();
        $this->setDefaultRebreatherCCRDataProperties();
        $this->setDefaultRebreatherSCRDataProperties();
    }

    private function setDefaultDivingLogProperties()
    {
        $this->divingLogArray['environment_water_temperature_unit'] = array_key_first($this->getTemperatureUnitOptions());
        $this->divingLogArray['environment_air_temperature_unit'] = array_key_first($this->getTemperatureUnitOptions());
        $this->divingLogArray['environment_water_visibility_unit'] = array_key_first($this->getDistanceUnitOptions());
    }

    private function setDefaultDivingDataProperties()
    {
        $defaults = [
            'depth_unit' => $this->getDistanceUnitOptions(),
            'tank_type' => $this->getTankTypeOptions(),
            'tank_volume_unit' => $this->getVolumeUnitOptions(),
            'start_pressure_unit' => $this->getPressureUnitOptions(),
            'end_pressure_unit' => $this->getPressureUnitOptions(),
            'average_depth_unit' => $this->getDistanceUnitOptions(),
            'equipment_weight_unit' => $this->getWeightUnitOptions(),
        ];

        foreach ($defaults as $key => $method) {
            $this->divingLogArray['divingData'][$key] = array_key_first(
                is_callable($method) ? $method() : $method
            );
        }
    }

    private function setDefaultFreedivingDataProperties()
    {
        $defaults = [
            'freedive_discipline' => $this->getFreedivingDisciplineOptions(),
            'static_details' => $this->getFreedivingStaticOptions(),
            'distance_disciplines_details' => $this->getFreedivingDistanceDisciplineOptions(),
            'max_distance_unit' => $this->getDistanceUnitOptions(),
            'equipment_weight_unit' => $this->getWeightUnitOptions(),
        ];

        foreach ($defaults as $key => $method) {
            $this->divingLogArray['freedivingData'][$key] = array_key_first(
                is_callable($method) ? $method() : $method
            );
        }
    }

    private function setDefaultExtendedRangeDataProperties()
    {
        $defaults = [
            'depth_unit' => $this->getDistanceUnitOptions(),
        ];
        foreach (range(1, 4) as $i) {
            $prefix = $i === 1 ? 'back_gas' : 'deco_gas_' . ($i - 1);
            $defaults += [
                $prefix . '_tank_volume_unit' => $this->getVolumeUnitOptions(),
                $prefix . '_start_pressure_unit' => $this->getPressureUnitOptions(),
                $prefix . '_end_pressure_unit' => $this->getPressureUnitOptions(),
                $prefix . '_tank_type' => $this->getTankTypeOptions(),
            ];
        }

        foreach ($defaults as $key => $method) {
            $this->divingLogArray['extendedRangeData'][$key] = array_key_first(
                is_callable($method) ? $method() : $method
            );
        }
    }

    private function setDefaultRebreatherCCRDataProperties()
    {
        $defaults = [
            'depth_unit' => $this->getDistanceUnitOptions(),
            'equipment_weight_unit' => $this->getWeightUnitOptions(),
        ];

        foreach (range(1, 4) as $i) {
            $prefix = $i === 4 ? 'diluent' : 'bailout_gas_' . $i;
            $defaults += [
                $prefix . '_tank_volume_unit' => $this->getVolumeUnitOptions(),
                $prefix . '_start_pressure_unit' => $this->getPressureUnitOptions(),
                $prefix . '_end_pressure_unit' => $this->getPressureUnitOptions(),
                $prefix . '_tank_type' => $this->getTankTypeOptions(),
            ];
        }

        foreach ($defaults as $key => $method) {
            $this->divingLogArray['rebreatherCCRData'][$key] = array_key_first(
                is_callable($method) ? $method() : $method
            );
        }
    }

    private function setDefaultRebreatherSCRDataProperties()
    {
        $defaults = [
            'weight_unit' => $this->getWeightUnitOptions(),
            'tank_volume_unit' => $this->getVolumeUnitOptions(),
            'start_pressure_unit' => $this->getPressureUnitOptions(),
            'end_pressure_unit' => $this->getPressureUnitOptions(),
            'deco_tank_volume_unit' => $this->getVolumeUnitOptions(),
            'deco_start_pressure_unit' => $this->getPressureUnitOptions(),
            'deco_end_pressure_unit' => $this->getPressureUnitOptions(),
            'equipment_weight_unit' => $this->getWeightUnitOptions(),
        ];

        foreach ($defaults as $key => $method) {
            $this->divingLogArray['rebreatherSCRData'][$key] = array_key_first(
                is_callable($method) ? $method() : $method
            );
        }
    }

    public function updateFreediveDiscipline() {}

    public function render(): View
    {
        return view('livewire.diving-log.diving-log-form');
    }
}
