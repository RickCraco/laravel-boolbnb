@extends('layouts.app')
@section('content')

<div class="container my-5 pt-5">
    <h2 class="text-center pb-2 text-white">{{ $message->name }} {{ $message->surname }}</h2>
    <div class="row d-flex justify-content-center">
        <div class="card px-0">
            <div class="card-header">
              <h3 class="ms-3">{{ $message->email }}</h3>
            </div>
            <ul class="list-group list-group-flush">
                @if($message->phone_number)
                    <li class="list-group-item fs-5 fw-bold ms-3">Phone number: <br> <span class="fw-normal">{{ $message->phone_number }}</span></li>
                @endif
                <li class="list-group-item fs-5 fw-bold ms-3">Message: <br> <span class="fw-normal">{{ $message->body }}</span></li>
            </ul>
          </div>
    </div>

</div>

@endsection
