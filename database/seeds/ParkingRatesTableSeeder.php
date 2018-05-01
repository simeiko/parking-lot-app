<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ParkingRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates default parking rates for 1hr, 3hrs, 6hrs, and 24hrs.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parking_rates')->insert([
            [
                'max_minutes' => 60, // 1 hour
                'title' => '1 hour',
                'price_per_hour' => 3,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'max_minutes' => 180, // 3 hours
                'title' => '3 hours',
                'price_per_hour' => 4.5, // The price increases by 50% for each rate level
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'max_minutes' => 360, // 6 hours
                'title' => '6 hours',
                'price_per_hour' => 6.75,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'max_minutes' => 1440, // 24 hours ( all day )
                'title' => 'All Day',
                'price_per_hour' => 10.125,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
