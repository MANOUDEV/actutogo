@extends('layouts.base')

@section('title') Accueil @endsection

@section('content')

    @include('includes.header')

    <main>

        <section class="position-relative" style="margin-top: -45px; margin-bottom: -25px"> 
            
            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.alaUne.alaUne')

            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.newstogo.newstogo')

            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.national.national')

             @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.PolitiqueSociete.PolitiqueSociete')

             @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.economie.economie')
           
            @include('adsense.google-ads.ads-sections-home-page')
 
            @include('sectionHomePage.Editorial.editorial')

            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.anepasmanquer.anepasmanquer')

            @include('adsense.google-ads.ads-sections-home-page')
 
            @include('sectionHomePage.opinionFaitsDivivers.opinionFaitsDivivers')

            @include('adsense.google-ads.ads-sections-home-page')
           
            @include('sectionHomePage.toutelActualite.toutelActualite')

            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.toutelActualite.all')

        </section>
        @include('includes.newletter')
    </main>
    <br>
    @include('includes.footer')

@endsection
