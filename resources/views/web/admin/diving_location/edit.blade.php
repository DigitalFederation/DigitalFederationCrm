<x-layout>
    <x-common.diving_location.form :countries="$countries" :divingLocation="$divingLocation" formMethod="PUT" :formAction="route('admin.diving-location.update', $divingLocation->id)"/>
</x-layout>
