<?php

use App\Models\Publication;
use Rabol\Adsense\AdsenseFacade;

function showPub($content, $category_id, $article_id){

    $alireaussi = Publication::where("publications.deja_citer", 0)
    ->where("publications.type_publication_id", 1)
    ->where("publications.status", 1)
    ->where("category_id", $category_id)
    ->where("id", "!=" ,$article_id)
    ->orderBy('publications.date_publish', 'desc')
    ->first();

    if($alireaussi == null){
        $alireaussi = Publication::where("publications.deja_citer", 0)
        ->where("publications.type_publication_id", 1)
        ->where("publications.status", 1)
        ->orderBy('publications.date_publish', 'desc')
        ->first();
    }

    $content_insert_begin = "<div class=\"row\">
                                <div class=\"col-sm-12 bg-primary bg-opacity-10 p-4 position-relative border-end border-1 rounded-start\">
                                    <span>A LIRE AUSSI</span>
                                    <h6 class=\"m-0\"><a href=\"/$alireaussi->slug\" class=\"stretched-link btn-link text-reset\"> $alireaussi->title </a></h6>
                                </div>
                            </div>
                            <br>
                            <div class=\"row g-4\">
                                <div class=\"col-lg-12 col-sm-12 col-xs-12\">
                                    <script defer async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5594963864359932\"
                                        crossorigin=\"anonymous\"></script>
                                    <!-- horizontale 970px / 80px -->
                                    <ins class=\"adsbygoogle\"
                                        style=\"display:inline-block;width:830px;height:90px\"
                                        data-ad-client=\"ca-pub-5594963864359932\"
                                        data-ad-slot=\"3527190816\"></ins>
                                    <script>
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                </div>
                            </div>";

    // Séparer le paragraphe en un tableau de mots
    $mots = explode(' ',  $content );

    // Calculer la position du milieu (basée sur le nombre de mots)
    $milieuPosition = floor(count($mots) / 2);

    // Insertion du texte au milieu du paragraphe
    array_splice($mots, $milieuPosition + 1, 0,  $content_insert_begin);
 

    // Rejoindre le tableau de mots en une chaîne de caractères
    $content = implode(' ', $mots);

    return $content;

}

