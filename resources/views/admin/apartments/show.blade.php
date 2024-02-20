@extends('layouts.app')
@section('content')
    <section class="container my-4">
        <div class="d-flex gap-4 align-items-center">
            <h1 class="text-white">{{ $apartment->title }}</h1>
            @if($apartment->sponsors->count() > 0)
                <div>
                    <span class="badge rounded-pill text-bg-warning text-uppercase"><i class="fa-solid fa-crown"></i> premium</span>
                </div>
            @endif
            <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-success">Edit</a>
        </div>


        @if(session()->has('message'))
            <div class="alert alert-success mt-4">{{ session()->get('message') }}</div>
        @endif

        <div class="text-white row mt-3">
            <div class="col-12 col-md-6">
                <img src="{{asset('storage/' . $apartment->cover_img) }}" class="card-img-top w-100 rounded-4" alt="{{ $apartment->title }}">
            </div>
            <div class="col-12 col-md-6 pt-5 pt-md-0">
                <div>
                    <p><i class="fa-solid fs-4 fa-location-dot me-3"></i> {{ $apartment->address }} ({{ $apartment->lat }}, {{ $apartment->lon }})</p>
                </div>
                <div class="d-flex gap-5 mt-5">
                    <p><i class="fa-solid fs-4 fa-couch"></i> {{ $apartment->rooms }}</p>
                    <p><i class="fa-solid fs-4 fa-bed"></i> {{ $apartment->beds }}</p>
                    <p><i class="fa-solid fs-4 fa-shower"></i> {{ $apartment->bathrooms }}</p>
                </div>
                <div class="mt-3">
                    <h2 class="mb-3">Services:</h2>
                    <ul class="list-unstyled d-flex flex-wrap">
                        @foreach ($apartment->services as $item)
                            <li class="fs-4 col-12 col-md-6 col-lg-4 my-2"><i class="{{ $item->icon }} me-2"></i>{{ $item->name }}</li>
                        @endforeach
                    </ul>
                </div>
                @if($apartment->sponsors->count() > 0)
                    <div class="mt-3">
                        <h2 class="mb-3">Premium until:</h2>
                        <p>{{$apartment->sponsors->last()->pivot->end_date}}</p>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <p class="d-inline-flex gap-1 mt-4">
                <button class="btn btn-success fs-4 text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  Boost your apartment
                </button>
            </p>
              <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <h2 class="text-center pb-3">Our Plans</h2>
                    <div class="text-white d-flex justify-content-between">
                        @foreach($sponsors as $sponsor)
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                              <h5 class="card-title">{{ $sponsor->name }}</h5>
                              <h6 class="card-subtitle mb-2 text-body-secondary">Price: {{ $sponsor->price }}$</h6>
                              <p class="card-text">Duration: {{ $sponsor->duration }} @if($sponsor->duration > 1)Days @else Day @endif</p>
                              <a href="{{ route('admin.apartments.payment', ['apartment' => $apartment, 'sponsor_id' => $sponsor->id]) }}" class="btn btn-success">Pay now</a>
                            </div>
                          </div>
                        @endforeach
                    </div>
                </div>
              </div>
            <div>
        </div>

            <div class="accordion my-5" id="accordionExample">
                <h2 class="text-white">Your Inbox</h2>
                @if ($apartment->messages->count() > 0)
                <table class="table">
                    <thead>
                      <tr>
                        <th class="text-center" scope="col">Email</th>
                        <th class="text-center" scope="col">Name</th>
                        <th class="text-center" scope="col">Surname</th>
                        <th class="text-center" scope="col">Date</th>
                        <th class="text-center" scope="col"><i class="fa-solid fa-gear"></i></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($apartment->messages->sortByDesc('created_at') as $item)
                        <tr>
                            <td class="text-center" scope="row">{{ $item->email }}</td>
                            <td class="text-center">{{ $item->name }}</td>
                            <td class="text-center">{{ $item->surname }}</td>
                            <td class="text-center">{{ $item->created_at }}</td>
                            <td class="text-center"><a href="{{ route('admin.messages.show', $item) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                  </table>
                @else
                    <h4 class="text-white">Your inbox is empty :(</h4>
                @endif

            </div>

        </div>

        <div class="my-4">
            <h2 class="text-white">Stats</h2>
            <canvas id="visualsChart" width="800" height="400"></canvas>
        </div>



    </section>

    <script>
        const ctx = document.getElementById('visualsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($visuals->pluck('month')) !!},
                datasets: [{
                    label: 'Visuals per month',
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
