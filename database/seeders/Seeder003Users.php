<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class Seeder003Users extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'first_name' => 'Farid',
                'last_name' => 'Shahidi',
                'url' => 'farid-shahidi',
                'email' => 'farid.sh69@gmail.com',
                'phone' => '09120568203',
                'password' => bcrypt('1111'),
                'activated' => 1,
            ],
            [
                'id' => 2,
                'first_name' => 'admin',
                'last_name' => 'cms',
                'url' => 'admin-cms',
                'email' => 'admin@cms.com',
                'phone' => '09120568204',
                'password' => bcrypt('1111'),
                'activated' => 1,
            ],
            [
                'id' => 3,
                'first_name' => 'Content',
                'last_name' => 'Writer',
                'url' => 'content-writer',
                'email' => 'content@gmail.com',
                'phone' => '+9120568203',
                'password' => bcrypt('1111'),
                'activated' => 1,
            ],
        ];
        foreach($users as $user){
            User::updateOrCreate(['id' => $user['id']], $user);
        }
    }
}
