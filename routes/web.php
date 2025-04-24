<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameTeamPlayerController;
use App\Http\Controllers\ScoreSummaryController;
use App\Models\Team;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    // Fetch all teams with the total score and breakdown, ordered by highest score
    $teams = Team::with(['scoreboards.game'])
        ->get()
        ->sortByDesc(fn($team) => $team->total_score); // Sort by total score in descending order
    
    return view('welcome', compact('teams')); // Return the new home page with team data
});
Route::get('/scoreSummary', [ScoreSummaryController::class, 'scoreSummary'])->name('scoreSummary');
Route::get('/report', [ScoreSummaryController::class, 'report'])->name('report');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/score-summary', [ScoreSummaryController::class, 'index'])->name('scoreboards.summary');
    Route::post('scoreboards/winners', [ScoreboardController::class, 'storeWinners'])->name('scoreboards.storeWinners');
    Route::get('fetch-players/{gameId}/{teamId}', [GameController::class, 'fetchPlayers'])->name('fetch.players');

    Route::resource('teams', TeamController::class);
    Route::resource('games', GameController::class);
    Route::resource('scoreboards', ScoreboardController::class);
    Route::prefix('games/{game}')->group(function () {
        Route::get('assign-players', [GameTeamPlayerController::class, 'create'])->name('game-team-players.create');
        Route::post('assign-players', [GameTeamPlayerController::class, 'store'])->name('game-team-players.store');
        Route::get('edit-players', [GameTeamPlayerController::class, 'edit'])->name('game-team-players.edit');
    Route::post('update-players', [GameTeamPlayerController::class, 'update'])->name('game-team-players.update');
    });
    Route::get('/game-team-players', [GameTeamPlayerController::class, 'index'])->name('game-team-players.index');
Route::get('/game-team-players/{game}', [GameTeamPlayerController::class, 'show'])->name('game-team-players.show');
    Route::delete('/game-team-players/{game}', [GameTeamPlayerController::class, 'destroy'])->name('game-team-players.destroy');
});

require __DIR__.'/auth.php';
Route::redirect('/register', '/login');
Route::get('/clear-cache', function() {
    $exitCode    = Artisan::call('cache:clear');
    $exitCode1    = Artisan::call('config:clear');
    $config      = Artisan::call('config:cache');
    $view        = Artisan::call('view:clear');
    $optimize        = Artisan::call('optimize:clear');
    echo "Cache is cleared";
 });
 
 