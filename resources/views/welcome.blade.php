@extends('layouts.base')

@section('title') Accueil @endsection

@section('content')

    @include('includes.header')

    <main>

        <section class="position-relative" style="margin-top: -45px; margin-bottom: -25px"> 
            
            @include('sectionHomePage.alaUne.alaUne')

            @include('sectionHomePage.national.national')
            
            @include('sectionHomePage.toutelActualite.toutelActualite')

            @include('sectionHomePage.opinionFaitsDivivers.opinionFaitsDivivers')
            
            @include('sectionHomePage.Editorial.editorial')

        </section>
        @include('includes.newletter')
    </main>
    <br>
    @include('includes.footer')

@endsection
