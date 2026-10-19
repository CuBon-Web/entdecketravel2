<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageDetailToBannerAdsTable extends Migration
{
    public function up()
    {
        Schema::table('banner_ads', function (Blueprint $table) {
            if (! Schema::hasColumn('banner_ads', 'image_detail')) {
                $table->string('image_detail')->nullable()->after('image');
            }
        });
    }

    public function down()
    {
        Schema::table('banner_ads', function (Blueprint $table) {
            if (Schema::hasColumn('banner_ads', 'image_detail')) {
                $table->dropColumn('image_detail');
            }
        });
    }
}
