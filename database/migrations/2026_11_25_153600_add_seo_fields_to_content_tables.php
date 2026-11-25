<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoFieldsToContentTables extends Migration
{
    public function up()
    {
        $tables = ['products', 'blogs', 'product_category', 'blog_category', 'settings'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'seo_title')) {
                    $table->string('seo_title', 255)->nullable();
                }
                if (! Schema::hasColumn($tableName, 'meta_description')) {
                    $table->string('meta_description', 500)->nullable();
                }
            });
        }
    }

    public function down()
    {
        foreach (['products', 'blogs', 'product_category', 'blog_category', 'settings'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'seo_title')) {
                    $table->dropColumn('seo_title');
                }
                if (Schema::hasColumn($tableName, 'meta_description')) {
                    $table->dropColumn('meta_description');
                }
            });
        }
    }
}
