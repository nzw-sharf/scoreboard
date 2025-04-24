<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Games') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-white text-lg font-bold">Games List</h3>
                    <a href="{{ route('games.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500">
                        Add Game
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table id="dataTable" class="w-full table-auto text-white border border-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="p-3 border border-gray-600">Name</th>
                                <th class="p-3 border border-gray-600">Type</th>
                                <th class="p-3 border border-gray-600">Category</th>
                                <th class="p-3 border border-gray-600">Date</th>
                                <th class="p-3 border border-gray-600">Participants / Teams</th>
                                <th class="p-3 border border-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($games as $game)
                                <tr class="bg-gray-900 border-b border-gray-700">
                                    <td class="p-3">{{ $game->name }}</td>
                                    <td class="p-3">{{ ucfirst($game->type) }}</td>
                                    <td class="p-3">{{ $game->category }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($game->game_date)->format('d M Y') }}
                                    </td>
                                    <td class="p-3">
                                        @if ($game->type === 'individual')
                                            {{ $game->max_participants_per_team ?? '—' }} participants
                                        @else
                                            {{ $game->number_of_teams ?? '—' }} teams ×
                                            {{ $game->members_per_team ?? '—' }} members
                                        @endif
                                    </td>
                                    <td class="p-3 flex flex-col md:flex-row gap-2">
                                        @php
                                            $firstPlace = $scoreboard
                                                ->where('game_id', $game->id)
                                                ->where('position', '1st');
                                            $secondPlace = $scoreboard
                                                ->where('game_id', $game->id)
                                                ->where('position', '2nd');
                                            $firstPlaceData = array_values(
                                                $firstPlace
                                                    ->map(
                                                        fn($item) => [
                                                            'team_id' => $item->team_id,
                                                            'winner_name' => $item->winner_name,
                                                            'winner_team' => $item->winner_team,
                                                        ],
                                                    )
                                                    ->toArray(),
                                            );

                                            $secondPlaceData = array_values(
                                                $secondPlace
                                                    ->map(
                                                        fn($item2) => [
                                                            'team_id' => $item2->team_id,
                                                            'winner_name' => $item2->winner_name,
                                                            'winner_team' => $item2->winner_team,
                                                        ],
                                                    )
                                                    ->toArray(),
                                            );
                                        @endphp

                                        <button
                                            onclick='openWinnersModal(
                                            {{ $game->id }},
                                            @json($game->name),
                                            @json($game->type),
                                            @json($firstPlaceData),
                                            @json($secondPlaceData))'
                                            class="text-green-500 bg-white px-3 py-1 rounded text-sm hover:underline">
                                            {{ $firstPlace->isNotEmpty() || $secondPlace->isNotEmpty() ? 'Edit Winners' : 'Add Winners' }}
                                        </button>



                                        @php
                                            $hasPlayers = \App\Models\GameTeamPlayer::where(
                                                'game_id',
                                                $game->id,
                                            )->exists();
                                        @endphp

                                        <a href="{{ route($hasPlayers ? 'game-team-players.edit' : 'game-team-players.create', $game->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-500 text-sm">
                                            {{ $hasPlayers ? 'Edit Players' : 'Assign Players' }}
                                        </a>

                                        <a href="{{ route('games.edit', $game->id) }}"
                                            class="text-yellow-500 hover:underline">Edit</a>

                                        <form action="{{ route('games.destroy', $game->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($games->isEmpty())
                    <p class="text-center text-gray-400 mt-4">No games found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Winners Modal -->
<!-- Winners Modal -->
<div id="winners-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <form id="winners-form" method="POST" action="{{ route('scoreboards.storeWinners') }}"
          class="bg-gray-900 text-white p-6 rounded-lg w-full max-w-3xl relative"
          x-data="{ 
                gameType: 'individual', 
                tie1: false, 
                tie2: false, 
                resetFirstTeam: () => { 
                    document.getElementById('first_team_2').value = ''; 
                    document.getElementById('first_player_2').value = ''; 
                },
                resetSecondTeam: () => { 
                    document.getElementById('second_team_2').value = ''; 
                    document.getElementById('second_player_2').value = ''; 
                }
            }">
        @csrf

        <h2 class="text-xl font-bold mb-4">Add Winners for <span id="game_name"></span></h2>
        <input type="hidden" id="game_id" name="game_id">

        <!-- 1st Place -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">1st Place</h3>
            <div class="md:flex gap-4">
                <div class="w-full">
                    <label class="block mb-1">Team</label>
                    <select id="first_team_1" name="first_team_1"
                            class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                        <option value="">Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block mb-1">Winner</label>
                    <input type="text" name="first_player_1" id="first_player_1" class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                </div>
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" x-model="tie1" class="mr-2"
                           @change="!tie1 ? resetFirstTeam() : null">
                    It's a tie? Add another team
                </label>
            </div>

            <div class="md:flex gap-4 mt-4" x-show="tie1" x-transition>
                <div class="w-full">
                    <label class="block mb-1">Second Team</label>
                    <select id="first_team_2" name="first_team_2"
                            class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                        <option value="">Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block mb-1">Winner</label>
                    <input type="text" name="first_player_2" id="first_player_2" class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                </div>
            </div>
        </div>

        <!-- 2nd Place -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">2nd Place</h3>
            <div class="md:flex gap-4">
                <div class="w-full">
                    <label class="block mb-1">Team</label>
                    <select id="second_team_1" name="second_team_1"
                            class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded"
                            onchange="fetchPlayers(this.value, 'second_player_1')">
                        <option value="">Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block mb-1">Winner</label>
                    <input type="text" name="second_player_1" id="second_player_1" class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                </div>
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" x-model="tie2" class="mr-2"
                           @change="!tie2 ? resetSecondTeam() : null">
                    It's a tie? Add another team
                </label>
            </div>

            <div class="md:flex gap-4 mt-4" x-show="tie2" x-transition>
                <div class="w-full">
                    <label class="block mb-1">Second Team</label>
                    <select id="second_team_2" name="second_team_2"
                            class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                        <option value="">Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block mb-1">Winner</label>
                    <input type="text" name="second_player_2" id="second_player_2" class="w-full border border-gray-700 bg-gray-800 text-white px-3 py-2 rounded">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end mt-6">
            <button type="button" onclick="closeWinnersModal()"
                    class="px-4 py-2 mr-2 bg-gray-600 text-white rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Winners</button>
        </div>
    </form>
