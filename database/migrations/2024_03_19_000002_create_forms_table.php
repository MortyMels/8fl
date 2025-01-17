<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Добавляем связь с формой в таблицу полей
        Schema::table('form_fields', function (Blueprint $table) {
            $table->foreignId('form_id')->after('id')->constrained()->onDelete('cascade');
        });

        // Добавляем связь с формой в таблицу отправок
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId('form_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropColumn('form_id');
        });

        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropColumn('form_id');
        });

        Schema::dropIfExists('forms');
    }
}; 