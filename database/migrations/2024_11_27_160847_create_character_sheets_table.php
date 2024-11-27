<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterSheetsTable extends Migration
{
    public function up()
    {
        Schema::create('character_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->json('valores')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('character_sheets');
    }
}