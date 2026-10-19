<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImpressiveNumbersTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('impressive_numbers')) {
            Schema::create('impressive_numbers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('value')->nullable();
                $table->string('suffix', 20)->nullable();
                $table->string('image')->nullable();
                $table->integer('status')->default(1);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (DB::table('impressive_numbers')->count() === 0) {
            $now = now();
            DB::table('impressive_numbers')->insert([
                [
                    'name' => 'Happy Travelers',
                    'value' => '25000',
                    'suffix' => '+',
                    'image' => 'https://traviox-html.wpthemeverse.com/images/counter-icon1.svg',
                    'status' => 1,
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Global Destinations',
                    'value' => '120',
                    'suffix' => '+',
                    'image' => 'https://traviox-html.wpthemeverse.com/images/counter-icon2.svg',
                    'status' => 1,
                    'sort_order' => 2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Customer Satisfaction',
                    'value' => '98',
                    'suffix' => '%',
                    'image' => 'https://traviox-html.wpthemeverse.com/images/counter-icon3.svg',
                    'status' => 1,
                    'sort_order' => 3,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Years of Experience',
                    'value' => '15',
                    'suffix' => '+',
                    'image' => 'https://traviox-html.wpthemeverse.com/images/counter-icon4.svg',
                    'status' => 1,
                    'sort_order' => 4,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('impressive_numbers');
    }
}
