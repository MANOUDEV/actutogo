<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'nom' => 'DENANYOH',
                'prenoms' => 'Alexandre',
                'nom_complet' => 'DENANYOH Alexandre',
                'email' => 'nonojack@yahoo.fr',
                'telephone' => '+33 6 27 38 75 14',
                'authorName' => 'Infos du pays',
                'slug' => 'mawuwonamtg',
                'wp_author_id' => 1,
                'date_publish' =>  now(),
                'description' => "<p>
                    Nous tenons à rappeler aux visiteurs du site que
                    sans partenariat avec togoactualite.com, la reprise des articles même partielle est strictement interdite.
                    Tout contrevenant s'expose à de graves poursuites.
                </p>"
            ],
            [
                'nom' => 'MIKANDO',
                'prenoms' => 'Eric',
                'nom_complet' => 'MIKANDO Eric',
                'email' => 'ericmakondo@gmail.com',
                'telephone' => '+228 91 91 91 91',
                'authorName' => 'Togo infos',
                'slug' => 'dutogoactu',
                'wp_author_id' => 10,
                'date_publish' =>  now(),
                'description' => "<p>
                    Nous tenons à rappeler aux visiteurs du site que
                    sans partenariat avec togoactualite.com, la reprise des articles même partielle est strictement interdite.
                    Tout contrevenant s'expose à de graves poursuites.
                </p>"
            ],
        ];

        foreach ($datas as $author) {
            Author::create($author);
        }

        // Génération du sitemap
        $sitemapHeader = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;

        $sitemapContent = $sitemapHeader . "\n";

        $authors = Author::all();
        $lastmod = now()->toDateString();

        foreach ($authors as $author) {
            $slug= \Illuminate\Support\Str::slug(strip_tags($author->authorName));
            $url = "https://togoactu.com/authors/{$slug}";
            $sitemapContent .= <<<XML
  <url>
    <loc>{$url}</loc>
    <lastmod>{$lastmod}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>

XML;
        }

        $sitemapContent .= "</urlset>";

        Storage::disk('public')->put('sitemap-authors.xml', $sitemapContent);
    }
}
