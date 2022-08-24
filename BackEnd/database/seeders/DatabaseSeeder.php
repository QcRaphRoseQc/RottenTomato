<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            actorsSeeder::class,
            languageSeeder::class,            
            filmsSeeder::class,
            actor_filmsSeeder::class,
            rolesSeeder::class,
            usersSeeder::class,
            criticsSeeder::class,
            
        ]);
        

    }
}
