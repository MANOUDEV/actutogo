@extends('layouts.base')

@section('title') Accueil @endsection

@section('content')

    @include('includes.header')

    <main>
 

        @include('includes.newletter')
    </main>
    @include('includes.footer')

@endsection
