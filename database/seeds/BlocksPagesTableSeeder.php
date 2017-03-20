<?php

use Illuminate\Database\Seeder;

class BlocksPagesTableSeeder extends Seeder
{

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('block_page_revision')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $blocks[] = [
            'block_id' => 1,
            'page_revision_id'  => 2,
            'type'     => 'form',
            'weight'   => 0,
        ];

        // Uncomment the below to run the seeder
        DB::table('block_page_revision')->insert($blocks);
    }
}
