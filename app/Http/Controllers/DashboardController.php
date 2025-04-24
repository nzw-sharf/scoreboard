<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use App\Models\Scoreboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all teams, games, and scoreboards
        $teams = Team::all();
        $games = Game::all();
        $scoreboards = Scoreboard::all(); // Or you can fetch scoreboard-related data in the format you need

        // Pass the data to the view
        return view('dashboard', compact('teams', 'games', 'scoreboards'));
    }
}
