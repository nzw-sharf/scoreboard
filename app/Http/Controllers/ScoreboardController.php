<?php

namespace App\Http\Controllers;

use App\Models\Scoreboard;
use App\Models\Game;
use App\Models\GameTeamPlayer;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScoreboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('scoreboards.index', ['scoreboards' => Scoreboard::orderBy('game_id','Desc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('scoreboards.create', ['teams' => Team::all(), 'games' => Game::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'team_id' => 'Team is Required ',
            'game_id' => 'Game is Required ',
            'position' => 'Position No. is Required ',
        ];

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'game_id' => 'required|exists:games,id',
            'position' => 'required|in:1st,2nd,3rd',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()], 401);
        }

        try {
            // Fetch the game type from the database (assuming game type is stored in the `games` table)
            $game = Game::find($request->game_id);
            $gameType = $game->type; // Assuming `type` is a field in the `games` table that stores the type (e.g., 'individual' or 'group')

            // Set points based on game type and position
            if ($gameType == 'individual') {
                $points = $this->getIndividualPoints($request->position);
            } elseif ($gameType == 'group') {
                $points = $this->getGroupPoints($request->position);
            } else {
                $points = 0; // Default value if no match
            }

            // Create the scoreboard entry
            Scoreboard::create([
                'team_id' => $request->team_id,
                'game_id' => $request->game_id,
                'position' => $request->position,
                'points' => $points, // Set points based on the game type and position
                'participants' => json_encode($request->participants ?? []),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('scoreboards.create')->with('error', 'An error occurred while creating the scoreboard.');
        }

        return redirect()->route('scoreboards.index')->with('success', 'Scoreboard created.');
    }
    public function storeWinners(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $game = Game::findOrFail($request->game_id);
        $gameId = $game->id;
        $gameType = $game->type;

        $positions = [
            '1st' => [
                ['team' => 'first_team_1', 'player' => 'first_player_1'],
                ['team' => 'first_team_2', 'player' => 'first_player_2'],
            ],
            '2nd' => [
                ['team' => 'second_team_1', 'player' => 'second_player_1'],
                ['team' => 'second_team_2', 'player' => 'second_player_2'],
            ]
        ];

        foreach ($positions as $position => $entries) {
            $submittedTeamIds = [];

            // Collect valid team_ids
            foreach ($entries as $entry) {
                $teamId = $request->input($entry['team']);
                if ($teamId) {
                    $submittedTeamIds[] = $teamId;
                }
            }

            // Delete previous records not in submitted ones
            if (!empty($submittedTeamIds)) {
                \App\Models\Scoreboard::where('game_id', $gameId)
                    ->where('position', $position)
                    ->whereNotIn('team_id', $submittedTeamIds)
                    ->delete();
            }

            // Save/update valid entries
            foreach ($entries as $entry) {
                $teamId = $request->input($entry['team']);
                $playerInput = $request->input($entry['player']);

                if (!$teamId) continue;

                $winnerTeam = null;
                $winnerName = $playerInput;

                // if ($gameType === 'group') {
                //     // Split into sub-team and players
                //     $split = explode(' - ', $playerInput);
                //     $subTeamName = trim($split[0] ?? '');
                //     $playersRaw = trim($split[1] ?? '');

                //     // Convert "Nazwa, Rageeba" into ["Nazwa", "Rageeba"]
                //     $players = array_map('trim', explode(',', $playersRaw));

                //     // Match exact player set for that sub_team
                //     $groupEntry = \App\Models\GameTeamPlayer::where('game_id', $gameId)
                //         ->where('team_id', $teamId)
                //         ->where('sub_team_name', $subTeamName)
                //         ->get()->first(function ($entry) use ($players) {
                //             // Decode the stored JSON into an array
                //             $storedPlayers = json_decode($entry->players, true);  // Decode the players JSON string

                //             // Ensure the stored players match the input players
                //             return collect($storedPlayers)->sort()->values()->all() === collect($players)->sort()->values()->all();
                //         });
                //     if ($groupEntry) {
                //         $winnerTeam = $subTeamName;
                //         $winnerName = implode(', ', $players);
                //     } else {
                //         $winnerTeam = $subTeamName;
                //         $winnerName = implode(', ', $players); // fallback even if not matched
                //     }
                // } else {
                //     $winnerTeam = null;
                //     $winnerName = $playerInput;
                // }

                $points = $this->getGamePoints($gameId, $position);
                $isTie = count($submittedTeamIds) > 1;


                // First, check if a scoreboard entry exists
                $scoreboard = Scoreboard::where('game_id', $gameId)
                    ->where('team_id', $teamId)
                    ->where('position', $position)
                    ->first();

                if (!$scoreboard) {
                    // If no existing record, create a new one
                    $scoreboard = new Scoreboard;
                }

                try {
                    // Manually set each attribute
                    $scoreboard->game_id = $gameId;
                    $scoreboard->team_id = $teamId;
                    $scoreboard->position = $position;
                    $scoreboard->points = $points;
                    $scoreboard->winner_team = $winnerTeam;
                    $scoreboard->winner_name = $winnerName;
                    $scoreboard->is_tie_or_not = $isTie;

                    // Save the record
                    $scoreboard->save();

                    // return back()->with('success', 'Winners saved/updated successfully.');
                } catch (\Exception $e) {
                    Log::error('Error saving/updating scoreboard: ' . $e->getMessage());
                    // return back()->with('error', 'An error occurred while saving the scoreboard.');
                }
            }
        }

        return back()->with('success', 'Winners saved successfully.');
    }





    private function getGamePoints($gameId, $position)
    {
        $game = Game::find($gameId);
        $gameType = $game->type;

        if ($gameType == 'individual') {
            return $this->getIndividualPoints($position);
        } elseif ($gameType == 'group') {
            return $this->getGroupPoints($position);
        }

        return 0;
    }

    /**
     * Display the specified resource.
     */
    public function show(Scoreboard $scoreboard)
    {
        return view('scoreboards.show', ['scoreboard' => $scoreboard]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scoreboard $scoreboard)
    {
        return view('scoreboards.edit', ['scoreboard' => $scoreboard, 'teams' => Team::all(), 'games' => Game::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scoreboard $scoreboard)
    {
        $messages = [
            'team_id.required' => 'Team is required.',
            'game_id.required' => 'Game is required.',
            'position.required' => 'Position is required.',
            'player.required' => 'Players field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'game_id' => 'required|exists:games,id',
            'position' => 'required|in:1st,2nd,3rd',
            'player' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()], 401);
        }

        try {
            $game = Game::findOrFail($request->game_id);
            $gameType = $game->type;
            $playerInput = $request->input('player');

            $winnerTeam = null;
            $winnerName = $playerInput;

            // Calculate points
            $points = match ($gameType) {
                'individual' => $this->getIndividualPoints($request->position),
                'group' => $this->getGroupPoints($request->position),
                default => 0
            };

            // // Handle winner name/team
            // if ($gameType === 'group') {
            //     [$subTeamName, $playersRaw] = array_pad(explode(' - ', $playerInput, 2), 2, '');
            //     $players = array_map('trim', explode(',', $playersRaw));

            //     $groupEntry = GameTeamPlayer::where([
            //         ['game_id', $game->id],
            //         ['team_id', $request->team_id],
            //         ['sub_team_name', trim($subTeamName)],
            //     ])->get()->first(function ($entry) use ($players) {
            //         return collect(json_decode($entry->players, true))->sort()->values()->all()
            //             === collect($players)->sort()->values()->all();
            //     });

            //     $winnerTeam = trim($subTeamName);
            //     $winnerName = implode(', ', $players);
            // } else {
            //     $winnerName = $playerInput;
            // }

            // Update scoreboard fields
            $scoreboard->team_id = $request->team_id;
            $scoreboard->game_id = $request->game_id;
            $scoreboard->position = $request->position;
            $scoreboard->points = $points;
            $scoreboard->winner_team = $winnerTeam;
            $scoreboard->winner_name = $winnerName;
            $scoreboard->save();

            $positions = ['1st', '2nd'];
            foreach ($positions as $position) {
                $scoreboards = Scoreboard::where('game_id', $request->game_id)
                    ->where('position', $position)
                    ->get();

                $isTie = $scoreboards->count() > 1;

                foreach ($scoreboards as $entry) {
                    if ($entry->is_tie_or_not !== $isTie) {
                        $entry->is_tie_or_not = $isTie;
                        $entry->save();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Scoreboard Update Failed: ' . $e->getMessage());
            return redirect()->route('scoreboards.edit', $scoreboard)->with('error', 'Something went wrong.');
        }

        return redirect()->route('scoreboards.index')->with('success', 'Scoreboard updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scoreboard $scoreboard)
    {
        $gameId = $scoreboard->game_id;
        $position = $scoreboard->position;

        // Delete the scoreboard
        $scoreboard->delete();

        // Check if more entries exist for the same game and position (tie)
        $remainingScoreboards = Scoreboard::where('game_id', $gameId)
            ->where('position', $position)
            ->get();

        if ($remainingScoreboards->count() === 1) {
            // Only one left, set its is_tie_or_not to false
            $remaining = $remainingScoreboards->first();
            if ($remaining->is_tie_or_not) {
                $remaining->is_tie_or_not = false;
                $remaining->save();
            }
        }

        return redirect()->route('scoreboards.index')->with('success', 'Score deleted successfully');
    }

    // Helper method for individual game points
    private function getIndividualPoints($position)
    {
        switch ($position) {
            case '1st':
                return 5;
            case '2nd':
                return 3;
            default:
                return 0;
        }
    }

    // Helper method for group game points
    private function getGroupPoints($position)
    {
        switch ($position) {
            case '1st':
                return 10;
            case '2nd':
                return 5;
            default:
                return 0;
        }
    }
}
