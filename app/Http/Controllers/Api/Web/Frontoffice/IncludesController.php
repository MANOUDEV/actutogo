<?php
namespace App\Http\Controllers\Api\Web\Frontoffice;
use App\Http\Controllers\Api\BaseController;
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
use App\Models\VisitorMatricule; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class IncludesController extends BaseController
{

    public function str_replace_all($search, $replace, $subject) {

        return str_replace($search, $replace, $subject);

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