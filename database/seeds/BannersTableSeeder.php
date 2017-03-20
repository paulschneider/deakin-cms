<?php

use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('banners')->truncate();
        DB::table('banners_images')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $banners[] = [
            'title'      => '2015 Brand',
            'stub'       => '2015_brand',
            'online'     => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];

        /**
         * Banner images
         */

        $banner_images[] = [
            'parent_id'     => null,
            'banner_id'     => 1,
            'title'         => 'Orange Blue',
            'online'        => 1,
            'weight'        => 0,
            'attachment_id' => 123,
            'created_at'    => new DateTime,
            'updated_at'    => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('banners')->insert($banners);
        DB::table('banners_images')->insert($banner_images);
    }
}
