<x-layout>
    {{--
        This view acts as a wrapper.
        It receives the $entity from the controller
        and passes it to the Livewire component.
    --}}
    <livewire:entity.diving-course.manage-diving-courses :entity="$entity" />
</x-layout>