</div>



    <script>
        function fetchPlayers(teamId, playerSelectId, winnerName = '') {
            
    const gameId = document.getElementById('game_id').value;
    if (!teamId || !gameId) return;

    const $select = $('#' + playerSelectId);
    $select.empty().append(new Option('Loading...', ''));

    $.ajax({
        url: `/fetch-players/${gameId}/${teamId}`,
        method: 'GET',
        success: function(response) {
            $select.empty();
            $select.append(new Option('Select Player', '')); // default option

            if (response.players.length > 0) {
                response.players.forEach(player => {
                    const option = new Option(player.label, player.value);
                    
                    // Check if label matches winnerName
                    if (player.value === winnerName) {
                        option.selected = true;
                    }

                    $select.append(option);
                });
            } else {
                $select.append(new Option('No players found', ''));
            }
        },
        error: function() {
            $select.empty();
            $select.append(new Option('Select Player', '')); // still add the default
            $select.append(new Option('Error loading players', ''));
        }
    });
}



        function openWinnersModal(gameId, gameName, gameType, firstPlace, secondPlace) {


            // Ensure firstPlace and secondPlace are arrays
            firstPlace = Array.isArray(firstPlace) ? firstPlace : [];
            secondPlace = Array.isArray(secondPlace) ? secondPlace : [];

            // Set game details
            document.getElementById('game_id').value = gameId;
            document.getElementById('game_name').innerText = gameName;
            // document.getElementById('game_id_form').value = gameId;

            // Clear all previous data
            const fieldsToClear = [
                'first_team_1', 'first_player_1', 'first_team_2', 'first_player_2',
                'second_team_1', 'second_player_1', 'second_team_2', 'second_player_2'
            ];
            fieldsToClear.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.value = '';
            });

            // Reset tie checkboxes
            const tie1Checkbox = document.querySelector('[x-model="tie1"]');
            const tie2Checkbox = document.querySelector('[x-model="tie2"]');
            if (tie1Checkbox) tie1Checkbox.checked = false;
            if (tie2Checkbox) tie2Checkbox.checked = false;

            // Hide tie fields initially
            document.getElementById('first_team_2').parentElement.parentElement.style.display = 'none';
            document.getElementById('second_team_2').parentElement.parentElement.style.display = 'none';

            // Populate first place winners
            firstPlace.forEach((winner, index) => {
                if (index < 2) { // Ensure we don't exceed the available fields
                    document.getElementById(`first_team_${index + 1}`).value = winner.team_id;
                    document.getElementById(`first_player_${index + 1}`).value = winner.winner_name;
                    // fetchPlayers(winner.team_id, `first_player_${index + 1}`, ! winner.winner_team ? winner.winner_name : winner.winner_team + ' - ' + winner.winner_name);
                

                    // Show tie fields if there is a second winner
                    if (index === 1) {
                        document.getElementById('first_team_2').parentElement.parentElement.style.display = 'flex';
                        if (tie1Checkbox) tie1Checkbox.checked = true;
                    }
                }
            });

            // Populate second place winners
            secondPlace.forEach((winner, index) => {
                if (index < 2) { // Ensure we don't exceed the available fields
                    document.getElementById(`second_team_${index + 1}`).value = winner.team_id;
                    document.getElementById(`second_player_${index + 1}`).value = winner.winner_name;
                    
                    // fetchPlayers(winner.team_id, `second_player_${index + 1}`, !winner.winner_team ? winner.winner_name : winner.winner_team + ' - ' + winner.winner_name);
            
               
                    // Show tie fields if there is a second winner
                    if (index === 1) {
                        document.getElementById('second_team_2').parentElement.parentElement.style.display = 'flex';
                        if (tie2Checkbox) tie2Checkbox.checked = true;
                    }
                }
            });

            // Show the modal
            document.getElementById('winners-modal').classList.remove('hidden');
        }


        function closeWinnersModal() {
            document.getElementById('winners-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
