@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-2">
    <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
    <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
</div>
