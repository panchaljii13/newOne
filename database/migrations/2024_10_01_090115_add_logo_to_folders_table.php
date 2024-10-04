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
        Schema::table('folders', function (Blueprint $table) {
            $table->string('logo')->nullable(); // Add a logo column
        });
    }
    
    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
