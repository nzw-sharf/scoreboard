<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">All Players</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-white text-lg font-bold">Player List</h3>
                    
                </div>
                <div class="overflow-x-auto">
                <table id="dataTable" class="w-full table-auto divide-y divide-gray-700 dark:divide-gray-700">
                    <thead class="bg-gray-700 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white dark:text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white dark:text-gray-300 uppercase tracking-wider">Game Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-white dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bggray-800 dark:bg-gray-800 divide-y divide-gray-700 dark:divide-gray-700">
                        @foreach($games as $index => $game)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white dark:text-gray-100">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white dark:text-gray-100">
                                    {{ $game->name }} <span class="text-gray-500">({{ $game->category }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white dark:text-gray-300">{{ ucfirst($game->type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    @if ($game->has_players)
                                        <a href="{{ route('game-team-players.show', $game->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-500">View Players</a>
                                    @else
                                        <a href="{{ route('game-team-players.create', $game->id) }}" class="inline-block px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-500">Add Players</a>
                                    @endif

                                    @if ($game->has_players)
                                        <form action="{{ route('game-team-players.destroy', $game->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete all players for this game?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-500">Delete Players</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
                @if($games->isEmpty())
                    <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No games found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
