@extends('layouts.app')
@section('content')
    <section class="container my-4">
        <h1 class="text-danger">{{ $apartment->title }}</h1>
        <div class="card w-50 bg-dark text-white border-white">
            <img src="{{asset('storage/' . $apartment->cover_img) }}" class="card-img-top" alt="{{ $apartment->title }}">
            <div class="card-body">
                <h5 class="card-title">{{ $apartment->title }}</h5>
                <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-danger">Edit</a>
            </div>
        </div>

        <h4>Payment</h4>
    </section>
@endsection