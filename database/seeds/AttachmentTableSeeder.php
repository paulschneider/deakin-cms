<?php

use Illuminate\Database\Seeder;

class AttachmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('attachments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $attachments[] = [
            'id'                => 123,
            'term_id'           => 5,
            'title'             => 'kinetic-header-banner.jpg',
            'file_file_name'    => 'kinetic-header-banner.jpg',
            'file_file_size'    => 319509,
            'file_content_type' => 'image/jpeg',
            'file_updated_at'   => '2015-07-08 13:52:06',
            'created_at'        => new DateTime,
            'updated_at'        => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('attachments')->insert($attachments);
    }
}
