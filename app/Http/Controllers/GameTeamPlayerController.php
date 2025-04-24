<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\GameTeamPlayer;
use App\Models\Scoreboard;
use Illuminate\Http\Request;

class GameTeamPlayerController extends Controller
{
    public function index()
    {
        $games = Game::withCount('teamPlayers')
            ->get()
            ->map(function ($game) {
                $game->has_players = $game->team_players_count > 0;
                return $game;
            });

        return view('game_team_players.index', compact('games'));
    }

    public function show(Game $game)
    {
        $playerAssignments = GameTeamPlayer::where('game_id', $game->id)
            ->get()
            ->groupBy('team_id');
            $scoreboards = Scoreboard::where('game_id', $game->id)->get();
        $teams = Team::all()->keyBy('id');

        return view('game_team_players.show', compact('game', 'playerAssignments', 'teams', 'scoreboards'));
    }

    public function destroy(Game $game)
    {
        GameTeamPlayer::where('game_id', $game->id)->delete();
        return redirect()->route('games.index')->with('success', 'Players deleted successfully!');
    }

    public function create(Game $game)
    {
        $teams = Team::all();
        return view('game_team_players.create', compact('game', 'teams'));
    }

    public function store(Request $request, Game $game)
    {
        $data = $request->validate([
            'players_data' => 'required|array'
        ]);

        foreach ($data['players_data'] as $teamId => $entries) {
            foreach ($entries as $i => $entry) {
                $players = $entry['players'] ?? [];
                $participantCount = $game->type === 'group' ? $game->members_per_team : $game->max_participants_per_team;

                if (empty($players)) {
                    $players = [];
                    for ($j = 1; $j <= $participantCount; $j++) {
                        $players[] = "Player $j";
                    }
                }

                $players = array_slice($players, 0, $participantCount);

                $subTeamName = null;
                if ($game->type === 'group') {
                    $subTeamName = $entry['sub_team_name'] ?? "Team " . ($entry['group_number'] ?? ($i + 1));
                }

                GameTeamPlayer::create([
                    'game_id' => $game->id,
                    'team_id' => $entry['team_id'],
                    'group_number' => $entry['group_number'] ?? null,
                    'sub_team_name' => $subTeamName,
                    'players' => json_encode($players),
                ]);
            }
        }

        return redirect()->route('games.index')->with('success', 'Players assigned successfully!');
    }

    public function edit(Game $game)
    {
        $teams = Team::all();
        $playerAssignments = GameTeamPlayer::where('game_id', $game->id)
            ->get()
            ->groupBy('team_id');

        $allPlayers = GameTeamPlayer::where('game_id', $game->id)->get()
            ->flatMap(function ($gtp) {
                return json_decode($gtp->players, true);
            })
            ->unique()
            ->values();

        return view('game_team_players.edit', compact('game', 'teams', 'playerAssignments', 'allPlayers'));
    }

    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'players_data' => 'required|array'
        ]);

        GameTeamPlayer::where('game_id', $game->id)->delete();

        foreach ($data['players_data'] as $teamId => $entries) {
            foreach ($entries as $i => $entry) {
                $players = $entry['players'] ?? [];
                $participantCount = $game->type === 'group' ? $game->members_per_team : $game->max_participants_per_team;

                if (empty($players)) {
                    $players = [];
                    for ($j = 1; $j <= $participantCount; $j++) {
                        $players[] = "Player $j";
                    }
                }

                $players = array_slice($players, 0, $participantCount);

                $subTeamName = null;
                if ($game->type === 'group') {
                    $subTeamName = $entry['sub_team_name'] ?? "Team " . ($entry['group_number'] ?? ($i + 1));
                }

                GameTeamPlayer::create([
                    'game_id' => $game->id,
                    'team_id' => $entry['team_id'],
                    'group_number' => $entry['group_number'] ?? null,
                    'sub_team_name' => $subTeamName,
                    'players' => json_encode($players),
                ]);
            }
        }

        return redirect()->route('games.index')->with('success', 'Players updated successfully!');
    }
}
