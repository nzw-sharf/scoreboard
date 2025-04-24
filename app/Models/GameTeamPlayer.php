<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTeamPlayer extends Model
{
    protected $fillable = [
        'game_id',
        'team_id',
        'group_number',
        'sub_team_name',
        'players',
    ];

    protected $casts = [
        'players' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}

