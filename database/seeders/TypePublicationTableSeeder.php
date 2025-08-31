<?php

namespace Database\Seeders;

use App\Models\TypePublication;
use Illuminate\Database\Seeder;

class TypePublicationTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => "ARTICLE", 'slug' => "article"],
            ['name' => "INFO ALERT", 'slug' => "info-alert"],
            ['name' => "ANNONCE", 'slug' => "annonce"],
            ['name' => "VIDEOS", 'slug' => "videos"],
            ['name' => "PUBLICITES", 'slug' => "publicites"],
            ['name' => "EVENEMENT", 'slug' => "evenement"],
        ];

        foreach ($types as $type) {
            TypePublication::updateOrCreate(
                ['slug' => $type['slug']], // clé unique pour éviter les doublons
                [
                    'name' => $type['name'],
                    'date_publish' => now(),
                    'user_id' => 1,
                ]
            );
        }
    }
}
