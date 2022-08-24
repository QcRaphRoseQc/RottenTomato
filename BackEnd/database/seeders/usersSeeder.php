<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create a struct with random first name
        $first_names = [
            'John',
            'Jane',
            'Mary',
            'Bob',
            'Tom',
            'Bill',
            'Mike',
            'Jack',
            'Linda',
            'Sara',
            'Peter',
            'Paul',
            'Mary',
            'Bob',
            'Tom',
        ];

        //create a struct with random last name
        $last_names = [
            'Smith',
            'Johnson',
            'Williams',
            'Jones',
            'Brown',
            'Davis',
            'Miller',
            'Wilson',
            'Moore',
            'Taylor',
            'Anderson',
            'Thomas',
            'Jackson',
            'White',
            'Harris',
        ];


        //create 500 users with random login, password:admin1234, email, last_name, first_name, role_id:2
        for ($i = 0; $i < 500; $i++) {
            
            $password = 'admin1234';            
            $last_name = $last_names[rand(0, count($last_names) - 1)];
            $first_name = $first_names[rand(0, count($first_names) - 1)];
            $login = $first_name . $last_name;
            $email =  $first_name . '.' . $last_name . '@gmail.com';
            $role_id = 2;
            \App\Models\User::create([
                'login' => $login,
                'password' => $password,
                'email' => $email,
                'last_name' => $last_name,
                'first_name' => $first_name,
                'role_id' => $role_id,
            ]);
    }

}
    
}
