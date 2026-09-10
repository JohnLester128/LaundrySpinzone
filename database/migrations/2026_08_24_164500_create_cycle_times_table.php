<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycle_times', function (Blueprint $table) {
            $table->id();
            $table->integer('kg')->unique();
            $table->integer('washing_time_minutes')->default(33);
            $table->integer('drying_time_minutes')->default(5);
            $table->timestamps();
        });

        $defaults = [
            ['kg' => 1, 'washing_time_minutes' => 33, 'drying_time_minutes' => 5],
            ['kg' => 2, 'washing_time_minutes' => 33, 'drying_time_minutes' => 10],
            ['kg' => 3, 'washing_time_minutes' => 33, 'drying_time_minutes' => 15],
            ['kg' => 4, 'washing_time_minutes' => 33, 'drying_time_minutes' => 20],
            ['kg' => 5, 'washing_time_minutes' => 33, 'drying_time_minutes' => 25],
            ['kg' => 6, 'washing_time_minutes' => 33, 'drying_time_minutes' => 30],
            ['kg' => 7, 'washing_time_minutes' => 33, 'drying_time_minutes' => 35],
            ['kg' => 8, 'washing_time_minutes' => 33, 'drying_time_minutes' => 40],
        ];

        foreach ($defaults as $item) {
            DB::table('cycle_times')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle_times');
    }
};
