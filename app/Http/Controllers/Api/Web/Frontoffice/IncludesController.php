<?php
namespace App\Http\Controllers\Api\Web\Frontoffice;
use App\Http\Controllers\Api\BaseController;
use App\Models\Category; 
use App\Models\NewsLetter;
use App\Models\Tag;
use App\Models\Publication; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 

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
    public function togoActualiteRequestData(){

        $togoactualiteDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 34)->count();

        if($togoactualiteDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur togoactualité n\'est publiée.');

        }else if ($togoactualiteDataCount !== 0){

            $togoactualiteData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 35)
                ->orWhere("category_id", 1)
                ->orWhere("category_id", 2)
                ->orWhere("category_id", 27)
                ->orWhere("category_id", 34);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'togoactualiteData' =>  $togoactualiteData,
                'status' => 200
            ], 'Listes de publications de togoactualité publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }



    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rubriquesRequestData(){

        $rubriquesDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 28)->count();

        if($rubriquesDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur rubriques n\'est publiée.');

        }else if ($rubriquesDataCount !== 0){

            $rubriquesData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 28);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'rubriquesData' =>  $rubriquesData,
                'status' => 200
            ], 'Listes de publications de rubriques publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function diplomatieRequestData(){

        $diplomatieDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 11)->count();

        if($diplomatieDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur diplomatie n\'est publiée.');

        }else if ($diplomatieDataCount !== 0){

            $diplomatieData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 11);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'diplomatieData' =>  $diplomatieData,
                'status' => 200
            ], 'Listes de publications de diplomatie publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function chroniquesRequestData(){

        $chroniquesDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 5)->count();

        if($chroniquesDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur chroniques n\'est publiée.');

        }else if ($chroniquesDataCount !== 0){

            $chroniquesData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 5);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'chroniquesData' =>  $chroniquesData,
                'status' => 200
            ], 'Listes de publications de chroniques publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function economieRequestData(){

        $economieDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 12)->count();

        if($economieDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur économie n\'est publiée.');

        }else if ($economieDataCount !== 0){

            $economieData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 12);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'economieData' =>  $economieData,
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

    public function diasporaRequestData(){

        $diasporaDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 10)->count();

        if($diasporaDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur diaspora n\'est publiée.');

        }else if ($diasporaDataCount !== 0){

            $diasporaData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 10);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'diasporaData' =>  $diasporaData,
                'status' => 200
            ], 'Listes de publications de diaspora publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function fenetreSurLAfriqueRequestData(){

        $fenetreSurLAfriqueDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 16)->count();

        if($fenetreSurLAfriqueDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur fenêtre sur l\'afrique n\'est publiée.');

        }else if ($fenetreSurLAfriqueDataCount !== 0){

            $fenetreSurLAfriqueData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 16);
            })->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'fenetreSurLAfriqueData' =>  $fenetreSurLAfriqueData,
                'status' => 200
            ], 'Listes de publications de fenêtre sur l\'afrique publiés');

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

        $internationalDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 20)->count();

        if($internationalDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur international n\'est publiée.');

        }else if ($internationalDataCount !== 0){

            $internationalData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 20);
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

     public function mondeRequestData(){

        $mondeDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 24)->count();

        if($mondeDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur monde n\'est publiée.');

        }else if ($mondeDataCount !== 0){

            $mondeData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 24);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'mondeData' =>  $mondeData,
                'status' => 200
            ], 'Listes de publications de monde publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }



    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function afriqueRequestData(){

        $afriqueDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 3)->count();

        if($afriqueDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur afrique n\'est publiée.');

        }else if ($afriqueDataCount !== 0){

            $afriqueData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 3);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'afriqueData' =>  $afriqueData,
                'status' => 200
            ], 'Listes de publications de afrique publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }



    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sportsRequestData(){

        $sportsDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 31)->count();

        if($sportsDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur sports n\'est publiée.');

        }else if ($sportsDataCount !== 0){

            $sportsData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 31);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'sportsData' =>  $sportsData,
                'status' => 200
            ], 'Listes de publications de sports publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function canRequestData(){

        $canDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 4)->count();

        if($canDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur can n\'est publiée.');

        }else if ($canDataCount !== 0){

            $canData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 4);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'canData' =>  $canData,
                'status' => 200
            ], 'Listes de publications de can publiés');

        }else{

            return $this->sendResponse(['status' => 422], 'Impossible de chargez les données.');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function togoRequestData(){

        $togoDataCount = Publication::where("status", 1)->where("type_publication_id", 1)->where("category_id", 34)->count();

        if($togoDataCount === 0){

            return $this->sendResponse(['status' => 401], 'Aucune publication sur togo n\'est publiée.');

        }else if ($togoDataCount !== 0){

            $togoData = Publication::select(array("id", "content", "truncate_content","title", "slug", "date_publish" ,"author_name", "author_slug","image_cover_url"))
            ->where(function ($query) {
                $query->where("status", 1);
            })->where(function ($query) {
                $query->where("category_id", 34);
            })->orderBy('date_publish', 'desc')
            ->orderBy('date_publish', 'desc')
            ->take(4)
            ->get();

            return $this->sendResponse([
                'togoData' =>  $togoData,
                'status' => 200
            ], 'Listes de publications de togo publiés');

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
       $tags = Tag::orderBy('tags.count_publications', 'desc')->take(20)->get();

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
    
}