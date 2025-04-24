<x-app-layout>
    <x-slot name="header">
        <div class="block md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Assign Players to Game: ' . $game->name) }} - {{ $game->category }}
            </h2>
            <div class="">
                <a href="{{ route('game-team-players.index') }}"
                    class=" text-green-600 py-2 px-2 flex items-center justify-center rounded text-sm">Players</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('game-team-players.store', $game->id) }}">
                @csrf

                <div class="space-y-8">
                    @foreach ($teams as $team)
                        <div class="p-6 bg-gray-800 text-white rounded-lg shadow">
                            <h3 class="text-lg font-bold mb-2">{{ $team->name }}</h3>

                            @if ($game->type === 'group')
                                @for ($i = 1; $i <= $game->number_of_teams; $i++)
                                    <div class="mb-4">
                                        <label class="block text-gray-300">Sub Team {{ $i }} Name</label>
                                        <input type="text"
                                            name="players_data[{{ $team->id }}][{{ $i }}][sub_team_name]"
                                            class="w-full bg-gray-700 text-white rounded">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-300">Players for Group {{ $i }} (Max
                                            {{ $game->members_per_team }})</label>
                                        <select
                                            name="players_data[{{ $team->id }}][{{ $i }}][players][]"
                                            multiple class="select2 w-full bg-gray-700 text-white rounded"
                                            data-max="{{ $game->members_per_team }}" data-placeholder="Add Players">
                                            <option></option>
                                        </select>
                                        <input type="hidden"
                                            name="players_data[{{ $team->id }}][{{ $i }}][group_number]"
                                            value="{{ $i }}">
                                        <input type="hidden"
                                            name="players_data[{{ $team->id }}][{{ $i }}][team_id]"
                                            value="{{ $team->id }}">
                                    </div>
                                @endfor
                            @else
                                <div class="mb-4">
                                    <label class="block text-gray-300">Players (Max
                                        {{ $game->max_participants_per_team }})</label>
                                    <select name="players_data[{{ $team->id }}][0][players][]" multiple
                                        class="select2 w-full bg-gray-700 text-white rounded"
                                        data-max="{{ $game->max_participants_per_team }}" data-placeholder="Add Players">
                                        <option></option>
                                    </select>
                                    <input type="hidden" name="players_data[{{ $team->id }}][0][team_id]"
                                        value="{{ $team->id }}">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Assign
                    Players</button>
            </form>
        </div>
    </div>

</x-app-layout>
