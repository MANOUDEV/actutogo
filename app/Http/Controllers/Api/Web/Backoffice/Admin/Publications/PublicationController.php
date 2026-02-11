<?php

namespace App\Http\Controllers\Api\Web\Backoffice\Admin\Publications;

use App\Http\Controllers\Api\BaseController;
use App\Models\Author;
use App\Models\Category;
use App\Models\File; 
use App\Models\TypePublication;
use App\Models\Publication; 
use App\Models\Tag; 
use Illuminate\Http\Request; 
class PublicationController extends BaseController
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
                ->paginate(9);

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
                ->paginate(9);

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
                ->paginate(9);

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
                ->paginate(9);

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

    
    public function publicationCreateBySlugType(Request $request){

        $slug = $request->slug;

        if(auth()->user()->role_id === 1){

            $typePublicationCount = TypePublication::where('slug', $slug)->count();

            if($typePublicationCount === 0){

                return $this->sendResponse(['status' => 401], 'Le formulaire de ce type de publication n\'est pas disponible .');

            }else{

                $categoryCount = Category::orderBy('count_publications', 'desc')->count();

                if($categoryCount === 0){

                    return $this->sendResponse(['status' => 401, 'categoryCount'=> $categoryCount], 'Aucune catégorie n\'est enregistrée.');

                }else{

                    $authorsCount = Author::orderBy('count_publications', 'desc')->count();

                    if($authorsCount === 0){

                        return $this->sendResponse(['status' => 401, 'authorsCount'=> $authorsCount], 'Aucun auteur n\'est enregistré.');

                    }else{

                        $categories = Category::orderBy('categories.count_publications', 'desc')->get();

                        $tags = Tag::orderBy('tags.count_publications', 'desc')->get();

                        $authors = Author::orderBy('authors.count_publications', 'desc')->get();

                        $typePublication = TypePublication::where('slug', $slug)->first();

                        return $this->sendResponse(['typePublication' => $typePublication,'tags' => $tags, 'categories' => $categories,'authors' => $authors ,'typePublicationCount'=> $typePublicationCount,'status' => 200], 'Liste des types de publications.');

                    }

                }

            }

        }else{
            return $this->sendResponse(['status' => 422], 'Vous n\'êtes pas autorisé à acceder à ses données .');
        }

    }

    public function changeStatusPublicationPrgramm(){

        $publicationCount = Publication::where('presently', 1)->count();

        if($publicationCount !== 0){

            $publications = Publication::where('status', 2)->where('presently', 1)->get();

            foreach($publications as $publication){

                if($publication->date_publish <= now()){

                    $publication->status = 1;
                    
                    $publication->update();

                    if($publication->status == 1){

                        if($publication->type_publication_id == 2 || $publication->type_publication_id == 3){

                             

                        }else{

                            

                        }
                    }

                }

            }

            $publicationsH = Publication::where('status', 1)->where('presently', 1)->get();

            foreach($publicationsH as $publication){

                if($publication->date_publish_end <= now()){

                    $publication->status = 4;

                    $publication->update();

                }

            }

            return $this->sendResponse(['status' => 200], 'Changement de status effectué.');

        }else{

            return $this->sendResponse(['status' => 422], 'Erreur de changment de status.');

        }
    }     
}
