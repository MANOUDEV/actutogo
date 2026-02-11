<?php

namespace App\Http\Controllers\Api\Web\Backoffice\Admin\Publications\CRUD;

use App\Http\Controllers\Api\BaseController;
use App\Models\File;
use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearPublication;
use App\Models\TypePublication;
use App\Models\Publication;
use App\Models\PublicationFile;
use Carbon\Carbon;
use App\Models\PushSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PublicitesController extends BaseController
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

    public function getFiles(Request $request){

        $filesCount = File::orderBy('count', 'desc')->count();

        if($filesCount === 0){

            return $this->sendResponse(['status' => 401, 'filesCount'=> $filesCount], 'Aucun fichier n\'est enregistré.');

        }else{

            $files = File::query();

            if($request->input('search')){

                $files = File::where('files.file_name', 'like', '%'. $request->input('search') . '%')
                ->where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                }) 
                ->orderBy('files.count_publications', 'desc')
                ->paginate(6);

                $filesCount = File::where('files.file_name', 'like', '%'. $request->input('search') . '%')
                ->where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                }) 
                ->orderBy('files.count_publications', 'desc')
                ->count();

                if($filesCount === 0){

                    return $this->sendResponse(['status' => 401, 'filesCount'=> $filesCount], 'Aucun fichier ne correspond à votre recherche.');
    
                }else{

                    return $this->sendResponse(['files' => $files, 'filesCount'=> $filesCount,'status' => 200], 'Liste des fichiers à la newsletter .');

                }

            }else{

                $files = File::where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                })->orderBy('files.count_publications', 'desc')
                ->paginate(6);

                $filesCount = File::where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                })->count();

                if($filesCount === 0){

                    return $this->sendResponse(['status' => 401, 'filesCount'=> $filesCount], 'Aucun fichier n\'est enregistré.');
    
                }else{

                    return $this->sendResponse(['files' => $files, 'filesCount'=> $filesCount,'status' => 200], 'Liste des fichiers à la newsletter .');

                }

            }

        }
    }

    public function searchByTypeFiles($slug, $status){

        $filesCount = File::orderBy('count', 'desc')->count();

        if($filesCount === 0){

            return $this->sendResponse(['status' => 401, 'filesCount'=> $filesCount], 'Aucun fichier n\'est enregistré.');

        }else{

            $files = File::query();

            if($status == 3){

                $files = File::where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                })->orderBy('files.count_publications', 'desc')
                ->paginate(6);

                $filesCount = File::where(function ($query) {
                    $query->where("type_file_id", 1)->orWhere("type_file_id", 2);
                })->count();

                if($filesCount === 0){

                    return $this->sendResponse(['status' => 401, 'filesCount'=> $status], 'Aucun fichier n\'est enregistré.');
    
                }else{

                    return $this->sendResponse(['files' => $files, 'filesCount'=> $status,'status' => 200], 'Liste des fichiers à la newsletter .');

                }

            }else{
                
                $files = File::where('files.type_file_id', $status)
                ->orderBy('files.count_publications', 'desc')
                ->paginate(6);

                $filesCount = File::where('files.type_file_id', $status)
                ->orderBy('files.count_publications', 'desc')
                ->count();

                if($filesCount === 0){

                    return $this->sendResponse(['status' => 401, 'filesCount'=> $status], 'Aucun fichier ne correspond à votre recherche.');
    
                }else{

                    return $this->sendResponse(['files' => $files, 'filesCount'=> $status,'status' => 200], 'Liste des fichiers à la newsletter .');

                }

            }

        }

    } 

/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
*/ 

public function storePublicites(Request $request, $slug)
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
        $datas['entete_site'] = ($datas['entete_site'] === 'true') ? true : false;
        $datas['entre_publications'] = ($datas['entre_publications'] === 'true') ? true : false;
        $datas['toutes_les_pages'] = ($datas['toutes_les_pages'] === 'true') ? true : false;
        $datas['dans_les_contenus'] = ($datas['dans_les_contenus'] === 'true') ? true : false;
        $datas['is_article'] = ($datas['is_article'] === 'true') ? true : false;

        if ($typePublication->id == 5) {
            $datas['is_pub'] = true;
        }  

        /**
         * =========================
         * 4️⃣ GESTION DES DATES (UTC, SANS BUG)
         * =========================
         */
        $now = Carbon::now('UTC');

        if (!in_array($datas['status'], [0, 3])) {

            // 🔹 Date de début (obligatoire ici)
            $datePublish = Carbon::parse($datas['date_publish'])
                ->addMinutes(1)
                ->utc();

            if ($datePublish->startOfMinute()->lte($now->startOfMinute())) {
                return $this->sendResponse(
                    ['status' => 422],
                    'Veuillez bien revoir votre date de début de publication.'
                );
            }

            // 🔹 Date de fin (OPTIONNELLE)
            if (!empty($datas['date_publish_end'])) {

                $datePublishEnd = Carbon::parse($datas['date_publish_end'])
                    ->addMinutes(1)
                    ->utc();

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

                $datas['date_publish_end'] = $datePublishEnd;
            } else {
                // 🔹 IMPORTANT : on force NULL
                $datas['date_publish_end'] = null;
            }

            $datas['date_publish'] = $datePublish;
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

        dd($request->file('uploads'));
        
        if ($request->hasFile('uploads')) {

            $year = date('Y');
            $month = date('m');

            $basePath = "publicites/$year/$month";

            // Crée le dossier si nécessaire
            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            foreach ($request->file('uploads') as $file) {
                if ($file && $file->isValid()) {
                    $typeFileId = str_contains($file->getMimeType(), 'video') ? 2 : 1;

                    // Nettoie le nom du fichier
                    $originalName = $file->getClientOriginalName();
                    $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $originalName);

                    // Stocke le fichier
                    $path = $file->storeAs($basePath, $fileName, 'public');

                    // Sauvegarde en base
                    $fileModel = File::create([
                        'file_name' => $originalName,
                        'file_url' => $path,
                        'type_file_id' => $typeFileId,
                        'user_id' => auth()->id(),
                    ]);

                    PublicationFile::create([
                        'publication_id' => $publication->id,
                        'file_id' => $fileModel->id,
                    ]);
                }
            }
        }

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
