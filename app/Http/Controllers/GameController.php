<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameTeamPlayer;
use App\Models\Scoreboard;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;
class GameController extends Controller
{
    /**
     * Display a listing of the games.
     */
    public function index()
    {
        $games = Game::all();
        $teams = Team::all();
        $scoreboard = Scoreboard::all();
        
        return view('games.index', compact('games', 'teams', 'scoreboard'));
    }
    public function fetchPlayers($gameId, $teamId)
    {
        $game = Game::find($gameId);
    
        if (!$game) {
            return response()->json(['players' => []]);
        }
    
        $isGroupGame = $game->type === 'group';
    
        $entries = GameTeamPlayer::where('game_id', $gameId)
                                 ->where('team_id', $teamId)
                                 ->get();
    
        $players = [];
    
        foreach ($entries as $entry) {
            $decodedNames = json_decode($entry->players, true) ?? [];
    
            if ($isGroupGame) {
                // Group game: show "Sub Team A - Ali, Sara"
                $label = $entry->sub_team_name . ' - ' . implode(', ', $decodedNames);
                $name1 = $entry->sub_team_name . ' - ' . implode(', ', array_slice($decodedNames, 0, 2));
                $players[] = ['label' => $name1, 'value' => $label]; // You can change 'value' if needed
            } else {
                // Individual game: show each player
                foreach ($decodedNames as $name) {
                    $players[] = ['label' => $name, 'value' => $name];
                }
            }
        }
    
        return response()->json(['players' => $players]);
    }
    


    /**
     * Show the form for creating a new game.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created game in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:individual,group',
        'category' => "required|in:Women's,Men's,Kids",
        'game_date' => 'required|date',
        // Conditional validations
        'max_participants_per_team' => 'required_if:type,individual|nullable|integer|min:1',
        'number_of_teams' => 'required_if:type,group|nullable|integer|min:1',
        'members_per_team' => 'required_if:type,group|nullable|integer|min:1',
    ]);

    $game = new Game;
    $game->name = $request->name;
    $game->type = $request->type;
    $game->category = $request->category;
    $game->game_date = $request->game_date;

    if ($request->type === 'individual') {
        $game->max_participants_per_team = $request->max_participants_per_team;
        $game->number_of_teams = null;
        $game->members_per_team = null;
    } else {
        $game->max_participants_per_team = null;
        $game->number_of_teams = $request->number_of_teams;
        $game->members_per_team = $request->members_per_team;
    }

    $game->save();

    return redirect()->route('games.index')->with('success', 'Game added successfully!');
}


    /**
     * Show the form for editing the specified game.
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified game in storage.
     */
    public function update(Request $request, Game $game)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:individual,group',
        'category' => "required|in:Women's,Men's,Kids",
        'game_date' => 'required|date',
        'max_participants_per_team' => 'required_if:type,individual|nullable|integer|min:1',
        'number_of_teams' => 'required_if:type,group|nullable|integer|min:1',
        'members_per_team' => 'required_if:type,group|nullable|integer|min:1',
    ]);

    try {
        $game->name = $request->name;
        $game->type = $request->type;
        $game->category = $request->category;
        $game->game_date = $request->game_date;

        if ($request->type === 'individual') {
            $game->max_participants_per_team = $request->max_participants_per_team;
            $game->number_of_teams = null;
            $game->members_per_team = null;
        } else {
            $game->max_participants_per_team = null;
            $game->number_of_teams = $request->number_of_teams;
            $game->members_per_team = $request->members_per_team;
        }

        $game->save();
    } catch (\Exception $e) {
        return redirect()->route('games.index')->with('error', 'Game not updated successfully!');
    }

    return redirect()->route('games.index')->with('success', 'Game updated successfully!');
}


    /**
     * Remove the specified game from storage.
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Game deleted successfully!');
    }
}
