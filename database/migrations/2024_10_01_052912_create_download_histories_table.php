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
        // Create the download_histories table
        Schema::create('download_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Create foreign key to users table
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade'); // Foreign key to folders table with cascade delete
            $table->timestamp('downloaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the download_histories table
        Schema::dropIfExists('download_histories');
    }
};
