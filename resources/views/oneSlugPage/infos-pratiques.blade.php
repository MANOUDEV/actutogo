@extends('layouts.base')

@section('title') Infos Pratiques @endsection

@section('content')

    @include('includes.header')

    <main style="margin-top: -30px">
        <section class="position-relative">
            <div class="container" data-sticky-container>
                
                @include('adsense.google-ads.ads-sections-home-page')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-4">
                            <h1 class="display-4"> Infos Pratiques</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots">
                                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house me-1"></i>Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="#">Infos pratiques</a></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    @include('adsense.google-ads.ads-sections-home-page')
                    <hr/>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h3 class="text-primary"> 19 COMMISSARIATS DE POLICE</h6>
                        </div>
                    </div>
                    <hr />
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT CENTRAL DE LOME</h5>
                            <h6><a href="tel:+22822212871" class="text-reset"><u>+228 22 21 28 71</u></a></h6>
                            <h6>Av. de la Libération, Carrefour de la Fontaine Luminueuse</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr />
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>Commissariat De Djidjolé (3e Arrondissement)</h5>
                            <h6><a href="tel:+22822254607" class="text-reset"><u>+228 22 25 46 07</u></a></h6>
                            <h6>1805, Avenue De Pya, 352</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE D’ATAKPAME (REGION DES PLATEAUX)</h5>
                            <h6><a href="tel:+22824400102" class="text-reset"><u>+228 24 40 01 02</u></a></h6>
                            <h6>En face de la Nle n°5, à côté de l’Abattoir, à 80m de l’agence CEET s/c 352 Lomé</h6>
                            <h6>Atakpamé – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE BAFILO (REGION DE LA KARA)</h5>
                            <h6><a href="tel:115" class="text-reset"><u>115</u></a></h6>
                            <h6>Non loin de l’ancien hôtel Mazasso</h6>
                            <h6>Bafilo – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE BASSAR (REGION DE LA KARA)</h5>
                            <h6><a href="tel:+22826630016" class="text-reset"><u>+228 26 63 00 16</u></a></h6>
                            <h6>En face de la station Transport Routier</h6>
                            <h6>Bassar – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE GUERIN KOUKA (REGION DE LA KARA)</h5>
                            <h6><a href="tel:+22826620005" class="text-reset"><u>+228 26 62 00 05</u></a></h6>
                            <h6>Guérin-Kouka – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE KANTE (REGION DE LA KARA)</h5>
                            <h6><a href="tel:+22826670010" class="text-reset"><u>+228 26 67 00 10</u></a></h6>
                            <h6>Kantè – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE KARA (REGION DE LA KARA)</h5>
                            <h6><a href="tel:+22826606052" class="text-reset"><u>+228 26 60 60 52</u></a></h6>
                            <h6>Rond point BCEAO, Diamond Bank et Commissariat</h6>
                            <h6>S/c BP 352 Lomé</h6>
                            <h6>Kara – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE KEVE (REGION MARITIME)</h5>
                            <h6><a href="tel:+22823371041" class="text-reset"><u>+228 23 37 10 41</u></a></h6>
                            <h6>Kévé – Togo</h6>
                        </div>
                    </div>
                    
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE KPALIME (REGION DES PLATEAUX)</h5>
                            <h6><a href="tel:+22824410053" class="text-reset"><u>+228 24 41 00 53</u></a></h6>
                            <h6>A côté de la 2ème station d’essence de Kpalimé, face au bar BENJAGO</h6>
                            <h6>Kpalimé – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE MANDOURI (REGION DES SAVANES)</h5>
                            <h6><a href="tel:+22827740003" class="text-reset"><u>+228 27 74 00 03</u></a></h6>
                            <h6>Mandouri – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE NOTSE (REGION DES PLATEAUX)</h5>
                            <h6><a href="tel:+22824420017" class="text-reset"><u>+228 24 42 00 17</u></a></h6>
                            <h6>Notsè – Togo</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE SOKODE (REGION CENTRALE)</h5>
                            <h6><a href="tel:+22825500062" class="text-reset"><u>+228 25 50 00 62</u></a></h6>
                            <h6>Nationale n°1, après le grand marché, 2ème station d’essence, à 20m de la station</h6>
                            <h6>Sokodé – Togo</h6>
                        </div>
                    </div>
                    <hr />
        
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE SOTOUBOUA (REGION CENTRALE)</h5>
                            <h6><a href="tel:+22825530078" class="text-reset"><u>+228 25 53 00 78</u></a></h6>
                            <h6>Sotouboua – Togo</h6>
                        </div>
                    </div>
                    <hr />
                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE TCHAMBA (REGION CENTRALE)</h5>
                            <h6><a href="tel:+22825520034" class="text-reset"><u>+228 25 52 00 34</u></a></h6>
                            <h6>Tchamba – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE TOHOUN (REGION DES PLATEAUX)</h5>
                            <h6><a href="tel:+22824485012" class="text-reset"><u>+228 24 48 50 12</u></a></h6>
                            <h6>Tohoun – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE TSEVIE (REGION MARITIME)</h5>
                            <h6><a href="tel:+22823304024" class="text-reset"><u>+228 23 30 40 24</u></a></h6>
                            <h6>Tsévié – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE DE VOGAN (REGION MARITIME)</h5>
                            <h6><a href="tel:+22823331022" class="text-reset"><u>+228 23 33 10 22</u></a></h6>
                            <h6>Vogan – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>COMMISSARIAT DE POLICE SANSSANE – MANGO (REGION DES SAVANES)</h5>
                            <h6><a href="tel:115" class="text-reset"><u>115</u></a>(N° Vert)<a href="tel:+22827717008" class="text-reset"><u> +228 27 71 70 08</u></a>(Standart)</h6>
                            <h6>S/c BP 352 Lomé</h6>
                            <h6>Mango – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h3 class="text-primary"> 29 AMBASSADES ET CONSULATS AU TOGO</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE D’ANGOLA</h5>
                            <h6><a href="tel:+228 22 20 96 58" class="text-reset"><u>+228 22 20 96 58</u></a></h6>
                            <h6>Kodjoviakopé – BP 701</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE D’EGYPTE AU TOGO</h5>
                            <h6><a href="tel:+228 22 26 24 43" class="text-reset"><u>+228 22 26 24 43</u></a></h6>
                            <h6>Cité OUA – 01 BP 512</h6>
                            <h6>Rue 168, Villa N°512</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE CHINE AU TOGO</h5>
                            <h6><a href="tel:+228 22 61 40 88" class="text-reset"><u>+228 22 61 40 88</u></a></h6>
                            <h6>Cité OUA – BP 2690</h6>
                            <h6>Derrière Ambassade des USA</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE FRANCE</h5>
                            <h6><a href="tel:+228 22 23 46 00" class="text-reset"><u>+228 22 23 46 00</u></a></h6>
                            <h6>Quartier Administratif – BP 337</h6>
                            <h6>13, Av. Mama Fousséni</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE L’ORDRE SOUVERAIN MILITAIRE DE MALTE</h5>
                            <h6><a href="tel:+228 22 21 58 11" class="text-reset"><u>+228 22 21 58 11</u></a></h6>
                            <h6>Tokoin SOTED – BP 10054</h6>
                            <h6>Bd. des Armées, Vers la petite porte du Camp</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE LA REPUBLIQUE FEDERALE D’ ALLEMANGNE</h5>
                            <h6><a href="tel:+228 22 23 32 32" class="text-reset"><u>+228 22 23 32 32</u></a></h6>
                            <h6>Kodjoviakopé – 01 BP 1175</h6>
                            <h6>Bd. de la République</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE LIBYE AU TOGO</h5>
                            <h6><a href="tel:+228 22 61 47 09" class="text-reset"><u>+228 22 61 47 09</u></a></h6>
                            <h6>Cité OUA – BP 4872</h6>
                            <h6>1er Bâtiment à gauche à l’entrée de la Primature</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE RDC</h5>
                            <h6><a href="tel:+228 22 21 42 33" class="text-reset"><u>+228 22 21 42 33</u></a></h6>
                            <h6>Tokoin Hôpital – BP 1102</h6>
                            <h6>311, rue Nagbeni, non loin de l’Hôtel Plateau</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DE TUNISIE</h5>
                            <h6><a href="tel:+228 22 21 26 37" class="text-reset"><u>+228 22 21 26 37</u></a></h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DES ETATS UNIS D’AMERIQUE AU TOGO</h5>
                            <h6><a href="tel:+228 22 61 54 70" class="text-reset"><u>+228 22 61 54 70</u></a></h6>
                            <h6>Cité OUA – BP 852</h6>
                            <h6>4332, Bd. Eyadéma</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU BRESIL</h5>
                            <h6><a href="tel:+228 22 61 56 58" class="text-reset"><u>+228 22 61 56 58</u></a></h6>
                            <h6>BP 916</h6>
                            <h6>1356, Rue de La Présidence de La République Annexe, Bd. Eyadema, Cité OUA</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU CANADA AU TOGO</h5>
                            <h6><a href="tel:+233 03 02 21 15 21" class="text-reset"><u>+233 03 02 21 15 21</u></a></h6>
                            <h6>P.O.Box 1639 Accra, Ghana</h6>
                            <h6>42 Independence Avenue Accra, Sankara Interchange, Ako Adjei Overpass</h6>
                            <h6>Lomé – Togo</h6>
                            <h6>Le Haut-Commissariat du Canada au Ghana a également la responsabilité pour le Togo et la Sierra Leone.</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU GABON</h5>
                            <h6><a href="tel:+228 22 26 75 63" class="text-reset"><u>+228 22 26 75 63</u></a></h6>
                            <h6>Hédzranawoé – BP 12025</h6>
                            <h6>Bd. Jean-Paul II</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU GHANA</h5>
                            <h6><a href="tel:+228 22 21 31 94" class="text-reset"><u>+228 22 21 31 94</u></a></h6>
                            <h6>Tokoin Ouest – BP 9118</h6>
                            <h6>8, Rue Paulin Eklou, 500m après le Marché de Kodomé</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU NIGER AU TOGO</h5>
                            <h6><a href="tel:+228 22 26 47 15" class="text-reset"><u>+228 22 26 47 15</u></a></h6>
                            <h6>Tokoin Tamé – 14 BP 141</h6>
                            <h6>412, rue Agnangna, TKT 205, non loin de SOS village d’enfants</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU NIGERIA AU TOGO</h5>
                            <h6><a href="tel:+228 22 21 34 55" class="text-reset"><u>+228 22 21 34 55</u></a></h6>
                            <h6>Tokoin Soted – BP 1189</h6>
                            <h6>109, Av. des Hydrocarbures, à côté de CFAO Motors</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU SENEGAL</h5>
                            <h6><a href="tel:+228 22 22 98 35" class="text-reset"><u>+228 22 22 98 35</u></a></h6>
                            <h6>Adéwi – BP 9272</h6>
                            <h6>1391, Bd. de la Kara</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT D’AUTRICHE</h5>
                            <h6><a href="tel:+228 22 20 89 35" class="text-reset"><u>+228 22 20 89 35</u></a></h6>
                            <h6>Agbadahonou – BP 212</h6>
                            <h6>Angle Rue Thompson et Komoré, Rue en face de Ramco</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT D’ESPAGNE AU TOGO</h5>
                            <h6><a href="tel:+228 23 81 33 09" class="text-reset"><u>+228 23 81 33 09</u></a></h6>
                            <h6>Rue 23</h6>
                            <h6>Akodésséwa</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT D’ISLANDE</h5>
                            <h6><a href="tel:+228 22 25 06 08" class="text-reset"><u>+228 22 25 06 08</u></a></h6>
                            <h6>Bè Klikamé</h6>
                            <h6>Von face à l’ancienne Eglise Catholique, après celle du Dr. Tchangai</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE BELGIQUE</h5>
                            <h6><a href="tel:+228 22 21 03 23" class="text-reset"><u>+228 22 21 03 23</u></a></h6>
                            <h6>BP 24</h6>
                            <h6>Imm. RAMCO, Assivito</h6>
                            <h6>Ambassades et consulats</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE FRANCE</h5>
                            <h6><a href="tel:+228 22 23 46 40" class="text-reset"><u>+228 22 23 46 40</u></a></h6>
                            <h6>Quartier Administratif – BP 337</h6>
                            <h6>Rue de la Marina</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE GRANDE BRETAGNE</h5>
                            <h6><a href="tel:+228 22 22 20 94" class="text-reset"><u>+228 22 22 20 94</u></a></h6>
                            <h6>Bè Château – BP 13956</h6>
                            <h6>Rue des Canaris</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE L’INDE</h5>
                            <h6><a href="tel:+228 22 22 30 04" class="text-reset"><u>+228 22 22 30 04</u></a></h6>
                            <h6>Bd. du Mono, Enceinte de l’Hôtel KRIMAS, BP 1003</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA CENTRAFRIQUE</h5>
                            <h6><a href="tel:+228 23 36 95 45" class="text-reset"><u>+228 23 36 95 45</u></a></h6>
                            <h6>Hanoukopé</h6>
                            <h6>Ancienne Mson de Edem Kodjo</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA REPUBLIQUE D’INDONESIE</h5>
                            <h6><a href="tel:+228 22 61 22 47" class="text-reset"><u>+228 22 61 22 47</u></a></h6>
                            <h6>418, Rue des Jumeaux, Derrière LCF</h6>
                            <h6>03 BP 30036</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA REPUBLIQUE DE COTE D’IVOIRE</h5>
                            <h6><a href="tel:+228 22 22 24 40" class="text-reset"><u>+228 22 22 24 40</u></a></h6>
                            <h6>58, Av. de Duisburg, Kodjoviakopé</h6>
                            <h6>BP 7701</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA REPUBLIQUE DE LA GUINEE CONAKRY AU TOGO</h5>
                            <h6><a href="tel:+228 22 21 13 01" class="text-reset"><u>+228 22 21 13 01</u></a></h6>
                            <h6>30, Rue de la Gare, Imm. face à CITIMART, 1er étage</h6>
                            <h6>Assivito</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA REPUBLIQUE DE LA TURQUIE</h5>
                            <h6><a href="tel:+228 22 20 31 06" class="text-reset"><u>+228 22 20 31 06</u></a></h6>
                            <h6>09, Rue Litimé, derrière Station Service Total, OCAM</h6>
                            <h6>Bè – BP 30386</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA REPUBLIQUE ISLAMIQUE DE MAURITANIE</h5>
                            <h6><a href="tel:+228 22 26 10 90" class="text-reset"><u>+228 22 26 10 90</u></a></h6>
                            <h6>255, Rue des Noyers</h6>
                            <h6>Hédzranawoé – 01 BP 374</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE LA ROUMANIE</h5>
                            <h6><a href="tel:+228 22 21 79 31" class="text-reset"><u>+228 22 21 79 31</u></a></h6>
                            <h6>BP 1025</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE NORVEGE AU TOGO</h5>
                            <h6><a href="tel:+228 22 27 08 59" class="text-reset"><u>+228 22 27 08 59</u></a></h6>
                            <h6>01 BP 1687</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>    
                    @include('adsense.google-ads.ads-sections-home-page')

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE POLOGNE</h5>
                            <h6><a href="tel:+228 22 22 33 88" class="text-reset"><u>+228 22 22 33 88</u></a></h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE SERBIE MONTENEGRO</h5>
                            <h6><a href="tel:+228 22 21 15 65" class="text-reset"><u>+228 22 21 15 65</u></a></h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DE SUISSE AU TOGO</h5>
                            <h6><a href="tel:+228 22 20 50 60" class="text-reset"><u>+228 22 20 50 60</u></a></h6>
                            <h6>1, Bd. de la République, Imm. Taba, Hotel Palm Beach</h6>
                            <h6>Grand Marché –Assigamé</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DU BENIN</h5>
                            <h6><a href="tel:+228 91 97 61 67" class="text-reset"><u>+228 91 97 61 67</u></a></h6>
                            <h6>Rue de l’Entente, Ex Rue de l’Ocam, Imm. Ogar Assurance</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DU LUXEMBOURG AU TOGO</h5>
                            <h6><a href="tel:+228 22 21 46 38" class="text-reset"><u>+228 22 21 46 38</u></a></h6>
                            <h6>BP 941</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DU NIGER</h5>
                            <h6><a href="tel:+228 22 21 63 73" class="text-reset"><u>+228 22 21 63 73</u></a></h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT DU PAKISTAN</h5>
                            <h6><a href="tel:+228 22 61 33 57" class="text-reset"><u>+228 22 61 33 57</u></a></h6>
                            <h6>Derrière la maison du ministre Koffi Panou, Kégué</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CONSULAT GENERAL DU BURKINA FASO</h5>
                            <h6><a href="tel:+228 22 26 66 00" class="text-reset"><u>+228 22 26 66 00</u></a></h6>
                            <h6>Hédzranawoé – 03 BP 31257 Lomé-Wuiti</h6>
                            <h6>Rue 174, en face de BEC.Co Ltd</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h3 class="text-primary"> 11 RELIGIONS – LIEUX DE CULTE</h6>
                        </div>
                    </div>
                    <hr />

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>AMBASSADE DU SAINT SIEGE NONCIATURE APOSTOLIQUE</h5>
                            <h6><a href="tel:+228 22 26 03 06" class="text-reset"><u>+228 22 26 03 06</u></a></h6>
                            <h6>Bd. du Haho, Hédzranawoè,</h6>
                            <h6>02 BP 20790 Lomé 02</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>ARCHEVECHE DE LOME</h5>
                            <h6><a href="tel:+228 22 21 22 72" class="text-reset"><u>+228 22 21 22 72</u></a></h6>
                            <h6>561, Rue de l’Eglise</h6>
                            <h6>Grand Marché –Assigamé</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CENTRE AUDIO VISUEL SAINT AUGUSTIN POUR LA CATECHESE</h5>
                            <h6><a href="tel:+228 22 40 17 97" class="text-reset"><u>+228 22 40 17 97</u></a></h6>
                            <h6>Av. de la Libération, Adawlato</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>CET (Conférence Episcopale du Togo)</h5>
                            <h6><a href="tel:+228 22 613 6 29" class="text-reset"><u>+228 22 613 6 29</u>(Siège des Evèques)</a></h6>
                            <h6>Hédzranawoé</h6>
                            <h6>BP 348</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>SERVICE DU SOCIAL</h5>
                            <h6><a href="tel:+228 22 40 37 07" class="text-reset"><u>+228 22 40 37 07</u></a></h6>
                            <h6>03 BP 31020 Lomé 03</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>LA VERTICALE</h5>
                            <h6><a href="tel:+228 90 94 21 25" class="text-reset"><u>+228 90 94 21 25</u>(Boutique spirituelle)</a></h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>MEPS (MINISTERE EVANGELISATION PERFECTIONNEMENT DES SAINTS)</h5>
                            <h6><a href="tel:+228 99 95 35 08" class="text-reset"><u>+228 99 95 35 08</u></a></h6>
                            <h6>Derrière Togo 2000</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    @include('adsense.google-ads.ads-sections-home-page')
                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>MINISTERE DE LA VOIE AU TOGO</h5>
                            <h6><a href="tel:+228 22 21 97 98" class="text-reset"><u>+228 22 21 97 98</u></a></h6>
                            <h6>51, Rue 35 Saint Joseph, Quartier Saint Joseph</h6>
                            <h6>08 BP 8563 Lomé 08</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>MISSION EVANGELIQUE DE VIE</h5>
                            <h6><a href="tel:+228 90 10 45 84" class="text-reset"><u>+228 90 10 45 84</u></a></h6>
                            <h6>51, Rue 35 Saint Joseph, Quartier Saint Joseph</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>POWER OF KNOWLEDGE</h5>
                            <h6><a href="tel:+228 92 10 24 79" class="text-reset"><u>+228 92 10 24 79</u></a></h6>
                            <h6>Adidogomé</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="mb-3 mt-3">
                            <h5>SYMPHONIE D’ADORATION</h5>
                            <h6><a href="tel:+228 22 34 54 25" class="text-reset"><u>+228 22 34 54 25</u></a></h6>
                            <h6>9, Rue Koketi, Assivito</h6>
                            <h6>Lomé – Togo</h6>
                        </div>
                    </div>
                    <hr>
                    @include('adsense.google-ads.ads-sections-home-page')
                </div>
            </div>
        </section>
    </main>

    @include('includes.footer')

@endsection