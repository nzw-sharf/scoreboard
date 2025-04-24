<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoreboard extends Model
{
    use HasFactory;
    protected $fillable = ['team_id', 'game_id', 'position', 'points', 'game_team_player_id'];

    

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function gameTeamPlayers()
    {
        return $this->belongsTo(GameTeamPlayer::class, 'id', 'game_team_player_id');
    }

}
