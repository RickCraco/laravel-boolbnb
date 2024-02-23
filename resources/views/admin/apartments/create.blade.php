@extends('layouts.app')
@section('content')
    <section class="container my-4 px-0">
        <h1 class="text-white ps-2">Create Apartment <br> <span class="fs-6 text-danger"> Fields marked with * are required!</span></h1>
        @if ($errors->any())
            <div class="alert  alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-black">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="mt-5 " action="{{ route('admin.apartments.store') }}"  method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row mx-0">
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <label class="text-white" for="title">Title *</label>
                    <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
                        required minlength="3" maxlength="200">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 d-flex justify-content-around gap-5">
                    <div>
                        <label class="text-white" for="rooms">Rooms *</label>
                        <br>
                        <div class="d-flex align-items-center">
                            <input type="number" value="{{ old('rooms') }}" class="@error('rooms') is-invalid @enderror form-control" name="rooms" id="rooms" min="1" required>
                        </div>
                        @error('rooms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-white" for="beds">Beds *</label>
                        <br>
                        <div class="d-flex align-items-center">
                            <input type="number" value="{{ old('beds') }}" class="@error('beds') is-invalid @enderror form-control" name="beds" id="beds" min="1" required>
                        </div>
                        @error('beds')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-white" for="bathrooms">Bathrooms *</label>
                        <br>
                        <div class="d-flex align-items-center">
                            <input type="number" value="{{ old('bathrooms') }}" class="@error('bathrooms') is-invalid @enderror form-control" name="bathrooms" id="bathrooms" min="1" required>
                        </div>
                        @error('bathrooms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-white" for="square_meters">Square Meters *</label>
                    <input type="number" value="{{ old('square_meters') }}" class="form-control  @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters" min="1" required>
                    @error('square_meters')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <p class="text-white">Visibility *</p>
                    <label class="text-white" for="visible">Visible</label>
                    <input type="radio" name="visible" id="visible" value="1" required>
                    <label class="text-white" for="visible">Not Visible</label>
                    <input type="radio" name="visible" id="visible" value="0">
                    @error('visible')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <h6 class="text-white">Services *</h6>
                        <div class="form-group row gy-3 mx-0">
                            @foreach ($services as $service)
                                <div class="form-check col-6 @error('services') is-invalid @enderror">
                                    @if ($errors->any())
                                        <input type="checkbox" class="form-check-input services" name="services[]"
                                            value="{{ $service->id }}" {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                                    @else
                                        <input type="checkbox" class="form-check-input services" name="services[]"
                                            value="{{ $service->id }}" {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
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

            </div>
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <label class="text-white" for="address">Address *</label>
                    <div>
                        <input type="text" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" name="address" id="address" placeholder="Street | House Number | Postal Code | City" list="addressList" required>
                        <datalist id="addressList" class="autocomplete"></datalist>
                    </div>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div>
                        <img  id="uploadPreview" src="https://via.placeholder.com/300x200" alt="PlaceHolder">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-white" for="cover_img">Preview Image</label>
                    <input type="file" class="form-control @error('cover_img') is-invalid @enderror" name="cover_img" id="cover_img">
                    @error('cover_img')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="text-white" for="images">Images</label>
                    <br>
                    <input type="file" class="form-control @error('images') is-invalid @enderror" name="images[]" id="images" multiple>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="text-white" for="desc">Description</label>
                <textarea class="form-control mt-2 @error('desc') is-invalid @enderror" name="desc" id="desc" rows="5">{{ old('desc') }}</textarea>
                @error('desc')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="d-flex justify-content-center gap-3 mt-5">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="reset" class="btn btn-primary">Reset</button>
        </div>
        </form>
    </section>

<script>
    document.getElementById('address').addEventListener('input', function() {
        const input = this.value.trim();
        if (input.length >= 4) {
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

    function controlServices() {
        const services = document.querySelectorAll('.services');
        const submitButton = document.querySelector('.btn-success');

        const oneChecked = Array.from(services).some(function(checkbox) {
            return checkbox.checked;
        });

        if (oneChecked) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        controlServices();

        const services = document.querySelectorAll('.services');
        services.forEach(function(checkbox) {
            checkbox.addEventListener('change', controlServices);
        });
    });


</script>


@endsection
