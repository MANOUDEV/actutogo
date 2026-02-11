<?php
namespace App\Http\Controllers\Api\Web\Frontoffice;
use App\Http\Controllers\Api\BaseController;
use App\Models\Author;
use App\Models\Category;
use App\Models\Message;
use App\Models\NewsLetter;
use App\Models\Tag;
use App\Models\Publication;
use App\Models\SenderMessage;
use App\Models\PublicationViews;
use App\Models\PublicationLikes;
use App\Models\Comment;
use App\Models\Commentator;
use App\Models\File;
use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearFile;
use App\Models\InfosMonthYearPublication;
use App\Models\InfosMonthYearTag;
use App\Models\PublicationFile;
use App\Models\PublicationTag;
use App\Models\TypeFile;
use App\Models\TypePublication;
use App\Models\VisitorMatricule;
use Carbon\Carbon;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class IncludesController extends BaseController
{

    public function str_replace_all($search, $replace, $subject) {

        return str_replace($search, $replace, $subject);

    }


    
    public function transferRequestData(){

        try {
            $response = Http::get("http://www.togoactualite.com/wp-json/wp/v2/categories?per_page=10&page=1");

            if (!$response->successful()) {
                return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');
            }

            $categories = $response->json();
            
            foreach ($categories as $categoryData) {

                $category = Category::where('wp_category_id', intval($categoryData['id']))->count();

                if($category == 0){

                    $result = Category::Create( 
                        [
                            'name' => $categoryData['name'],
                            'slug' => $categoryData['slug'],
                            'count_publications' => 0,
                            'date_publish' => now(),
                            'user_id' => 1
                        ]
                    );

                }
                
            }

            
        } catch (\Exception $e) {
           return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');
        }
             
        
         try {
            $response = Http::get("http://www.togoactualite.com/wp-json/wp/v2/tags?per_page=30&page=1");

            if (!$response->successful()) {
                return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');
            }

            $tags = $response->json();
            
            foreach ($tags as $tagData) {

                $tag = Tag::where('wp_tag_id', intval($tagData['id']))->count();

                if($tag == 0){

                    $date = Carbon::now();
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

                    $result = Tag::Create( 
                        [
                            'name' => $tagData['name'],
                            'slug' => $tagData['slug'],
                            'count_publications' => 0,
                            'date_publish' => now(),
                            'user_id' => 1
                        ]
                    );

                }
                
            }

            
        } catch (\Exception $e) {
           return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');
        }


        $type_files = TypeFile::get();

        $medias_count_by_type = [];

        foreach($type_files as $type_file){

            $response = Http::get("https://www.togoactualite.com/wp-json/wp/v2/media?media_type=$type_file->slug_wp&per_page=100");
            
            $medias_count_by_type[] = [
                'x-wp-totalpages' => $response->getHeader('x-wp-totalpages')[0],
                'x-wp-total' => $response->getHeader('x-wp-total')[0],
                'type_file_id' => $type_file->id,
                'type_file_name' => $type_file->name,
                'type_file_slug_wp' => $type_file->slug_wp,
            ];

            $type_file->count_files = $response->getHeader('x-wp-total')[0];

            $type_file->update();
        
        }

        
        foreach($medias_count_by_type as $result){

           
            $media_type = $result['type_file_slug_wp'];

            $page = $result['x-wp-totalpages'];

            $type_file_id = $result['type_file_id'];

          
            $medias = Http::get('https://www.togoactualite.com/wp-json/wp/v2/media?media_type='.$media_type.'&page=1&per_page=100')->json();
        
            foreach( $medias as $media ){ 


                
                if(isset($media['source_url'])){

                    $file = File::where('wp_file', $media['source_url'])->count();

                    if($file == 0){

                        $link = $media['source_url']; 

                        $date = Carbon::parse($media["modified_gmt"]);

                        $mois_id = $date->format('m');

                        $year = $date->format('Y');

                        $mois = InfosMonthYear::where('month_id', $mois_id)->first();

                        $date_name = $mois->month.' '.$year;

                        $verify_date_name = InfosMonthYearFile::where('date_name', $date_name)->first();

                        if(!$verify_date_name){

                            InfosMonthYearFile::create(['date_name' => $date_name]);

                        }

                       
                        $fichier_original = File::create([
                            'file_url' => str_replace('https://togoactualite.com/wp-content/uploads', 'https://togoactualite.com/wp-content/uploads', $link),
                            'date_name' => $date_name,
                            'file_name' => $media['title']['rendered'],
                            'caption' => $media['caption']['rendered'] ? strip_tags($media['caption']['rendered']) : null,
                            'file_slug' => \Illuminate\Support\Str::slug($media['title']['rendered']),
                            'wp_file' => $media['source_url'],
                            'date_publish' => $media["modified_gmt"],
                            'type_file_id' => $type_file_id,
                            'user_id' => 1
                        ]);


                    }

                   
                }
            }

            
        }


         

            $posts = Http::get("http://www.togoactualite.com/wp-json/wp/v2/posts?page=1&per_page=10")->json(); 

        foreach ($posts as $value) {

            $author = Author::where('wp_author_id', $value['author'])->first();
            $typePublication = TypePublication::where('slug', 'articles')->first();

            // ✅ Identification correcte par ID WordPress
            $publication = Publication::where('title', $value['title']['rendered'])->first();

            if (!$author || !$typePublication) continue;

                // Incrément des compteurs
            $author->increment('count_publications');
            $typePublication->increment('count_publications');

            // Gestion de l'image de couverture
            $imageCoverUrl = null;
            $fileId = null;

            if($publication){

                continue;

            }else{

                if (isset($value['yoast_head_json']['schema']['@graph'][0]['thumbnailUrl'])) {
                    $url = str_replace(
                        'https://togoactualite.com/wp-content/uploads',
                        'http://togoactualite.com/wp-content/uploads',
                        $value['yoast_head_json']['schema']['@graph'][0]['thumbnailUrl']
                    );
                    $imageCoverUrl = $url;

                    $file = File::firstOrCreate(
                        ['file_url' => $url],
                        [
                            'date_name' => Carbon::parse($value['date'])->format('F Y'),
                            'date_publish' => $value['date'],
                            'type_file_id' => 1,
                            'user_id' => 1,
                        ]
                    );
                    $file->increment('count_publications');
                    $fileId = $file->id;
                }

                // Récupération des catégories
                $categories = collect();
                foreach ($value['categories'] as $wpCategoryId) {
                    $category = Category::where('wp_category_id', $wpCategoryId)->first();
                    if ($category) {
                        $category->increment('count_publications');
                        $categories->push($category);
                    }
                }

                // Récupération des tags
                $tags = collect();
                foreach ($value['tags'] as $wpTagId) {
                    $tag = Tag::where('wp_tag_id', $wpTagId)->first();
                    if ($tag) {
                        $tag->increment('count_publications');
                        $tags->push($tag);
                    }
                }

                // Date name pour InfosMonthYearPublication
                $date = Carbon::parse($value['date']);
                $month = InfosMonthYear::where('month_id', $date->format('m'))->first();
                $dateName = $month ? $month->month . ' ' . $date->format('Y') : $date->format('F Y');
                InfosMonthYearPublication::firstOrCreate(['date_name' => $dateName]);

                // ---- Création de la publication par catégorie ----
                if ($categories->isEmpty()) {
                    $publication = Publication::create([
                        'title' => $value['title']['rendered'],
                        'title_truncate' => Str::words(strip_tags($value['title']['rendered']), 10, ' ...'),
                        'slug' => Str::slug(strip_tags($value['title']['rendered'])),
                        'date_name' => $dateName,
                        'deja_citer' => 0,
                        'image_cover_url' => $imageCoverUrl,
                        'content' => $value['content']['rendered'],
                        'truncate_content' => Str::words(strip_tags($value['excerpt']['rendered']), 20, ' ...'),
                        'truncate_content_max' => $value['excerpt']['rendered'],
                        'status' => $value['status'] === 'publish' ? 1 : 0,
                        'comment_status' => $value['comment_status'] === 'open' ? 1 : 0,
                        'views_count' => rand(351, 2564),
                        'likes_count' => rand(123, 554),
                        'shares_count' => 0,
                        'comment_count' => 0,
                        'date_publish' => $value['date'],
                        'author_id' => $author->id,
                        'author_slug' => $author->slug,
                        'author_name' => $author->authorName,
                        'type_publication_id' => $typePublication->id,
                        'type_publication_name' => $typePublication->name,
                        'type_publication_slug' => $typePublication->slug,
                        'category_id' => null,
                        'category_name' => null,
                        'category_slug' => null,
                        'user_id' => 1,
                        'source' => 'Togoactualité',
                        'wp_article_id' => $value['id'],
                    ]);

                    // Attacher tags
                    foreach ($tags as $tag) {
                        PublicationTag::firstOrCreate(
                            ['publication_id' => $publication->id, 'tag_id' => $tag->id],
                            ['date_publish' => $value['date']]
                        );
                    }

                    // Attacher fichier
                    if ($fileId) {
                        PublicationFile::firstOrCreate(
                            ['publication_id' => $publication->id, 'file_id' => $fileId],
                            ['date_publish' => $value['date']]
                        );
                    }

                
                } else {
                    foreach ($categories as $index => $category) {

                        $deja_citerH = Publication::where('title', $value['title']['rendered'])->where('deja_citer', 0)->first();

                        if($deja_citerH){
                            $deja_citer = 1;
                        }else{
                            $deja_citer = 0;
                        }

                        $publication = Publication::create([
                            'title' => $value['title']['rendered'],
                            'title_truncate' => Str::words(strip_tags($value['title']['rendered']), 10, ' ...'),
                            'slug' => Str::slug(strip_tags($value['title']['rendered'])) . ($index > 0 ? "-{$category->slug}" : ""),
                            'date_name' => $dateName,
                            'deja_citer' => $deja_citer,
                            'image_cover_url' => $imageCoverUrl,
                            'content' => $value['content']['rendered'],
                            'truncate_content' => Str::words(strip_tags($value['excerpt']['rendered']), 20, ' ...'),
                            'truncate_content_max' => $value['excerpt']['rendered'],
                            'status' => $value['status'] === 'publish' ? 1 : 0,
                            'comment_status' => $value['comment_status'] === 'open' ? 1 : 0,
                            'views_count' => rand(351, 2564),
                            'likes_count' => rand(123, 554),
                            'shares_count' => 0,
                            'comment_count' => 0,
                            'date_publish' => $value['date'],
                            'author_id' => $author->id,
                            'author_slug' => $author->slug,
                            'author_name' => $author->authorName,
                            'type_publication_id' => $typePublication->id,
                            'type_publication_name' => $typePublication->name,
                            'type_publication_slug' => $typePublication->slug,
                            'category_id' => $category->id,
                            'category_name' => $category->name,
                            'category_slug' => $category->slug,
                            'user_id' => 1,
                            'source' => 'Togoactualité',
                            'wp_article_id' => $value['id'],
                        ]);

                        foreach ($tags as $tag) {
                            PublicationTag::firstOrCreate(
                                ['publication_id' => $publication->id, 'tag_id' => $tag->id],
                                ['date_publish' => $value['date']]
                            );
                        }

                        if ($fileId) {
                            PublicationFile::firstOrCreate(
                                ['publication_id' => $publication->id, 'file_id' => $fileId],
                                ['date_publish' => $value['date']]
                            );
                        }

                        
                    }
                }


            }
            
                
        }


            return $this->sendResponse(['status' => 200], 'Réussie.');

    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function economieRequestData(){

        $economieDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 11)->count();

        if($economieDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Acune publication sur économie n\'est publiée.');

        }else if ($economieDataCount !== 0){

            $economieData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 12);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

             $diaspora = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 10);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

             $rubriques = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 28);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

             $societe = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 30);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'economieData' =>  $economieData,
                'societe' =>  $societe,
                'diaspora' =>  $diaspora,
                'rubriques' =>  $rubriques,
                'status' => 200
            ], 'Listes de publications de économie publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }



    }

   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function internationalRequestData(){

        $internationalDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 20)->orWhere("category_id", 23)->count();

        if($internationalDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Acune publication sur international n\'est publiée.');

        }else if ($internationalDataCount !== 0){

            $internationalData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 20)->orWhere("category_id", 23);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'internationalData' =>  $internationalData,
                'status' => 200
            ], 'Listes de publications de international publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }



    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function politiqueRequestData(){

        $politiqueDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 28)->count();

        if($politiqueDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Acune publication sur politique n\'est publiée.');

        }else if ($politiqueDataCount !== 0){

            $politiqueData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 28);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'politiqueData' =>  $politiqueData,
                'status' => 200
            ], 'Listes de publications de politique publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newsletterStoreRequest(Request $request )
    {

        $datas = $request->all();

        $validator = Validator::make($datas, [
            'email' => ['required','string', 'email', 'max:255', 'unique:news_letters'],
        ],[
            'required' => 'Votre :attribute est obligatoire.',
            'email.unique' => 'Cet :attribute est déjà enregistré.'
        ], [
            'email' => 'email',
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }

        $datas['slug'] = Str::slug($datas['email']);

        $message = NewsLetter::create($datas);

        if($message){

            return $this->sendResponse(['message' => $message, 'status' => 200], 'Email enregistré.');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible d\'enregistrer cet email.');

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tagsRequestData()
    {
       $tags = Tag::orderBy('tags.count_publications', 'desc')->take(6)->get();

       return $this->sendResponse(['tagsPopularsData' => $tags, 'status' => 200], 'les mots clés populaires');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryRequestData(){

        if(Category::count() == 0){

            return $this->sendResponse(['FirstSectionCategory' => [], 'TwoSectionCategory' => [] ,'status' => 200], 'les catégories populaires');

        }else{

            $category = Category::where('id', '!=', 34 )
            ->where('id', '!=', 27 )
            ->where('id', '!=', 27 )
            ->where('id', '!=', 25 )
            ->where('id', '!=', 5 )
            ->where('id', '!=', 10 )
            ->where('id', '!=', 11 )
            ->where('id', '!=', 9 )
            ->where('id', '!=', 23 )
            ->where('id', '!=', 3 )
            ->where('id', '!=', 15 )
            ->where('id', '!=', 16 )
            ->where('id', '!=', 19 )
            ->where('id', '!=', 30 )
            ->where('id', '!=', 31 )
            ->take(14)->get();

            $FirstSectionCategory = [$category[12], $category[1], $category[2], $category[3], $category[4], $category[5], $category[6]];

            $TwoSectionCategory = [$category[7], $category[8], $category[9],$category[11], $category[13]];

            return $this->sendResponse(['FirstSectionCategory' => $FirstSectionCategory, 'TwoSectionCategory' => $TwoSectionCategory ,'status' => 200], 'les catégories populaires');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicationsRequestData(){

        $publications = Publication::where('status', 1)->where("type_publication_id", 1)->whereDate('date_publish', '>', '2022-12-31')->orderBy('views_count', 'desc')->take(2)->get();

        return $this->sendResponse(['publicationsPopularsData' => $publications, 'status' => 200], 'les publications populaires');

    }
    
    public function submitContact(Request $request){

        $datas = $request->all();

        $validator = Validator::make($datas, [
            'email' => ['required', 'email', 'max:255'],
            'nom_complet' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ],[
            'required' => 'Votre :attribute est obligatoire.',
        ], [
            'email' => 'email',
            'nom_complet' => 'nom complet',
            'content' => 'contenu',
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }

        $datas['slug'] = Str::slug($datas['nom_complet']);

        $check_sender_message = SenderMessage::where('nom_complet', $datas['nom_complet'])->where('email', $datas['email'])->first();

        if($check_sender_message){

            $create_contact = Message::create([
                'sender_message_id' => $check_sender_message->id,
                'content' => $datas['content'],
                'subject' => $datas['subject'] ? $datas['subject'] : null,
            ]);

            $check_sender_message->count_messages += 1;

            $check_sender_message->update();

        }else{

            $create_sender_contact = SenderMessage::create([
                'nom_complet' => $datas['nom_complet'],
                'slug' => $datas['slug'],
                'email' => $datas['email'],
            ]);

            $create_contact = Message::create([
                'sender_message_id' => $create_sender_contact->id,
                'content' => $datas['content'],
                'subject' => $datas['subject'] ? $datas['subject'] : null,
            ]);

            $create_sender_contact->count_messages += 1;

            $create_sender_contact->update();

        }

        return $this->sendResponse(['result' => $create_contact , 'status' => 200], 'Message envoyé.');

    }
  
    public function generateVisitorMatricule(){

        $matricule_visitors_count = VisitorMatricule::count();

        if($matricule_visitors_count == 0){

            $visitor_matricule_create = VisitorMatricule::create([
                'matricule_reference' => $this->str_replace_all("/", "", Hash::make(time().'-1'))
            ]);

            return $this->sendResponse(['visitor_matricule_create' => $visitor_matricule_create, 'status' => 200], 'Matricule de référence pour un visiteur géneré.');

        }else{

            $matricule_visitor_latest = VisitorMatricule::latest()->first();

            if($matricule_visitor_latest){

                $visitor_matricule_create = VisitorMatricule::create([
                    'matricule_reference' => $this->str_replace_all("/", "", Hash::make(time().'-'.$matricule_visitor_latest->id + 1))
                ]);

                return $this->sendResponse(['visitor_matricule_create' => $visitor_matricule_create, 'status' => 200], 'Matricule de référence pour un visiteur géneré.');

            }else{
                return $this->sendResponse(['result' => 'Error', 'status' => 401], 'Impossible de générer un matricule de référence pour ce visiteur.');
            }

        }

    }

    public function checkVisitorMatricule($matricule) {

        $matricule_visitors_count = VisitorMatricule::count();

        if($matricule_visitors_count !== 0){

            $check_matricule_reference = VisitorMatricule::where('matricule_reference', $matricule)->first();

            if($check_matricule_reference){

                return $this->sendResponse(['check_matricule_reference' => $check_matricule_reference, 'status' => 200], 'Ce matricule de réference existe déjà.');

            }else{

                return $this->sendResponse(['result' => 'Error', 'status' => 401], 'Ce matricule de réference n\'existe pas encore.');

            }
        }else{

            return $this->sendResponse(['result' => 'Error', 'status' => 401], 'Aucun matricule de réference n\'existe encore.');

        }

    }

     public function articleState($slug, $mtr){ 

        $article = Publication::where('status', 1)->where('slug', $slug)->where('deja_citer', 0)->first();

        $user_views = VisitorMatricule::where('matricule_reference', $mtr)->first();
 
        if($article && $user_views){

            $check_views = PublicationViews::where('publication_id', $article->id)->where('visitor_matricule_id', $user_views->id)->first();

            if($check_views){

                $check_likes = PublicationLikes::where('publication_id', $article->id)->where('visitor_matricule_id', $user_views->id)->first();

                if($check_likes){

                    return $this->sendResponse(['article' => $article, 'status' => 202], 'Les statistiques sur un article.');

                }else{

                    return $this->sendResponse(['article' => $article, 'status' => 201], 'Les statistiques sur un article.');

                }

            }else{

                $articles = Publication::where('status', 1)->where('slug', $slug)->where('deja_citer', 1)->get();

                foreach($articles as $result){

                    $views_create = PublicationViews::create([
                        'visitor_matricule_id' => $user_views->id,
                        'publication_id' => $result->id
                    ]);

                    $result->views_count += 1;

                    $result->update();

                }

                $view_create = PublicationViews::create([
                    'visitor_matricule_id' => $user_views->id,
                    'publication_id' => $article->id
                ]);

                $article->views_count += 1;

                $article->update();

                return $this->sendResponse(['article' => $article, 'status' => 200], 'Les statistiques sur un article.');
            }

        }else{

            return $this->sendResponse(['article' => null, 'status' => 422], 'Impossible de chager les données.');

        }

    }

     public function actionsLikes($slug, $mtr){
 
        $article = Publication::where('status', 1)->where('slug', $slug)->where('deja_citer', 0)->first();

        $user = VisitorMatricule::where('matricule_reference', $mtr)->first();

        if($article && $user){

            $check_likes = PublicationLikes::where('publication_id', $article->id)->where('visitor_matricule_id', $user->id)->first();

            if($check_likes){

                $articles = Publication::where('status', 1)->where('slug', $slug)->where('deja_citer', 1)->get();

                foreach($articles as $articleH){

                    $check_likess = PublicationLikes::where('publication_id', $articleH->id)->where('visitor_matricule_id', $user->id)->first();

                    foreach($check_likess as $result){ 

                        $result->delete();
        
                        $articleH->likes_count -= 1;
        
                        $articleH->update();

                    }
                }

               
                $check_likes->delete();

                $article->likes_count -= 1;
        
                $article->update();

                return $this->sendResponse(['article' => $article, 'status' => 202], 'null');

            }else{

                $articles = Publication::where('status', 1)->where('slug', $slug)->where('deja_citer', 1)->get();

                foreach($articles as $articleH){

                    $likes_create = PublicationLikes::create([
                        'visitor_matricule_id' => $user->id, 
                        'publication_id' => $articleH->id,
                        'date_publish'=>now()
                    ]);

                    $articleH->likes_count += 1;

                    $articleH->update();
                
                }

                $likes_create = PublicationLikes::create([
                    'visitor_matricule_id' => $user->id, 
                    'publication_id' => $article->id,
                    'date_publish'=>now()
                ]);

                $article->likes_count += 1;

                $article->update();

                return $this->sendResponse(['article' => $article, 'status' => 201], 'Merci d\'avoir aimé cette publication');

            }
        }

    }

    public function articleComments($slug, $mtr){

        $article = Publication::where('status', 1)->where('slug', $slug)->first();

        $user = VisitorMatricule::where('matricule_reference', $mtr)->first();

        if($article && $user){

            $comments = Comment::select(array("comments.id", "comments.content", "comments.date_publish", "commentators.username", "commentators.nom_complet", "comments.commentator_id"))
            ->where('comments.status', 1)
            ->where('comments.publication_id', $article->id)
            ->leftJoin("commentators", "commentators.id", "=", "comments.commentator_id")
            ->orderBy('comments.date_publish', 'desc')
            ->get();

            return $this->sendResponse(['article' => $article, 'comments' => $comments, 'status' => 200], 'Les commentaires sur un article.');

        }else{

            return $this->sendResponse(['article' => null, 'status' => 422], 'Impossible de chager les données.');

        }

    }

    public function submitComment(Request $request, $slug, $mtr){

        $datas = $request->all();

        $validator = Validator::make($datas, [
            'email' => ['required', 'email', 'max:255'],
            'nom_complet' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ],[
            'required' => 'Votre :attribute est obligatoire.',
        ], [
            'email' => 'email',
            'nom_complet' => 'nom complet',
            'content' => 'commentaire',
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }

        $datas['slug'] = Str::slug($datas['nom_complet']);

        $check_sender_comment = Commentator::where('nom_complet', $datas['nom_complet'])->where('email', $datas['email'])->first();
 
        $article = Publication::where('status', 1)->where('slug', $slug)->first();

        $user = VisitorMatricule::where('matricule_reference', $mtr)->first();

        if($article){

            if($user){

                if($check_sender_comment){

                    $articles = Publication::where('status', 1)->where('slug', $slug)->get();

                    foreach($articles as $articleH){

                        $create_comment = Comment::create([
                            'commentator_id' => $check_sender_comment->id,
                            'content' => $datas['content'],
                            'publication_id' => $articleH->id,
                            'date_publish'=>now()
                        ]);

                        $articleH->comment_count += 1;

                        $articleH->update();

                    }

                    $check_sender_comment->count_comments += 1;

                    $check_sender_comment->update();

                }else{

                    $articles = Publication::where('status', 1)->where('slug', $slug)->get();

                    $create_sender_comment = Commentator::create([
                        'nom_complet' =>  $datas['nom_complet'], 
                        'email' =>  $datas['email'], 
                        'slug'=> $datas['slug'],
                        'date_publish'=>now()
                    ]);

                    foreach($articles as $articleH){

                        $create_comment = Comment::create([
                            'commentator_id' => $create_sender_comment->id,
                            'content' => $datas['content'],
                            'publication_id' => $articleH->id,
                            'date_publish'=>now()
                        ]);

                        $articleH->comment_count += 1;

                        $articleH->update();
            
                    }

                    $create_sender_comment->count_comments += 1;
            
                    $create_sender_comment->update();

                }

            }else{

                return $this->sendResponse(['result' => 'Error', 'status' => 422], 'Ce matricule de réference n\'existe pas encore.');

            }

        }else{

            return $this->sendResponse(['result' => 'Error', 'status' => 422], 'Cette publication n\'existe pas encore.');

        }

        return $this->sendResponse(['result' => $create_comment , 'status' => 200], 'Commentaire envoyé.');

    }
}