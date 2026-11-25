<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGa4GtmToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'ga4_id')) {
                $table->string('ga4_id', 64)->nullable();
            }
            if (! Schema::hasColumn('settings', 'gtm_id')) {
                $table->string('gtm_id', 64)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ga4_id')) {
                $table->dropColumn('ga4_id');
            }
            if (Schema::hasColumn('settings', 'gtm_id')) {
                $table->dropColumn('gtm_id');
            }
        });
    }
}
