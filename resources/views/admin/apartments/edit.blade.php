@extends('layouts.app')
@section('content')
    <section class="container my-4">
        <h1 class="text-white">Edit {{$apartment->title}} <span class="fs-6 ms-3 text-danger"> Fields marked with * are required!</span></h1>
        @if ($errors->any())
            <div class="alert  alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-black">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="mt-5" action="{{ route('admin.apartments.update', $apartment) }}"  method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mx-0">
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <label class="text-white" for="title">Title *</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
                        required minlength="3" maxlength="200" value="{{ old('title', $apartment->title) }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            <div class="mb-3 d-flex justify-content-evenly gap-4">
                <div class="">
                    <label class="text-white" for="rooms">Rooms *</label>
                    <input type="number" min="1" class="form-control @error('rooms') is-invalid @enderror" name="rooms" id="rooms" value="{{ old('rooms', $apartment->rooms) }}" required>
                    @error('rooms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="">
                    <label class="text-white" for="beds">Beds *</label>
                    <input type="number" min="1" class="form-control @error('beds') is-invalid @enderror" name="beds" id="beds" value="{{ old('beds', $apartment->beds) }}" required>
                    @error('beds')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="">
                    <label class="text-white" for="bathrooms">Bathrooms *</label>
                    <input type="number" min="1" class="form-control @error('bathrooms') is-invalid @enderror" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}" required>
                    @error('bathrooms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="text-white" for="square_meters">Square Meters *</label>
                <input type="number" min="1" class="form-control  @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters" value="{{ old('square_meters', $apartment->square_meters) }}">
                @error('square_meters')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <p class="text-white">Visibility *</p>
                <label class="text-white" for="visible">Visible</label>
                <input type="radio" name="visible" id="visible" value="1" {{ old('visible', $apartment->visible) ? 'checked' : '' }}>
                <label class="text-white" for="visible">Not Visible</label>
                <input type="radio" name="visible" id="visible" value="0" {{ old('visible', $apartment->visible) ? '' : 'checked' }}>
                @error('visible')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <h6 class="text-white">Services *</h6>
                    <div class="form-group row mx-0 gy-3">
                        @foreach ($services as $service)
                            <div class="form-check col-6 @error('services') is-invalid @enderror">
                                @if ($errors->any())
                                    <input type="checkbox" class="  form-check-input" name="services[]"
                                        value="{{ $service->id }}"
                                        {{ in_array($service->id, old('services', $apartment->services->pluck('id')->toArray())) ? 'checked' : '' }}>
                                @else
                                    <input type="checkbox" class="p-0 form-check-input" name="services[]"
                                        value="{{ $service->id }}"
                                        {{ $apartment->services->contains($service->id) ? 'checked' : '' }}>
                                @endif
                                <label class="form-check-label text-white">
                                {{ $service->name }}
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
                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address', $apartment->address) }}" list="addressList">
                    <datalist id="addressList" class="autocomplete"></datalist>
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
                    <input type="file" class="form-control @error('cover_img') is-invalid @enderror" name="cover_img" id="cover_img" value="{{old('cover_img', $apartment->cover_img)}}">
                    @error('cover_img')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                            <label class="text-white" for="images">Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" name="images[]" id="images" multiple accept="images/*" value="{{old('images', $apartment->images)}}">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="text-white" for="desc">Description</label>
                    <textarea class="form-control mt-2 @error('desc') is-invalid @enderror" name="desc" id="desc" rows="5">{{ old('title', $apartment->desc) }}</textarea>
                    @error('desc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
</script>
@endsection
