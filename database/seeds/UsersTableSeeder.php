<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        echo "";

        $users[] = [
            'id'         => 1,
            'email'      => 'al.munnings@iconinc.com.au',
            'password'   => str_random(20),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'name'       => 'Al',
            'active'     => 1,
        ];

        $users[] = [
            'id'         => 2,
            'email'      => 'fabrian@iconinc.com.au',
            'password'   => str_random(20),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'name'       => 'Fabrian',
            'active'     => 1,
        ];

        $users[] = [
            'id'         => 3,
            'email'      => 'dan.gregson@iconinc.com.au',
            'password'   => str_random(20),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'name'       => 'Dan',
            'active'     => 1,
        ];

        $users[] = [
            'id'         => 4,
            'email'      => 'chris@iconinc.com.au',
            'password'   => str_random(20),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'name'       => 'Chris',
            'active'     => 1,
        ];

        $users[] = [
            'id'         => 5,
            'email'      => 'paul.schneider@iconinc.com.au',
            'password'   => str_random(20),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'name'       => 'Paul',
            'active'     => 1,
        ];

        foreach ($users as &$user) {
            $this->command->getOutput()->writeln('<comment>' . $user['email'] . ' password is: ' . $user['password'] . '</comment>');

            $user['password'] = Hash::make($user['password']);
        }

        // Uncomment the below to run the seeder
        DB::table('users')->insert($users);
    }
}
