<?php

use Illuminate\Database\Seeder;

class VariableTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('variables')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $vars = [
            // Titles
            'admin.title'       => 'Admin | Deakin Digital',
            'admin.title.short' => 'DD+',
            'admin.search.bar'  => 'Search the system...',

            'admin.welcome'     => 'Deakin Digital Admin System',
            'site.title'        => 'Deakin Digital',
            // Auth
            'login.title'       => 'Authentication Required',
            'login.help'        => 'Please log in to continue to the Deakin Digital administration system.',
            // Forgot password
            'forgot.title'      => 'Forgot Password',
            'forgot.help'       => 'Passwords can be forgotten. Fill out the form below and we will send you out a reset request.',
            // Reset
            'reset.title'       => 'Reset Password',
            'reset.help'        => 'You have chosen to reset your password. Be sure to choose something secure.',
            // Register page (not logged in)
            'register.title'    => 'Register',
            'register.help'     => 'Please fill out the following fields to register a new account for system access.',
            // Activate page
            'activate.title'    => 'Activate',
            'activate.help'     => 'Please fill out the following fields to activate your account. You should use the email address the invitation was sent to.',
            // Generic
            'title.seperator'   => '|',
            'site.copyright'    => 'Deakin Digital',
        ];

        $built = [];
        foreach ($vars as $k => $value) {
            $built[] = [
                'name'       => $k,
                'value'      => serialize($value),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ];
        }

        // Uncomment the below to run the seeder
        DB::table('variables')->insert($built);
    }
}
