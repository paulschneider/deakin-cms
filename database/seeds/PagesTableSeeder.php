<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::table('pages')->truncate();
        DB::table('page_revisions')->truncate();

        $pages[] = [
            'online'      => 1,
            'slug'        => 'home',
            'revision_id' => 1,
            'created_at'  => new DateTime,
            'updated_at'  => new DateTime,
        ];

        $pages[] = [
            'online'      => 1,
            'slug'        => 'contact',
            'revision_id' => 2,
            'created_at'  => new DateTime,
            'updated_at'  => new DateTime,
        ];

        $revisions[] = [
            'status'     => 'current',
            'user_id'    => 1,
            'user_ip'    => '127.0.0.1',
            'entity_id'  => 1,
            'title'      => 'Home',
            'body'       => '<p>Body</p>',
            'summary'    => 'Testing QWERTY',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];

        $revisions[] = [
            'status'     => 'current',
            'user_id'    => 1,
            'user_ip'    => '127.0.0.1',
            'entity_id'  => 2,
            'title'      => 'Contact',
            'body'       => '<p>This is the contact page</p>',
            'summary'    => 'Contact form',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('pages')->insert($pages);

        // Uncomment the below to run the seeder
        DB::table('page_revisions')->insert($revisions);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
