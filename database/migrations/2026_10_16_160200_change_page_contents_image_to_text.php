<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangePageContentsImageToText extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE page_contents MODIFY image TEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE page_contents MODIFY image VARCHAR(255) NULL');
    }
}
