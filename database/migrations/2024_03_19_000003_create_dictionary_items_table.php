<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dictionary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dictionary_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['dictionary_id', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dictionary_items');
    }
}; 