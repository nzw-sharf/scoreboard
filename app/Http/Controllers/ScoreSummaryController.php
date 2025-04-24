<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Scoreboard;
use App\Models\Team;

class ScoreSummaryController extends Controller
{
    public function index()
    {
        $teams = Team::with(['scoreboards.game'])->get(); // Load teams with scores and related games
        $categories = Game::pluck('category')->unique(); // Get unique categories

        $summary = [];

        foreach ($teams as $team) {
            $categoryScores = [];
            $totalScore = 0;
            $breakdown = [];

            foreach ($categories as $category) {
                $scores = $team->scoreboards()
                    ->whereHas('game', function ($query) use ($category) {
                        $query->where('category', $category);
                    })
                    ->get();

                $categoryTotal = $scores->sum('points');

                $categoryScores[$category] = $categoryTotal;
                $totalScore += $categoryTotal;

                // Store breakdown details
                foreach ($scores as $score) {
                    $breakdown[] = [
                        'game_name' => $score->game->name,
                        'category' => $score->game->category,
                        'position' => $score->position ?? '-',
                        'points' => $score->points,
                        'is_tie_or_not' => $score->is_tie_or_not,
                        'winning_team' => $score->winner_team,
                        'players' => $score->winner_name,
                    ];
                }
            }

            $summary[] = [
                'team' => $team->name,
                'category_scores' => $categoryScores,
                'total_score' => $totalScore,
                'breakdown' => $breakdown,
            ];
        }

        return view('scoreboards.summary', compact('summary', 'categories'));

    }
    public function scoreSummary()
    {
        $teams = Team::with(['scoreboards.game'])->get(); // Load teams with scores and related games
        $categories = Game::pluck('category')->unique(); // Get unique categories

        $summary = [];

        foreach ($teams as $team) {
            $categoryScores = [];
            $totalScore = 0;
            $breakdown = [];

            foreach ($categories as $category) {
                $scores = $team->scoreboards()
                    ->whereHas('game', function ($query) use ($category) {
                        $query->where('category', $category);
                    })
                    ->get();

                $categoryTotal = $scores->sum('points');

                $categoryScores[$category] = $categoryTotal;
                $totalScore += $categoryTotal;

                // Store breakdown details
                foreach ($scores as $score) {
                    $breakdown[] = [
                        'game_name' => $score->game->name,
                        'category' => $score->game->category,
                        'position' => $score->position ?? '-',
                        'points' => $score->points,
                        'is_tie_or_not' => $score->is_tie_or_not,
                        'winning_team' => $score->winner_team,
                        'players' => $score->winner_name,
                    ];
                }
            }

            $summary[] = [
                'team' => $team->name,
                'category_scores' => $categoryScores,
                'total_score' => $totalScore,
                'breakdown' => $breakdown,
            ];
        }

        return view('score-summary', compact('summary', 'categories'));

    }
    public function report()
    {
        return view('reports', ['scoreboards' => Scoreboard::orderBy('game_id','Desc')->get()]);
    }
}
