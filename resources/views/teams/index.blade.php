<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Team Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between">
                <h3 class="text-lg text-white font-bold mb-4">Manage Teams</h3>
                <a href="{{ route('teams.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Team</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-auto mt-4 border">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="p-2">Logo</th>
                        <th class="p-2">Team Name</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr class="border-b text-center">
                            <td class="p-2">
                                @if($team->logo)
                                    <img src="{{ asset($team->logo) }}" class="w-16 h-16 d-block mx-auto">
                                @endif
                            </td>
                            <td class="p-2 text-white">{{ $team->name }}</td>
                            <td class="p-2">
                                <!-- Edit Button -->
                                <a href="{{ route('teams.edit', $team->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-400">Edit</a>

                                <!-- Delete Button -->
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            
        </div>
    </div>
</x-app-layout>
