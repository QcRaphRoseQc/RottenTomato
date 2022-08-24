<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class actorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path() . '/seeders/actors.sql');
   
        \DB::statement($sql);
    }
}
