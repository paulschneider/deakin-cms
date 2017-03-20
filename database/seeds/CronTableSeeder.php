<?php

use Illuminate\Database\Seeder;

class CronTableSeeder extends Seeder
{

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('cron_jobs')->truncate();

        $pages[] = [
            'min'        => 0,
            'hour'       => '*',
            'day_month'  => '*',
            'month'      => '*',
            'day_week'   => '*',
            'year'       => null, // Used for onces.
            'command'    => 'inspire',
            'online'     => true,
            'once'       => false,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('cron_jobs')->insert($pages);
    }
}
