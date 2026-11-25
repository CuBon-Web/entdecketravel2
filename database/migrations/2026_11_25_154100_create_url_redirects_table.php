<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRedirectsTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('url_redirects')) {
            Schema::create('url_redirects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('from_path', 500)->unique();
                $table->string('to_path', 500);
                $table->unsignedSmallInteger('status_code')->default(301);
                $table->unsignedInteger('hits')->default(0);
                $table->timestamps();
                $table->index('to_path');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('url_redirects');
    }
}
