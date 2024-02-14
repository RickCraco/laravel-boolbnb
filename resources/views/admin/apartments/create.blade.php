@extends('layouts.app')
@section('content')
    <section class="container mb-3">
        <h1>Create Apartment</h1>
        @if ($errors->any())
            <div class="alert  alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-black">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.apartments.store') }}"  method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="mb-3">
            <label class="text-white" for="title">Title</label>
            <input type="text" class="form-control w-50 @error('title') is-invalid @enderror" name="title" id="title"
                required minlength="3" maxlength="200">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    <div class="mb-3 d-flex">
        <div>
            <label class="text-white" for="rooms">Rooms</label>
            <br>
            <div class="d-flex align-items-center">
                <input type="number" class="@error('rooms') is-invalid @enderror form-control w-50" name="rooms" id="rooms" min="1" required>
            </div>
            @error('rooms')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="text-white" for="beds">Beds</label>
            <br>
            <div class="d-flex align-items-center">
                <input type="number" class="@error('beds') is-invalid @enderror form-control w-50" name="beds" id="beds" min="1" required>
            </div>
            @error('beds')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="text-white" for="bathrooms">Bathrooms</label>
            <br>
            <div class="d-flex align-items-center">
                <input type="number" class="@error('bathrooms') is-invalid @enderror form-control w-50" name="bathrooms" id="bathrooms" min="1" required>
            </div>
            @error('bathrooms')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="text-white" for="square_meters">Square Meters</label>
        <input type="number" class="form-control w-25 @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters" min="1">
        @error('square_meters')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="address">Address</label>
        <div>
            <input type="text" class="form-control w-50 @error('address') is-invalid @enderror" name="address" id="address" placeholder="Street | House Number | Postal Code | City" list="addressList">
            <datalist id="addressList" class="autocomplete"></datalist>
        </div>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div>
            <img class="w-25" id="uploadPreview" src="https://via.placeholder.com/300x200" alt="PlaceHolder">
        </div>
    </div>

    <div class="mb-3">
        <label class="text-white" for="cover_img">Preview Image</label>
        <input type="file" class="form-control w-25 @error('cover_img') is-invalid @enderror" name="cover_img" id="cover_img">
        @error('cover_img')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="images">Images</label>
        <br>
        <input type="file" class="form-control w-25 @error('images') is-invalid @enderror" name="images[]" id="images" multiple>
        @error('images')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="visible">Visible</label>
        <input type="radio" name="visible" id="visible" value="1">
        <label class="text-white" for="visible">Not Visible</label>
        <input type="radio" name="visible" id="visible" value="0">
        @error('visible')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <h6 class="text-white">Services:</h6>
            <div class="form-group d-flex flex-wrap gap-3">
                @foreach ($services as $service)
                    <div class="form-check @error('services') is-invalid @enderror">
                        @if ($errors->any())
                            <input type="checkbox" class="  form-check-input" name="services[]"
                                value="{{ $service->id }}">
                        @else
                            <input type="checkbox" class="p-0 form-check-input" name="services[]"
                                value="{{ $service->id }}">
                        @endif
                        <label class="form-check-label text-white">
                        {{ $service->name }} <i class="{{ $service->icon }}"></i>
                        </label>
                    </div>
                @endforeach
            </div>
        @error('service_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <button type="reset" class="btn btn-primary">Reset</button>

        </form>
    </section>

<script>
    document.getElementById('address').addEventListener('input', function() {
        const input = this.value.trim();
        if (input.length > 0) {
            const datalist = document.getElementById('addressList');
            datalist.innerHTML = ''; // Pulisce le opzioni precedenti
            fetch('https://api.tomtom.com/search/2/search/' + input + '.json?key=2HI9GWKpWiwAq3zKIGlnZVdmoLe7u7xs')
                .then(response => response.json())
                .then(data => {
                    data.results.forEach(result => {
                        const option = document.createElement('option');
                        option.value = result.address.freeformAddress;
                        datalist.appendChild(option);
                    });
                })
                .catch(error => console.error('Si è verificato un errore durante il recupero dei dati:', error));
        }
    });
</script>


@endsection
