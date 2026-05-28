<x-layout>
    <x-common.diving_location.form :countries="$countries" :divingLocation="$divingLocation" formMethod="POST" :formAction="route('individual.diving-location.store')" :existingLocations="$existingLocations" :publicLocations="$publicLocations"/>
</x-layout>
