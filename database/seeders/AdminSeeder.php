<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $users = [
//            0 => [
//                'name' => 'Hekim',
//                'email' => 'user.hekim@gmail.com',
//                'password' => \Hash::make('hekim'),
//            ],
            1 => [
                'name' => 'Muhammet',
                'email' => 'user.muhammet@gmail.com',
                'password' => \Hash::make('muhammet'),
            ],
            2 => [
                'name' => 'Ysmayyl',
                'email' => 'user.ysmayyl@gmail.com',
                'password' => \Hash::make('ysmayyl'),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
