@extends('layouts.base')

@section('title') Accueil @endsection

@section('content')

    @include('includes.header')

    <main>

        <section class="position-relative" style="margin-top: -45px; margin-bottom: -25px"> 
           
            
            @include('adsense.pub')
           
            @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.alaUne.alaUne')

             @include('adsense.google-ads.ads-sections-home-page')

             
@include('adsense.pub')

            @include('sectionHomePage.national.national')

             @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.PolitiqueSociete.PolitiqueSociete')

             @include('adsense.google-ads.ads-sections-home-page')

            @include('sectionHomePage.economie.economie')

            
@include('adsense.pub')
           
            @include('adsense.google-ads.ads-sections-home-page')

            
            @include('sectionHomePage.Editorial.editorial')

 
            @include('sectionHomePage.opinionFaitsDivivers.opinionFaitsDivivers')

            
@include('adsense.pub')

            @include('adsense.google-ads.ads-sections-home-page')
            
            @include('sectionHomePage.toutelActualite.toutelActualite')

             @include('adsense.google-ads.ads-sections-home-page')

             
@include('adsense.pub')

        </section> 
    </main>
    <br>
    @include('includes.footer')

@endsection
