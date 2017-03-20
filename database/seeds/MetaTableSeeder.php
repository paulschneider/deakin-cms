<?php

use Illuminate\Database\Seeder;

class MetaTableSeeder extends Seeder
{

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('metas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $metas[] = [
            'metable_type' => 'App\Models\Page',
            'metable_id'   => 1,
            'key'          => 'meta_title',
            'value'        => 'Test',
            'created_at'   => new DateTime,
            'updated_at'   => new DateTime,
        ];

        $metas[] = [
            'metable_type' => 'App\Models\Page',
            'metable_id'   => 1,
            'key'          => 'meta_description',
            'value'        => 'Test',
            'created_at'   => new DateTime,
            'updated_at'   => new DateTime,
        ];

        $metas[] = [
            'metable_type' => 'App\Models\Page',
            'metable_id'   => 1,
            'key'          => 'meta_glossary',
            'value'        => 1,
            'created_at'   => new DateTime,
            'updated_at'   => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('metas')->insert($metas);
    }
}
