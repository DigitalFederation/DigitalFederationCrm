<x-layout>
    <x-common.diving_location.index
        :divingLocations="$divingLocations"
        :districts="$districts ?? collect()"
        :searchName="$searchName ?? ''"
        :districtId="$districtId ?? ''"
        group="federation"
    />
</x-layout>
