<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('scoreboards.index') }}" class="hover:underline">{{ __('Scoreboard') }}</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg text-white font-bold mb-4">Update Score</h3>

                <form action="{{ route('scoreboards.store') }}" method="POST">
                    @csrf

                    <!-- Select Team -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Select Team:</label>
                        <select name="team_id" multiple required class="selectNew w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Select a Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Game -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Select Game:</label>
                        <select id="gameSelect" name="game_id" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Select a Game</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" data-max-participants="{{ $game->max_participants_per_team }}">{{ $game->name }} - {{$game->category}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Position -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Select Position:</label>
                        <select name="position" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="1st">1st Place</option>
                            <option value="2nd">2nd Place</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg mt-4 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
