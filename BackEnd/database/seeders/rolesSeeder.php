<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path() . '/seeders/roles.sql');   
        \DB::statement($sql);
    }
}
