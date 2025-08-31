<?php

namespace Database\Seeders;

use App\Models\NewsLetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsLetterTableSeeder extends Seeder
{
    public function run()
    {
        $newsLetters = [
            'manouadjanor@gmail.com',
            'nonojack@yahoo.fr'
        ];

        foreach ($newsLetters as $email) {
            NewsLetter::updateOrCreate(
                ['email' => $email], // clé unique pour éviter les doublons
                [
                    'slug' => Str::slug($email),
                    'status' => 1,
                    'date_publish' => now(),
                ]
            );
        }
    }
}
