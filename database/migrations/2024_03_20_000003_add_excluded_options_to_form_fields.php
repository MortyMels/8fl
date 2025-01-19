<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('form_fields', 'excluded_options')) {
                $table->json('excluded_options')->nullable()->after('options');
            }
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (Schema::hasColumn('form_fields', 'excluded_options')) {
                $table->dropColumn('excluded_options');
            }
        });
    }
}; 