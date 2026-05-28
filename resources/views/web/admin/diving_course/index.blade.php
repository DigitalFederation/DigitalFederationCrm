<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Diving Courses') }}</h1>
            <a class="btn btn-primary" href="{{ route('admin.diving-course.create') }}">
                <span>{{ __('Add Diving Course') }}</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 font-medium text-sm text-red-600">{{ session('error') }}</div>
        @endif

        <div class="sm:flex sm:justify-center sm:items-center mb-5">
            <x-dynamic-table :headers="['Entity', 'Certification', 'Start Date', 'Actions']">
                @foreach($divingCourses as $course)
                    <tr>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">{{ $course->entity->name ?? '-' }}</td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">{{ $course->certification->name ?? '-' }}</td>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">{{ $course->start_date ? $course->start_date->format('Y-m-d') : '-' }}</td>
                        <td class="px-2 first:pl-5 last:pr-5 w-px">
                            <div class="gap-x-2 flex justify-end">
                                <x-dynamic-table-buttons type="edit" :route="route('admin.diving-course.edit', $course->id)" />
                                <x-dynamic-table-buttons type="delete" :route="route('admin.diving-course.destroy', $course->id)" method="DELETE" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-dynamic-table>
        </div>

        <div class="mt-8">
            {{ $divingCourses->links() }}
        </div>
    </div>
</x-layout>
