<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{

    public function run()
    {
        \DB::table('users')->truncate();

        $users = [
            ['HighSolutions', 'admin@highsolutions.pl', 'H$Adm1n'],
        ];

        foreach($users as $user) {
             \DB::table('users')->insert([
                'name' => $user[0],
                'email' => $user[1],
                'password' => bcrypt($user[2]),
            ]);
        }
    }
    
}
