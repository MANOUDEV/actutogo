<?php

namespace App\Http\Controllers\Api\Web\Backoffice\Admin\Publications\CRUD;

use App\Http\Controllers\Api\BaseController;
use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearPublication;
use App\Models\TypePublication;
use App\Models\Publication; 
use Carbon\Carbon;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AlertInfosAnnonceController extends BaseController
{
    public function str_replace_all($search, $replace, $subject) {

        return str_replace($search, $replace, $subject);

    }

    public function getExcerpt($limit = 50, $post_content)
    {
        $content = strip_tags($post_content); // Supprime les balises HTML

        $words = explode(' ', $content); // Sépare les mots

        if (count($words) > $limit) {

            $excerpt = implode(' ', array_slice($words, 0, $limit)) . '...';

        } else {

            $excerpt = $content;

        }

        return $excerpt;
    }

    public function stripTags($post_content){

        $content = strip_tags($post_content); 

        return $content;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */

    public function storeInfoAlertAnnonce(Request $request, $slug)
    {
        if (auth()->user()->role_id !== 1) {
            return $this->sendResponse(
                ['status' => 403],
                'Vous n\'êtes pas autorisé à accéder à ces données.'
            );
        }

        /**
         * =========================
         * 1️⃣ VALIDATION
         * =========================
         */
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'unique:publications'],
            'status' => ['required'],
            
        ], [
            'required' => 'Votre :attribute est obligatoire.',
            'title.unique' => 'Ce :attribute est déjà enregistré.',
            
        ], [
            'title' => 'titre de la publication',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(
                ['errors' => $validator->errors(), 'status' => 422],
                'Erreur de validation'
            );
        }

        /**
         * =========================
         * 2️⃣ TYPE DE PUBLICATION
         * =========================
         */
        $typePublication = TypePublication::where('slug', $slug)->first();

        if (!$typePublication) {
            return $this->sendResponse(
                ['status' => 422],
                'Aucun type de publication enregistré.'
            );
        }

        /**
         * =========================
         * 3️⃣ NORMALISATION DES DONNÉES
         * =========================
         */
        $datas = $request->all();

        $datas['user_id'] = auth()->user()->id;
        $datas['type_publication_id'] = $typePublication->id;
        $datas['type_publication_name'] = $typePublication->name;
        $datas['type_publication_slug'] = $typePublication->slug;
        $datas['is_article'] = false;
        $datas['presently'] = 1;

        if ($typePublication->id == 2) {
            $datas['is_alert_infos'] = true;
        } else if ($typePublication->id == 3){
            $datas['is_annonce'] = true;
        }

        /**
         * =========================
         * 4️⃣ GESTION DES DATES (UTC, SANS BUG)
         * =========================
         */
        $now = Carbon::now('UTC');

        if (!in_array($datas['status'], [0, 3])) {

            $datePublish = Carbon::parse($datas['date_publish'])->addMinutes(1)->utc();
           

            // Comparaison à la minute (logique humaine)
            if ($datePublish->startOfMinute()->lte($now->startOfMinute())) {
                return $this->sendResponse(
                    ['status' => 422],
                    'Veuillez bien revoir votre date de début de publication.'
                );
            }


            

            if($datas['date_publish_end']){


                $datePublishEnd = Carbon::parse($datas['date_publish_end'])->addMinutes(1)->utc();

                if ($datePublishEnd->startOfMinute()->lte($now->startOfMinute())) {
                    return $this->sendResponse(
                        ['status' => 422],
                        'Veuillez bien revoir votre date de fin de publication.'
                    );
                }

                if ($datePublishEnd->lte($datePublish)) {
                    return $this->sendResponse(
                        ['status' => 422],
                        'La date de fin ne peut pas être antérieure à la date de début.'
                    );
                }

            }
            

            $datas['date_publish'] = $datePublish;
            $datas['date_publish_end'] = $datas['date_publish_end'] ? $datePublishEnd : null;
        }

        /**
         * =========================
         * 5️⃣ TRAITEMENT TEXTE
         * =========================
         */
        $datas['title'] = $this->str_replace_all(
            '<p>',
            '<div><a href="#" class="text-reset btn-link">',
            $this->str_replace_all('</p>', '</a></div>', $datas['title'])
        );

        $datas['slug'] = Str::slug($this->stripTags($datas['title']));
        $datas['title_truncate'] = $this->getExcerpt(10, $datas['title']);

        /**
         * =========================
         * 6️⃣ DATE NAME (MOIS / ANNÉE)
         * =========================
         */
        $monthId = $now->format('m');
        $year = $now->format('Y');

        $mois = InfosMonthYear::where('month_id', $monthId)->first();
        $dateName = $mois->month . ' ' . $year;

        $datas['date_name'] = $dateName;

        InfosMonthYearPublication::firstOrCreate([
            'date_name' => $dateName
        ]);

        /**
         * =========================
         * 7️⃣ CRÉATION
         * =========================
         */
        $typePublication->increment('count_publications');

        $publication = Publication::create($datas);

        if (!$publication) {
            return $this->sendResponse(
                ['status' => 422],
                'Impossible d\'enregistrer cette publication.'
            );
        }

        return $this->sendResponse(
            ['publicationCreateData' => $publication, 'status' => 200],
            'Publication enregistrée.'
        );
    }

}