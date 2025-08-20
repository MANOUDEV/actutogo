@extends('layouts.base')

@section('title') Accueil @endsection

@section('content')

    @include('includes.header')

    <main>

        <section class="position-relative" style="margin-top: -45px; margin-bottom: -25px">

            @include('sectionHomePage.alaUne.alaUne')

            @include('sectionHomePage.togoPolitique.togoPolitique')

            @include('sectionHomePage.aNePasManquerTogo.aNePasManquerTogo')

            @include('sectionHomePage.international.international') 
            
            @include('sectionHomePage.societe.societe')

            @include('sectionHomePage.opinionFaitsDivers.opinionFaitsDivers')

            @include('sectionHomePage.important.important')

            @include('sectionHomePage.toutelActualite.toutelActualite')

        </section>
 
        @include('includes.newletter')  
    </main>
    <br>
    @include('includes.footer')

@endsection
