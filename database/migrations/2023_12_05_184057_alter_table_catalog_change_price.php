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
        Schema::table('catalog', function (Blueprint $table) {
            Schema::table('catalog', function (Blueprint $table) {
                $table->integer('price')->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalog', function (Blueprint $table) {
            Schema::table('catalog', function (Blueprint $table) {
                Schema::table('catalog', function (Blueprint $table) {
                    $table->varchar('price')->change();
                });
            });
        });
    }
};
