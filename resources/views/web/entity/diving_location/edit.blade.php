<x-layout>
    <x-common.diving_location.form
        :countries="$countries"
        :districts="$districts"
        :divingLocation="$divingLocation"
        formMethod="PUT"
        :formAction="route('entity.diving-location.update', $divingLocation->id)"
    />
</x-layout>
