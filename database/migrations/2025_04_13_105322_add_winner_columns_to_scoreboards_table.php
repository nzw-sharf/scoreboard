<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scoreboards', function (Blueprint $table) {
            $table->string('winner_name')->nullable()->after('points'); // replace with actual column name
            $table->string('winner_team')->nullable()->after('winner_name');
            $table->boolean('is_tie_or_not')->default(false)->after('winner_team'); // New column for tie or overtime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scoreboards', function (Blueprint $table) {
            $table->dropColumn(['winner_name', 'winner_team','is_tie_or_not']);
        });
    }
};
