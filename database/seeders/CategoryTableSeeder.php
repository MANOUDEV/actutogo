<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // En-tête XML du sitemap
        $sitemapHeader = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;

        // Initialisation du contenu XML
        $sitemapContent = $sitemapHeader . "\n";

        $response = Http::get("https://www.togoactualite.com/wp-json/wp/v2/categories?per_page=100");
        $totalPages = $response->header('x-wp-totalpages');

        for ($i = 1; $i <= $totalPages; $i++) {

            $categories = Http::get("https://www.togoactualite.com/wp-json/wp/v2/categories?per_page=100&page=$i")->json();

            foreach ($categories as $categoryData) {

                // Création ou mise à jour de la catégorie
                $category = Category::updateOrCreate(
                    ['wp_category_id' => intval($categoryData['id'])],
                    [
                        'name' => $categoryData['name'],
                        'slug' => $categoryData['slug'],
                        'count_publications' => 0,
                        'date_publish' => now(),
                        'user_id' => 1
                    ]
                );

                // Génération de l'entrée XML
                $slug = $categoryData['slug'];
                $url = "https://togoactu.com/{$slug}";
                $lastmod = now()->toDateString();

                $sitemapContent .= <<<XML
  <url>
    <loc>{$url}</loc>
    <lastmod>{$lastmod}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

XML;
            }
        }

        // Fermeture du XML
        $sitemapContent .= "</urlset>";

        // Écriture dans le fichier sitemap.xml (dans le disque 'public')
        Storage::disk('public')->put('sitemap-category.xml', $sitemapContent);
    }
}
