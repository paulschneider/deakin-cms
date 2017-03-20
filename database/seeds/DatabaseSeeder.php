<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('MetaTableSeeder');
        $this->call('PagesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('VocabAndTermsTableSeeder');
        $this->call('MenuAndLinksTableSeeder');
        $this->call('VariableTableSeeder');
        $this->call('CronTableSeeder');
        $this->call('BlocksTableSeeder');
        $this->call('BlocksPagesTableSeeder');
        $this->call('AttachmentTableSeeder');
        $this->call('BannersTableSeeder');
        $this->call('IconsTableSeeder');

        $this->command->getOutput()->writeln("<comment>Running Permissions Build</comment>");
        \Artisan::call('permission:build');
        $this->command->getOutput()->writeln("<info>Permissions Built</info>");
    }
}
