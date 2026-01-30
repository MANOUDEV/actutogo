<?php
namespace App\Http\Controllers\Api\Web\Frontoffice;

use App\Http\Controllers\Api\BaseController; 
use App\Models\Publication;
use App\Models\Tag;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR with multi
use Artesaos\SEOTools\Facades\JsonLdMulti;

class HomeController extends BaseController
{
    public function home(){

        $articles_count = Publication::where('status', 1)->count();

        if($articles_count === 0){

            return view('errors.HomePageControlEmpty');

        }else{

        SEOMeta::setTitle('Page d\'accueil| News 228');
        SEOMeta::setDescription('Votre site d\'actualité en ligne');
        SEOMeta::setCanonical('https://codecasts.com.br/lesson');

        OpenGraph::setDescription('Votre site d\'actualité en ligne');
        OpenGraph::setTitle('Page d\'accueil| News 228');
        OpenGraph::setUrl('https://news228.com');
        OpenGraph::addProperty('type', 'articles');

        TwitterCard::setTitle('Page d\'accueil| News 228page');
        TwitterCard::setSite('@manoudev');

        JsonLd::setTitle('Page d\'accueil| News 228page');
        JsonLd::setDescription('Votre site d\'actualité en ligne');
        JsonLd::addImage('https://news228.com/assets/images/logo.png');
        
            $alaUne =  Publication::where('status', 1)->where("publications.type_publication_id", 1)->where("publications.deja_citer", 0)->orderBy('date_publish', 'desc')->take(18)->get();

            $sports =  Publication::where('status', 1)->where("publications.type_publication_id", 1)->where("publications.category_id", 31)->where("publications.deja_citer", 0)->orderBy('date_publish', 'desc')->take(18)->get();

            $economie =  Publication::where('status', 1)->where("publications.type_publication_id", 1)->where("publications.category_id", 12)->where("publications.deja_citer", 0)->orderBy('date_publish', 'desc')->take(18)->get();

            return view('welcome', ['alaUne' => $alaUne, 'sports' => $sports, 'economie' => $economie]);

        }
    }

    public function nationalDataRequest(){

        $societe = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)

        ->where(function ($query) {
            $query->where("status", 1);
        })->where(function ($query) {
            $query->where("category_id", 35)
            ->orWhere("category_id", 1)
            ->orWhere("category_id", 2)
            ->orWhere("category_id", 27)
            ->orWhere("category_id", 34);
        })
        ->orderBy('date_publish', 'desc')
        ->take(6)
        ->get();

        $societeLoad = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where(function ($query) {
            $query->where("status", 1);
        })->where(function ($query) {
            $query->where("category_id", 35)
            ->orWhere("category_id", 1)
            ->orWhere("category_id", 2)
            ->orWhere("category_id", 27)
            ->orWhere("category_id", 34);
        })
        ->where("id", '!=',$societe[0]->id)
        ->where("id", '!=',$societe[1]->id)
        ->where("id", '!=',$societe[2]->id)
        ->where("id", '!=',$societe[3]->id)
        ->where("id", '!=',$societe[4]->id)
        ->where("id", '!=',$societe[5]->id)
        ->orderBy('date_publish', 'desc')
        ->take(6)
        ->get();

        $populars = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 10)
        ->orderBy('date_publish', 'desc')
        ->take(5)
        ->get();

        $inter = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 20)
        ->orderBy('date_publish', 'desc')
        ->take(5)
        ->get();

        $tags = Tag::orderBy('tags.count_publications', 'desc')->take(5)->get();

        return $this->sendResponse([
            'status' => 200,
            'societe' => $societe,
            'populars' => $populars,
            'inter' => $inter,
            'societeLoad' => $societeLoad,
            'tags' => $tags,
        ], 'Toute l\'actualité');

    }

    public function editorialDataRequest()
    {

        $internationalFirst = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 13)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        $internationalTwo = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 13)
        ->where("id", '!=',$internationalFirst[0]->id)
        ->where("id", '!=',$internationalFirst[1]->id)
        ->where("id", '!=',$internationalFirst[2]->id)
        ->where("id", '!=',$internationalFirst[3]->id)
        ->where("id", '!=',$internationalFirst[4]->id)
        ->where("id", '!=',$internationalFirst[5]->id)
        ->where("id", '!=',$internationalFirst[6]->id)
        ->where("id", '!=',$internationalFirst[7]->id)
        ->orderBy('date_publish', 'desc')
        ->take(1)
        ->get();


        $sports = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 20)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        $afrique = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 21)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

       

        return $this->sendResponse([
            'internationalFirst' =>  $internationalFirst,
            'internationalTwo' =>  $internationalTwo, 
            'afrique' =>  $afrique, 
            'sports' =>  $sports,
            'status' => 200
        ], 'Liste des articles publiés sur international.');

    }


     public function opinionFaitsDiversDataRequest()
    {

        $opinion = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 26)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        $faitsDivers = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 15)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        $politique = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 27)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        $societe = Publication::where("status", 1)
        ->where("publications.type_publication_id", 1)
        ->where("category_id", 30)
        ->orderBy('date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'opinion' =>  $opinion,
            'faitsDivers' =>  $faitsDivers,
            'politique' =>  $politique,
            'societe' =>  $societe,
            'status' => 200
        ], 'Liste des articles publiés sur les opinions et faits divers.');

    }

}
