<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scoreboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between">
                <h3 class="text-lg text-white font-bold mb-4">Complete Scoreboard</h3>
                {{-- <a href="{{ route('scoreboards.create') }}" class="bg-red-500 text-white px-4 py-2 rounded">Update Scoreboard</a> --}}
            </div>
            <div class="overflow-x-auto">
                <table id="dataTable2" class="w-full table-auto mt-4 border text-white text-start">
                <thead>
                    <tr class="bg-gray-700 text-white">
                         <th class="p-2">Game</th>
                        <th class="p-2">Category</th>
                        <th class="p-2">Winner Team</th>
                       
                        <th class="p-2">Position</th>
                        <th class="p-2">Points</th>
                        <th class="p-2">Winner(s)</th>
                        <th class="p-2">Is it Tie?</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scoreboards as $scoreboard)
                        {{-- Loop through winners data (which is a JSON array) --}}
                        <tr class="border-b">
                                <!-- Accessing winner's team name -->
                                <td class="p-2">{{ $scoreboard->game->name ?? 'N/A' }}</td>
                                 <td class="p-2">{{ $scoreboard->game->category ?? 'N/A' }}</td>
                                <td class="p-2">{{ $scoreboard->team->name ?? 'N/A' }}</td>
                                <!-- Accessing position from the winner array -->
                                <td class="p-2">{{ $scoreboard->position }}</td>
                                <!-- Accessing the total points from the scoreboard model -->
                                <td class="p-2">{{ $scoreboard->points }}</td>
                                <td class="p-2">{{ $scoreboard->winner_name }}</td>
                                <td class="p-2">{{ $scoreboard->is_tie_or_not ? 'Yes' : 'No' }}</td>
                                <!-- Accessing participants from the scoreboard model (if available) -->
                                <td class="p-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('scoreboards.edit', $scoreboard->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('scoreboards.destroy', $scoreboard->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
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
