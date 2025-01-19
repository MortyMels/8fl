<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('form_fields', 'dictionary_id')) {
                $table->foreignId('dictionary_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (Schema::hasColumn('form_fields', 'dictionary_id')) {
                $table->dropForeign(['dictionary_id']);
                $table->dropColumn('dictionary_id');
            }
        });
    }
}; 