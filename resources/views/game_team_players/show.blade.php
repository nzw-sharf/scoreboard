<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Players for Game: {{ $game->name }} ({{ ucfirst($game->category) }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded shadow overflow-x-auto">
                <table class="table-auto border-collapse border w-full text-sm text-gray-900 dark:text-white">
                    <thead>
                        <!-- Row 1: Team headers with colspan -->
                        <tr>
                            @foreach ($teams as $team)
                                @php
                                    $subTeams = $playerAssignments[$team->id] ?? [];
                                    $colspan = ($game->type === 'group') ? count($subTeams) : 1;
                                @endphp
                                <th colspan="{{ max(1, $colspan) }}" class="text-center border px-4 py-2 bg-gray-100 dark:bg-gray-700 font-bold">
                                    {{ $team->name }}
                                </th>
                            @endforeach
                        </tr>

                        <!-- Row 2: Sub-team headers (or "Players" for individual) -->
                        <tr>
                            @foreach ($teams as $team)
                                @php
                                    $subTeams = $playerAssignments[$team->id] ?? [];
                                @endphp

                                @if ($game->type === 'group')
                                    @foreach ($subTeams as $entry)
                                        <th class="text-center border px-4 py-2 bg-gray-50 dark:bg-gray-600 font-semibold">
                                            {{ $entry->sub_team_name }}
                                        </th>
                                    @endforeach
                                @else
                                    <th class="text-center border px-4 py-2 bg-gray-50 dark:bg-gray-600 font-semibold">
                                        Players
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            // Prepare vertical player rows
                            $maxRows = 0;
                            $columns = [];

                            foreach ($teams as $team) {
                                $entries = $playerAssignments[$team->id] ?? [];

                                if ($game->type === 'group') {
                                    foreach ($entries as $entry) {
                                        $players = json_decode($entry->players, true) ?? [];
                                        $columns[] = $players;
                                        $maxRows = max($maxRows, count($players));
                                    }
                                } else {
                                    $players = [];
                                    foreach ($entries as $entry) {
                                        $players = array_merge($players, json_decode($entry->players, true) ?? []);
                                    }
                                    $columns[] = $players;
                                    $maxRows = max($maxRows, count($players));
                                }
                            }
                        @endphp

                        @for ($i = 0; $i < $maxRows; $i++)
                            <tr>
                                @foreach ($columns as $col)
                                    <td class="border px-4 py-2 text-center">
                                        {{ $col[$i] ?? '' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endfor

                        <!-- Winners Row -->
                        <tr class="bg-yellow-100 dark:bg-yellow-900">
                            @php
                                $scoreboardMap = [];

                                foreach ($scoreboards as $scoreboard) {
                                    $key = $scoreboard->team_id . '-' . ($scoreboard->winner_team ?? 'individual');
                                    $scoreboardMap[$key][] = $scoreboard;
                                }
                            @endphp

                            @foreach ($teams as $team)
                                @php
                                    $entries = $playerAssignments[$team->id] ?? [];
                                @endphp

                                @if ($game->type === 'group')
                                    @foreach ($entries as $entry)
                                        @php
                                            $key = $team->id . '-' . $entry->sub_team_name;
                                            $matching = $scoreboardMap[$key] ?? [];
                                            $winnerText = '';

                                            foreach ($matching as $s) {
                                                $winnerText .= "{$s->position}";
                                                $winnerText .= $s->is_tie_or_not ? " (Tie)" : "";
                                                $winnerText .= "<br>";
                                            }
                                        @endphp
                                        <td class="border px-4 py-2 text-center text-xs text-gray-800 dark:text-gray-200 bg-yellow-100 dark:bg-yellow-800">
                                            {!! $winnerText ?: '—' !!}
                                        </td>
                                    @endforeach
                                @else
                                    @php
                                        $key = $team->id . '-individual';
                                        $matching = $scoreboardMap[$key] ?? [];
                                        $winnerText = '';

                                        foreach ($matching as $s) {
                                            $winnerText .= "{$s->winner_name} ({$s->position}";
                                            $winnerText .= $s->is_tie_or_not ? " - Tie" : "";
                                            $winnerText .= ")<br>";
                                        }
                                    @endphp
                                    <td class="border px-4 py-2 text-center text-xs text-gray-800 dark:text-gray-200 bg-yellow-100 dark:bg-yellow-800">
                                        {!! $winnerText ?: '—' !!}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
