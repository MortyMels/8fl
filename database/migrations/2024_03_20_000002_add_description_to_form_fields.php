<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('form_fields', 'description')) {
                $table->text('description')->nullable()->after('label');
            }
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            if (Schema::hasColumn('form_fields', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}; 