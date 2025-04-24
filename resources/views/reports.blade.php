@extends('layouts.master')
@section('content')
           <div class="overflow-x-auto">
                <table class="w-full table-auto mt-4 border text-white text-start">
                <thead>
                    <tr class="bg-gray-700 text-white">
                         <th class="p-2">Game</th>
                        <th class="p-2">Category</th>
                        <th class="p-2">Winner Team</th>
                       
                        <th class="p-2">Position</th>
                        <th class="p-2">Points</th>
                        <th class="p-2">Winner(s)</th>
                        <th class="p-2">Is it Tie?</th>
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
                            </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
@endsection

    
