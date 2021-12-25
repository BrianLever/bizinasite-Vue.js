<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('country')->truncate();
        $path = __DIR__.'/source/countries.sql';
        DB::unprepared(file_get_contents($path));

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
