<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           <a href="{{ route('teams.index') }}">{{ __('Team Management') }}</a> 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between">
                <h3 class="text-lg text-white font-bold mb-4">Edit Team</h3>
            </div>
            <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg shadow-lg">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-white text-lg">Team Name:</label>
                    <input type="text" name="name" value="{{ old('name', $team->name) }}" required class="w-full p-3 mt-2 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-white text-lg">Team Logo:</label>
                    <input type="file" name="logo" class="w-full p-3 mt-2 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                @if ($team->logo)
                    <div class="mb-4">
                        <p class="text-white">Current Logo:</p>
                        <img src="{{ asset( $team->logo) }}" alt="Team Logo" class="w-32 h-32 object-cover mt-2">
                    </div>
                @endif

                <button type="submit" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg mt-4 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update
                </button>
            </form>
            
        </div>
    </div>
</x-app-layout>
