<x-layout>
    <x-common.diving_location.form :countries="$countries" :divingLocation="$divingLocation" formMethod="POST" :formAction="route('admin.diving-location.store')" />
</x-layout>
