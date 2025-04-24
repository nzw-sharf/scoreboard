<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('scoreboards.index') }}" class="hover:underline">{{ __('Scoreboard') }}</a> &gt;
            <span class="text-gray-600">Edit Score</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg text-white font-bold mb-4">Update Score</h3>

                <form action="{{ route('scoreboards.update', $scoreboard->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Select Game -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Select Game:</label>
                        <input type="text" name="" id="" readonly value="{{ $scoreboard->game->name ?? 'N/A' }}" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <input type="hidden" name="game_id" id="game_id" value="{{ $scoreboard->game_id }}">
                        
                    </div>
                    <!-- Select Team -->
                    <div class="mb-4">¸
                        <label class="block text-white text-lg mb-2">Select Team:</label>
                        <select name="team_id" id="team_id" required class=" w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Select a Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ $team->id == $scoreboard->team_id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Select Position -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Select Position:</label>
                        <select name="position" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="1st" {{ $scoreboard->position == '1st' ? 'selected' : '' }}>1st Place</option>
                            <option value="2nd" {{ $scoreboard->position == '2nd' ? 'selected' : '' }}>2nd Place</option>
                        </select>
                    </div>
                    <!-- Select player -->
                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Winner:</label>
                        <input type="text" name="player" id="player" value="{{$scoreboard->winner_name}}" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                       
                    </div>

                    <button type="submit" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg mt-4 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update
                    </button>
                </form>
            </div>
        </div>
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
$(document).ready(function() {
  fetchPlayers(
        {{ $scoreboard->team_id }},
        'player',
        "{{ !$scoreboard->winner_team ? $scoreboard->winner_name : $scoreboard->winner_team . ' - ' . $scoreboard->winner_name }}"
    );

});
</script>
</x-app-layout>
