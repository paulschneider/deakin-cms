<?php

use Illuminate\Database\Seeder;

class IconsTableSeeder extends Seeder
{

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('icons')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $circle = <<<EOT
    <svg width="80" height="80">
      <circle cx="40" cy="40" r="30" stroke="green" stroke-width="4" fill="yellow"></circle>
    </svg>
EOT;
        $icons[] = [
            'title'      => 'Circle',
            'svg'        => $circle,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ];

        // Uncomment the below to run the seeder
        DB::table('icons')->insert($icons);
    }
}
