@extends('layouts.app')
@section('content')
    <section class="container">
        @auth
            <h1>Benvenuto admin {{ Auth::user()->name }}</h1>
        @else
            <h1>Ti diamo il benvenuto utente</h1>
        @endauth
    </section>
@endsection
