<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        $baseUrl = "https://www.togoactualite.com/wp-json/wp/v2/categories";
        $perPage = 100;
        $batchSize = 5; // Nombre de pages à traiter par lot

        // Récupérer la première page pour connaître le nombre total de pages
        $response = Http::get("$baseUrl?per_page=$perPage&page=1");

        if (!$response->successful()) {
            $this->command->error('Impossible de récupérer les catégories depuis WordPress.');
            return;
        }

        $totalPages = intval($response->header('x-wp-totalpages', 1));
        $this->command->info("Total pages à traiter : $totalPages");

        $pages = range(1, $totalPages);

        // Traiter les pages par lots
        foreach (array_chunk($pages, $batchSize) as $batch) {
            foreach ($batch as $page) {
                $response = Http::get("$baseUrl?per_page=$perPage&page=$page");

                if (!$response->successful()) {
                    $this->command->warn("Échec de récupération de la page $page. Passage à la suivante...");
                    continue;
                }

                $categories = $response->json();

                foreach ($categories as $categoryData) {
                    // Crée ou met à jour la catégorie pour éviter les doublons
                    Category::updateOrCreate(
                        ['wp_category_id' => intval($categoryData['id'])],
                        [
                            'name' => $categoryData['name'],
                            'slug' => $categoryData['slug'],
                            'count_publications' => 0,
                            'date_publish' => now(),
                            'user_id' => 1
                        ]
                    );
                }

                $this->command->info("Page $page traitée.");
            }
        }

        $this->command->info('Import des catégories terminé avec succès !');
    }
}
