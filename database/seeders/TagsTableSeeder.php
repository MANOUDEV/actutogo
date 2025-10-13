<?php

namespace Database\Seeders;

use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        $perPage = 100;
        $batchSize = 5;

        $response = Http::get("http://www.togoactualite.com/wp-json/wp/v2/tags?per_page=$perPage");

        if (!$response->successful()) {
            $this->command->error("Impossible de récupérer les tags depuis WordPress.");
            return;
        }

        $totalPages = intval($response->header('x-wp-totalpages', 1));
        $this->command->info("Total pages de tags à traiter : $totalPages");

        $pages = range(1, $totalPages);
        $totalFetched = 0;
        $totalInserted = 0;

        foreach (array_chunk($pages, $batchSize) as $batch) {
            foreach ($batch as $page) {
                $tagsResponse = Http::get("http://www.togoactualite.com/wp-json/wp/v2/tags?per_page=$perPage&page=$page");

                if (!$tagsResponse->successful()) {
                    $this->command->warn("Échec de récupération de la page $page de tags.");
                    continue;
                }

                $tags = $tagsResponse->json();
                $fetchedThisPage = count($tags);
                $insertedThisPage = 0;

                foreach ($tags as $tagData) {
                    $date = Carbon::now();
                    $monthId = $date->format('m');
                    $year = $date->format('Y');

                    $month = InfosMonthYear::where('month_id', $monthId)->first();
                    $dateName = $month->month . ' ' . $year;

                    // Vérifier ou créer l'enregistrement InfosMonthYearTag
                    $monthTag = InfosMonthYearTag::firstOrCreate(
                        ['date_name' => $dateName],
                        ['deja_citer' => 0, 'user_id' => 1]
                    );

                    if ($monthTag->deja_citer === 0) {
                        $monthTag->update(['deja_citer' => 1]);
                    }

                    // Créer ou mettre à jour le Tag
                    $tag = Tag::updateOrCreate(
                        ['wp_tag_id' => intval($tagData['id'])],
                        [
                            'name' => $tagData['name'],
                            'slug' => $tagData['slug'],
                            'date_name' => $dateName,
                            'count_publications' => 0,
                            'date_publish' => now(),
                            'user_id' => 1
                        ]
                    );

                    if ($tag->wasRecentlyCreated) {
                        $insertedThisPage++;
                    }
                }

                $totalFetched += $fetchedThisPage;
                $totalInserted += $insertedThisPage;

                $this->command->info("Page $page de tags traitée : $fetchedThisPage récupérés, $insertedThisPage insérés. Total insérés : $totalInserted");
            }
        }

        $this->command->info("✅ Import des tags terminé : $totalFetched récupérés au total, $totalInserted insérés en base.");
    }
}
