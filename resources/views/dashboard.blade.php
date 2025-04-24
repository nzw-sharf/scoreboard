<x-app-layout>
    <x-slot name="header">
        <div class="block md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white dark:text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                <a href="{{ route('teams.index') }}" class="bg-blue-500 text-white py-2 px-2 flex items-center justify-center rounded text-sm">Teams</a>
                <a href="{{ route('games.index') }}" class="bg-green-500 text-white py-2 px-2 flex items-center justify-center rounded text-sm">Games</a>
                <a href="{{ route('game-team-players.index') }}" class="bg-yellow-500 text-white py-2 px-2 flex items-center justify-center rounded text-sm">Players</a>
                <a href="{{ route('scoreboards.index') }}" class="bg-red-500 text-white py-2 px-2 flex items-center justify-center rounded text-sm">Scoreboard</a>
                <a href="{{ route('scoreboards.summary') }}" class="bg-blue-500 text-white py-2 px-2 flex items-center justify-center rounded text-sm">Score Summary</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

