<?php

namespace App\Http\Controllers\Api\Web\Backoffice\Admin\Publications;

use App\Http\Controllers\Api\BaseController;
use App\Models\Author;
use App\Models\Category;
use App\Models\File;
use App\Models\InfosMonthYear;
use App\Models\InfosMonthYearPublication;
use App\Models\TypePublication;
use App\Models\Publication;
use App\Models\PublicationTag;
use App\Models\PublicationFile;
use App\Models\PushSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class PublicationController extends BaseController
{
   

    public function str_replace_all($search, $replace, $subject) {

        return str_replace($search, $replace, $subject);

    }

    public function storePublication(Request $request){

          

            $datas = $request->all();

            
            $datas['author'] = json_decode($datas['author'], true);
            $datas['category'] = json_decode($datas['category'], true);
            $datas['tag'] = json_decode($datas['tag'], true);

            $validator = Validator::make($datas, [
                'title' => ['required','string', 'unique:publications'],
                'status' => ['required'],
            ],[
                'required' => 'Votre :attribute est obligatoire.',
                'title.unique' => 'Ce :attribute est déjà enregistré.',
            ], [
                'title' => 'titre de la publication',

            ]);

            if ($validator->fails()) {

                return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Le titre de votre publication est obligatoire');

            }

            $typePublicationCount = TypePublication::where('slug','articles')->count();

            if($typePublicationCount === 0){

                return $this->sendResponse(['status' => 422], 'Aucun type de publication n\'est enregistré.');

            }else{

                $typePublication = TypePublication::where('slug','articles')->first();

                // if($datas['status'] == 1 || $datas['status']  == 2){

                //     if($datas['date_publish'] <= now()){

                //         return $this->sendResponse(['status' => 422], 'Veuillez bien revoir votre date de debut de publication .');

                //     }else{

                //         if($datas['date_publish_end'] <= now()){

                //             return $this->sendResponse(['status' => 422], 'Veuillez bien revoir votre date de fin de publication .');

                //         }else{

                //             if($datas['date_publish_end'] <= $datas['date_publish']){

                //                 return $this->sendResponse(['status' => 422], 'Votre date de fin de publication ne peut être antérieure à la date de debut de publication ');

                //             }
                //         }
                //     }
                // }

                $datas['title'] = strip_tags($datas['title']);

                if(count($datas['category']) === 1){

                    $datas['type_publication_id'] = $typePublication->id;

                    $datas['type_publication_name'] = $typePublication->name;

                    $datas['type_publication_slug'] = $typePublication->slug;

                    $datas['date_publish'] = now();

                    $datas['deja_citer'] = 0;

                    $datas['category_id'] = $datas['category']['0']['id'];

                    $datas['category_name'] = $datas['category']['0']['name'];

                    $datas['category_slug'] = $datas['category']['0']['slug'];

                    $category = Category::where('slug', $datas['category_slug'])->first();

                    $category->count_publications += 1;

                    $category->update();

                    $datas['author_id'] = $datas['author']['id'];

                    $datas['author_name'] = $datas['author']['authorName'];

                    $datas['author_slug'] = $datas['author']['slug'];

                    $author = Author::where('slug', $datas['author_slug'])->first();

                    $author->count_publications += 1;

                    $author->update();

                    $datas['user_id'] = 1;

                    $datas['presently'] = 1;

                    $datas['title'] = $this->str_replace_all('<p>', '<span>', $this->str_replace_all('</p>', '</span>', $datas['title'])) ;

                    $datas['slug'] = Str::slug($this->stripTags($datas['title']));

                    $datas['title_truncate'] = $this->getExcerpt(10, $datas['title']);



                    $date = Carbon::parse(now());

                    $mois_id = $date->format('m');

                    $year = $date->format('Y');

                    $mois = InfosMonthYear::where('month_id', $mois_id)->first();

                    $date_name = $mois->month.' '.$year;

                    $datas['date_name'] =  $date_name;

                    $verify_date_name = InfosMonthYearPublication::where('date_name', $date_name)->first();

                    if(!$verify_date_name){

                        InfosMonthYearPublication::create(['date_name' => $date_name]);

                    }

                    $publicationCreateData =  Publication::create($datas);

                    $publicationCreateData->truncate_content = $this->getExcerpt(20, $datas['content']);

                    $publicationCreateData->truncate_content_max = $this->getExcerpt(60, $datas['content']);

                    $publicationCreateData->update();

                    for ($i=0; $i<= count($datas['tag']) - 1; $i++ ){

                        $tag_publication = PublicationTag::create([
                            'publication_id' => $publicationCreateData->id,
                            'tag_id' => $datas['tag'][$i]['id'],
                            'date_publish' => now(),

                        ]);

                        $tag =  Tag::where('id',$datas['tag'][$i]['id'])->first();

                        if($tag->count_publications === 0){

                            $tag->count_publications = 1;

                            $tag->update();

                        }else{

                            $tag->count_publications++;

                            $tag->update();

                        }

                    }

                    $uploadedFiles = $request->file('files');

                    dd($uploadedFiles);

                    foreach ($uploadedFiles as $file) {

                        $path = $file->store('uploads', 'public');

                        $fileDB = File::create([
                            'file_url' => "/storage/" . $path,
                            'name' => $file->getClientOriginalName(), 
                        ]);

                            if( $file['isCover']){

                                $publicationCreateData->image_cover_url = $fileDB->file_url;

                                $publicationCreateData->update();

                            }

                             $tag_publication = PublicationFile::create([
                                'publication_id' => $publicationCreateData->id,
                                'file_id' => $fileDB->id,
                                'date_publish' => now(),

                            ]);

                            $tag =   File::where('file_url', $fileDB->file_url)->first();

                            if($tag->count_publications === 0){

                                $tag->count_publications = 1;

                                $tag->update();

                            }else{

                                $tag->count_publications++;

                                $tag->update();

                            } 

                        

                    }

                    if($publicationCreateData){



                        return $this->sendResponse(['publicationCreateData' => $publicationCreateData, 'status' => 200], 'Première étape validée.');

                    }else{

                        return $this->sendResponse(['status' => 422], 'Impossible d\'enregistrer cette publication.');

                    }

                }else{

                    $datas['type_publication_id'] = $typePublication->id;

                    $datas['type_publication_name'] = $typePublication->name;

                    $datas['type_publication_slug'] = $typePublication->slug;

                    $datas['date_publish'] = now();

                    $datas['deja_citer'] = 0;

                    $datas['category_id'] = $datas['category']['0']['id'];

                    $datas['category_name'] = $datas['category']['0']['name'];

                    $datas['category_slug'] = $datas['category']['0']['slug'];

                    $category = Category::where('slug', $datas['category_slug'])->first();

                    $category->count_publications += 1;

                    $category->update();

                    $datas['author_id'] = $datas['author']['id'];

                    $datas['author_name'] = $datas['author']['authorName'];

                    $datas['author_slug'] = $datas['author']['slug'];

                    $author = Author::where('slug', $datas['author_slug'])->first();

                    $author->count_publications += 1;

                    $author->update();

                    $datas['user_id'] = auth()->user()->id;

                    $datas['presently'] = 1;

                    $datas['title'] = $this->str_replace_all('<p>', '<span>', $this->str_replace_all('</p>', '</span>', $datas['title'])) ;

                    $datas['slug'] = Str::slug($this->stripTags($datas['title']));

                    $datas['title_truncate'] = $this->getExcerpt(10, $datas['title']);



                    $date = Carbon::parse(now());

                    $mois_id = $date->format('m');

                    $year = $date->format('Y');

                    $mois = InfosMonthYear::where('month_id', $mois_id)->first();

                    $date_name = $mois->month.' '.$year;

                    $datas['date_name'] =  $date_name;

                    $verify_date_name = InfosMonthYearPublication::where('date_name', $date_name)->first();

                    if(!$verify_date_name){

                        InfosMonthYearPublication::create(['date_name' => $date_name]);

                    }

                    $publicationCreateData =  Publication::create($datas);

                    $publicationCreateData->truncate_content = $this->getExcerpt(20, $datas['content']);

                    $publicationCreateData->truncate_content_max = $this->getExcerpt(60, $datas['content']);

                    $publicationCreateData->image_cover_url = $datas['og_file_url'];

                    $publicationCreateData->update();

                    for ($i=0; $i<= count($datas['tag']) - 1; $i++ ){

                        $tag_publication = PublicationTag::create([
                            'publication_id' => $publicationCreateData->id,
                            'tag_id' => $datas['tag'][$i]['id'],
                            'date_publish' => now(),

                        ]);

                        $tag =  Tag::where('id',$datas['tag'][$i]['id'])->first();

                        if($tag->count_publications === 0){

                            $tag->count_publications = 1;

                            $tag->update();

                        }else{

                            $tag->count_publications++;

                            $tag->update();

                        }

                    }

                    for ($i=0; $i<= count($datas['files_selected']) - 1; $i++ ){

                        $files_results = File::where('file_name', $datas['files_selected'][$i]['file_name'])->first();

                        $tag_publication = PublicationFile::create([
                            'publication_id' => $publicationCreateData->id,
                            'file_id' => $files_results->id,
                            'date_publish' => now(),

                        ]);

                        $tag =   File::where('file_name', $datas['files_selected'][$i]['file_name'])->first();

                        if($tag->count_publications === 0){

                            $tag->count_publications = 1;

                            $tag->update();

                        }else{

                            $tag->count_publications++;

                            $tag->update();

                        }

                    }


                    for ($i=1; $i<= count($datas['category']) - 1; $i++ ){

                        $datas['type_publication_id'] = $typePublication->id;

                        $datas['type_publication_name'] = $typePublication->name;

                        $datas['type_publication_slug'] = $typePublication->slug;

                        $datas['date_publish'] = now();

                        $datas['deja_citer'] = 1;

                        $datas['category_id'] = $datas['category'][$i]['id'];

                        $datas['category_name'] = $datas['category'][$i]['name'];

                        $datas['category_slug'] = $datas['category'][$i]['slug'];

                        $category = Category::where('slug', $datas['category_slug'])->first();

                        $category->count_publications += 1;

                        $category->update();

                        $datas['author_id'] = $datas['author']['id'];

                        $datas['author_name'] = $datas['author']['authorName'];

                        $datas['author_slug'] = $datas['author']['slug'];

                        $author = Author::where('slug', $datas['author_slug'])->first();

                        $author->count_publications += 1;

                        $author->update();

                        $datas['user_id'] = auth()->user()->id;

                        $datas['presently'] = 1;

                        $datas['title'] = $this->str_replace_all('<p>', '<span>', $this->str_replace_all('</p>', '</span>', $datas['title'])) ;

                        $datas['slug'] = Str::slug($this->stripTags($datas['title']));

                        $datas['title_truncate'] = $this->getExcerpt(10, $datas['title']);



                        $date = Carbon::parse(now());

                        $mois_id = $date->format('m');

                        $year = $date->format('Y');

                        $mois = InfosMonthYear::where('month_id', $mois_id)->first();

                        $date_name = $mois->month.' '.$year;

                        $datas['date_name'] =  $date_name;

                        $verify_date_name = InfosMonthYearPublication::where('date_name', $date_name)->first();

                        if(!$verify_date_name){

                            InfosMonthYearPublication::create(['date_name' => $date_name]);

                        }

                        $publicationCreateData =  Publication::create($datas);

                        $publicationCreateData->truncate_content = $this->getExcerpt(20, $datas['content']);

                    $publicationCreateData->truncate_content_max = $this->getExcerpt(60, $datas['content']);

                    $publicationCreateData->image_cover_url = $datas['og_file_url'];

                    $publicationCreateData->update();

                    for ($i=0; $i<= count($datas['tag']) - 1; $i++ ){

                        $tag_publication = PublicationTag::create([
                            'publication_id' => $publicationCreateData->id,
                            'tag_id' => $datas['tag'][$i]['id'],
                            'date_publish' => now(),

                        ]);

                        $tag =  Tag::where('id',$datas['tag'][$i]['id'])->first();

                        if($tag->count_publications === 0){

                            $tag->count_publications = 1;

                            $tag->update();

                        }else{

                            $tag->count_publications++;

                            $tag->update();

                        }

                    }

                    for ($i=0; $i<= count($datas['files_selected']) - 1; $i++ ){

                        $files_results = File::where('file_name', $datas['files_selected'][$i]['file_name'])->first();

                        $tag_publication = PublicationFile::create([
                            'publication_id' => $publicationCreateData->id,
                            'file_id' => $files_results->id,
                            'date_publish' => now(),

                        ]);

                        $tag =   File::where('file_name', $datas['files_selected'][$i]['file_name'])->first();

                        if($tag->count_publications === 0){

                            $tag->count_publications = 1;

                            $tag->update();

                        }else{

                            $tag->count_publications++;

                            $tag->update();

                        }

                    }


                    }

                    if($publicationCreateData){



                        return $this->sendResponse(['publicationCreateData' => $publicationCreateData, 'status' => 200], 'Première étape validée.');

                    }else{

                        return $this->sendResponse(['status' => 422], 'Impossible d\'enregistrer cette publication.');

                    }

                }
            }


        

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
