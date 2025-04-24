<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'logo'];
    public function scoreboards()
    {
        return $this->hasMany(Scoreboard::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'scoreboards')->withPivot('points');
    }

    public function getTotalScoreAttribute()
    {
        return $this->scoreboards()->sum('points');
    }

    public function getScoreBreakdownAttribute()
    {
        return $this->scoreboards()
            ->with('game')
            ->get()
            ->mapWithKeys(function ($scoreboard) {
                return [$scoreboard->game->name => [
                    'category' => $scoreboard->game->category,
                    'score' => $scoreboard->points,
                    'position' => $scoreboard->position
                ]];
            })
            ->toArray();
    }
    public function getCategoryScoresAttribute()
        {
            // Step 1: Get all unique categories from the related games
            $categories = Game::pluck('category')  // Pluck the category from the game relation
                ->unique();          // Get unique categories

            // Step 2: Initialize an array to hold category scores
            $categoryScores = [];

            // Step 3: Loop through each category and calculate the total score
            foreach ($categories as $category) {
                // Calculate the total score for each category by summing the points in the scoreboards
                $categoryScores[$category] = $this->scoreboards()
                    ->whereHas('game', function ($query) use ($category) {
                        $query->where('category', $category);  // Filter scoreboards by category
                    })
                    ->sum('points');  // Sum the points in the scoreboard for this category
            }
            // Step 4: Ensure that categories without scores are included with 0 points
            foreach ($categories as $category) {
                if (!isset($categoryScores[$category])) {
                    $categoryScores[$category] = 0;  // Set score to 0 if no points are found
                }
            }
            
            return $categoryScores;

        }

}
