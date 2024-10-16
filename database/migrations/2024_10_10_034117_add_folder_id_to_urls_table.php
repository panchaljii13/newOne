<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('urls', function (Blueprint $table) {
        $table->unsignedBigInteger('folder_id')->nullable()->after('url');

        // Optional: Add foreign key constraint if required
        $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
    });
    
}

public function down()
{
    Schema::table('urls', function (Blueprint $table) {
        $table->dropForeign(['folder_id']);
        $table->dropColumn('folder_id');
    });
}

};
