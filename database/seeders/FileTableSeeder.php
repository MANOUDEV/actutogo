<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\TypeFile;
use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearFile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FileTableSeeder extends Seeder
{
    public function run(): void
    {
        $perPage = 100;
        $batchSize = 5; // Nombre de pages à traiter par lot

        $typeFiles = TypeFile::all();

        foreach ($typeFiles as $typeFile) {
            $response = Http::get("https://www.togoactualite.com/wp-json/wp/v2/media?media_type=$typeFile->slug_wp&per_page=$perPage");

            if (!$response->successful()) {
                $this->command->warn("Impossible de récupérer les médias pour le type {$typeFile->name}");
                continue;
            }

            $totalPages = intval($response->header('x-wp-totalpages', 1));
            $totalFiles = intval($response->header('x-wp-total', 0));

            $typeFile->count_files = $totalFiles;
            $typeFile->update();

            $this->command->info("Type {$typeFile->name}: $totalFiles fichiers sur $totalPages pages.");

            $pages = range(1, $totalPages);

            foreach (array_chunk($pages, $batchSize) as $batch) {
                foreach ($batch as $page) {
                    $mediasResponse = Http::get("https://www.togoactualite.com/wp-json/wp/v2/media?media_type={$typeFile->slug_wp}&per_page=$perPage&page=$page");

                    if (!$mediasResponse->successful()) {
                        $this->command->warn("Échec de récupération de la page $page pour le type {$typeFile->name}");
                        continue;
                    }

                    $medias = $mediasResponse->json();

                    foreach ($medias as $media) {
                        if (!isset($media['source_url'])) {
                            continue;
                        }

                        $link = $media['source_url'];
                        $date = Carbon::parse($media["modified_gmt"]);
                        $monthId = $date->format('m');
                        $year = $date->format('Y');

                        $month = InfosMonthYear::where('month_id', $monthId)->first();
                        $dateName = $month->month . ' ' . $year;

                        // Vérifier ou créer l'enregistrement InfosMonthYearFile
                        InfosMonthYearFile::firstOrCreate(['date_name' => $dateName]);

                        // Créer ou mettre à jour le fichier
                        File::updateOrCreate(
                            ['wp_file' => $media['source_url']],
                            [
                                'file_url' => str_replace(
                                    'https://togoactualite.com/wp-content/uploads',
                                    'https://togoactualite.com/wp-content/uploads',
                                    $link
                                ),
                                'date_name' => $dateName,
                                'file_name' => $media['title']['rendered'],
                                'caption' => !empty($media['caption']['rendered']) ? strip_tags($media['caption']['rendered']) : null,
                                'file_slug' => Str::slug($media['title']['rendered']),
                                'date_publish' => $media["modified_gmt"],
                                'type_file_id' => $typeFile->id,
                                'user_id' => 1,
                            ]
                        );
                    }

                    $this->command->info("Page $page pour le type {$typeFile->name} traitée.");
                }
            }
        }

        $this->command->info('Import des fichiers terminé avec succès !');
    }
}