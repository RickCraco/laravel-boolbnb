@extends('layouts.app')
@section('content')
    <section class="container my-4">
        <h1 class="text-danger">{{ $apartment->title }}</h1>

        @if(session()->has('message'))
            <div class="alert alert-success mt-4">{{ session()->get('message') }}</div>
        @endif

        <div class="card w-50 bg-dark text-white border-white">
            <img src="{{asset('storage/' . $apartment->cover_img) }}" class="card-img-top" alt="{{ $apartment->title }}">
            <div class="card-body">
                <h5 class="card-title">{{ $apartment->title }}</h5>
                <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-danger">Edit</a>
            </div>
        </div>

        <div class="my-4">
            <h2 class="text-white">Visuals</h2>
            <canvas id="visualsChart" width="800" height="400"></canvas>
        </div>

        <div class="text-white">
            @foreach($sponsors as $sponsor)
                <p>{{ $sponsor->name }}</p>
                <a href="{{ route('admin.apartments.payment', ['apartment' => $apartment, 'sponsor_id' => $sponsor->id]) }}" class="btn btn-primary">Pay now</a>
            @endforeach
        </div>

    </section>

    <script>
        const ctx = document.getElementById('visualsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($visuals->pluck('month')) !!},
                datasets: [{
                    label: 'Visualizzazioni per Mese',
                    data: {!! json_encode($visuals->pluck('count')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
