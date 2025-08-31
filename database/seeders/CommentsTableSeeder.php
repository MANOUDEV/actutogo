<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\Comment;
use App\Models\Commentator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $baseUrl = "https://www.togoactualite.com/wp-json/wp/v2/comments";
        $perPage = 100;
        $batchSize = 5; // Nombre de pages à traiter par lot

        // Récupérer la première page pour connaître le nombre total de pages
        $response = Http::get("$baseUrl?per_page=$perPage&page=1");

        if (!$response->successful()) {
            $this->command->error('Impossible de récupérer les commentaires depuis WordPress.');
            return;
        }

        $totalPages = intval($response->header('x-wp-totalpages', 1));
        $this->command->info("Total pages de commentaires à traiter : $totalPages");

        $pages = range(1, $totalPages);

        // Traiter les pages par lots
        foreach (array_chunk($pages, $batchSize) as $batch) {
            foreach ($batch as $page) {
                $response = Http::get("$baseUrl?per_page=$perPage&page=$page");

                if (!$response->successful()) {
                    $this->command->warn("Échec de récupération de la page $page. Passage à la suivante...");
                    continue;
                }

                $comments = $response->json();

                foreach ($comments as $value) {
                    // Vérifier si le commentateur existe déjà
                    $commentator = Commentator::firstOrCreate(
                        ['nom_complet' => $value['author_name']],
                        [
                            'slug' => Str::slug($value['author_name']),
                            'date_publish' => date('Y-m-d H:i:s', strtotime($value['date'])),
                            'count_comments' => 0,
                        ]
                    );

                    // Récupérer l'article lié
                    $articles = Publication::where('wp_article_id', $value['post'])->get();

                    foreach ($articles as $article) {
                        // Vérifier si le commentaire existe déjà pour éviter les doublons
                        $comment = Comment::firstOrCreate(
                            [
                                'publication_id' => $article->id,
                                'commentator_id' => $commentator->id,
                                'content' => $value['content']['rendered']
                            ],
                            [
                                'date_publish' => date('Y-m-d H:i:s', strtotime($value['date']))
                            ]
                        );

                        if ($comment->wasRecentlyCreated) {
                            // Mettre à jour les compteurs
                            $commentator->increment('count_comments');
                            $article->increment('comment_count');
                        }
                    }
                }

                $this->command->info("Page $page traitée.");
            }
        }

        $this->command->info('Import des commentaires terminé avec succès !');
    }
}
