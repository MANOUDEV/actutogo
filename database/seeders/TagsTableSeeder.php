<?php

namespace Database\Seeders;

use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Préparation du sitemap XML
        $sitemapHeader = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;
        $sitemapContent = $sitemapHeader . "\n";

        $response = Http::get("https://www.togoactualite.com/wp-json/wp/v2/tags?per_page=100");

        $totalPages = $response->header('x-wp-totalpages')[0];

        for ($i = 1; $i <= $totalPages; $i++) {

            $tags = Http::get("https://www.togoactualite.com/wp-json/wp/v2/tags?per_page=100&page=$i")->json();

            foreach ($tags as $value) {

                $date = Carbon::parse(now());
                $mois_id = $date->format('m');
                $year = $date->format('Y');

                $mois = InfosMonthYear::where('month_id', $mois_id)->first();
                $date_name = $mois->month . ' ' . $year;

                $verify_date_name = InfosMonthYearTag::where('date_name', $date_name)->first();

                if (!$verify_date_name) {
                    InfosMonthYearTag::create(['date_name' => $date_name, 'deja_citer' => 0, 'user_id' => 1]);
                } else {
                    if ($verify_date_name->deja_citer === 0) {
                        InfosMonthYearTag::create(['date_name' => $date_name, 'deja_citer' => 1, 'user_id' => 1]);
                    }
                }

                // Création ou mise à jour du tag
                $post = Tag::updateOrCreate(
                    ['wp_tag_id' => intval($value['id'])],
                    [
                        'name' => $value['name'],
                        'date_name' => $date_name,
                        'slug' => $value['slug'],
                        'count_publications' => 0,
                        'date_publish' => now(),
                        'user_id' => 1
                    ]
                );

                // Ajout dans le sitemap
                $slug = $value['slug'];
                $url = "https://togoactu.com/tags/{$slug}";
                $lastmod = now()->toDateString();

                $sitemapContent .= <<<XML
  <url>
    <loc>{$url}</loc>
    <lastmod>{$lastmod}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>

XML;
            }
        }

        // Fermeture du XML
        $sitemapContent .= "</urlset>";

        // Écriture dans sitemap-tags.xml
        Storage::disk('public')->put('sitemap-tags.xml', $sitemapContent);
    }
}
