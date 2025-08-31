<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => "Administrateur",
                'nbRsp' => '&nbtsd!?'
            ],
            [
                'name' => "Publicateur d' articles",
                'nbRsp' => '&nbrsp?!'
            ],
            [
                'name' => "Visiteur",
                'nbRsp' => '&nbdfpo@!'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => Str::slug($role['name'])], // clé unique pour éviter les doublons
                [
                    'name' => $role['name'],
                    'nbRsp' => $role['nbRsp']
                ]
            );
        }
    }
}
