<?php

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('blocks')->truncate();
        DB::table('block_term')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $blocks[] = [
            'id'         => 1,
            'name'       => 'Contact Form',
            'type'       => 'one_column_registered',
            'content'    => 'a:3:{s:14:"col_one_method";s:40:"App\Forms\FormHandler.contactForm";s:13:"col_one_title";s:12:"Contact Form";s:13:"col_one_class";s:12:"contact-form";}',
            'includes'   => null,
            'excludes'   => null,
            'weight'     => 0,
            'online'     => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];
        $terms[] = [
            'block_id' => 1,
            'term_id'  => 10,
        ];

        // Uncomment the below to run the seeder
        DB::table('blocks')->insert($blocks);
        DB::table('block_term')->insert($terms);
    }
}
