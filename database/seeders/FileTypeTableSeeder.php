<?php

namespace Database\Seeders;

use App\Models\TypeFile;
use Illuminate\Database\Seeder;

class FileTypeTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => "IMAGES", 'slug_wp' => "image", 'slug' => "images"],
            ['name' => "VIDEOS", 'slug_wp' => "video", 'slug' => "videos"],
            ['name' => "DOCUMENTS", 'slug_wp' => "text", 'slug' => "documents"],
            ['name' => "APPLICATIONS", 'slug_wp' => "application", 'slug' => "applications"],
            ['name' => "AUDIOS", 'slug_wp' => "audio", 'slug' => "audios"],
        ];

        foreach ($types as $type) {
            TypeFile::updateOrCreate(
                ['slug_wp' => $type['slug_wp']], // clé unique pour éviter les doublons
                [
                    'name' => $type['name'],
                    'slug' => $type['slug'],
                    'date_publish' => now(),
                    'user_id' => 1,
                ]
            );
        }
    }
}
