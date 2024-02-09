@extends('layouts.app')
@section('content')
    <section class="container">
        <h1 class="my-4 text-danger">Apartments List</h1>

        <a href="{{ route('admin.apartments.create') }}" class="btn btn-danger mb-4">Add Apartment</a>

        @if(session()->has('message'))
            <div class="alert alert-success mt-4">{{ session()->get('message') }}</div>
        @endif

        <ul class="list-group">
            @foreach($apartments as $apartment)
                <li class="list-group-item d-flex align-items-center justify-content-between bg-dark text-white">
                    {{ $apartment->title }}
                    <div class="d-flex">
                        <a href="{{ route('admin.apartments.show', $apartment) }}" class="btn btn-primary mx-2"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-secondary mx-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST" class="mx-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

    </section>
@endsection