<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'game_date', 'max_participants_per_team'];
    public function scoreboards()
    {
        return $this->hasMany(Scoreboard::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'scoreboards')->withPivot('score');
    }
    public function teamPlayers()
{
    return $this->hasMany(GameTeamPlayer::class, 'game_id');
}

}
