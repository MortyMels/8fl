<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->json('options')->nullable();
            $table->json('excluded_options')->nullable();
            $table->foreignId('dictionary_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
}; 