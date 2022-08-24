<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class languageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path() . '/seeders/languages.sql');   
        \DB::statement($sql);

        
    }
}
