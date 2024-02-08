@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fs-4 text-danger my-4">
        {{ __('Dashboard') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card bg-dark text-white border-white">
                <div class="card-header border-white">{{ __('User Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <div class="mt-3">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-danger">Control Panel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
