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
        Schema::table('files', function (Blueprint $table) {
            $table->string('unique_id')->nullable()->unique()->after('id');
        });

        // Populate unique_id for existing records
        \App\Models\File::whereNull('unique_id')->chunk(100, function ($files) {
            foreach ($files as $file) {
                $file->update(['unique_id' => \Illuminate\Support\Str::uuid()->toString()]);
            }
        });

        // Make it not nullable after populating
        Schema::table('files', function (Blueprint $table) {
            $table->string('unique_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
