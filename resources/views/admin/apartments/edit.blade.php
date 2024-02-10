@extends('layouts.app')
@section('content')
    <section class="container">
        <h1>Edit {{$apartment->title}}</h1>
        <form action="{{ route('admin.apartments.update', $apartment) }}"  method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
     <div class="mb-3">
            <label class="text-white" for="title">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
                required minlength="3" maxlength="200" value="{{ old('title', $apartment->title) }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="rooms">Rooms</label>
        <input type="text" class="form-control @error('rooms') is-invalid @enderror" name="rooms" id="rooms" value="{{ old('rooms', $apartment->rooms) }}">
        @error('rooms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="beds">Beds</label>
        <input type="text" class="form-control @error('beds') is-invalid @enderror" name="beds" id="beds" value="{{ old('beds', $apartment->beds) }}">
        @error('beds')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="bathrooms">Bathrooms</label>
        <input type="text" class="form-control @error('bathrooms') is-invalid @enderror" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}">
        @error('bathrooms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="square_meters">Square Meters</label>
        <input type="text" class="form-control @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters" value="{{ old('square_meters', $apartment->square_meters) }}">
        @error('square_meters')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="text-white" for="address">Address</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address', $apartment->address) }}">
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

    <div class="mb-3">
        <label class="text-white" for="visible">Visible</label>
        <input type="radio" name="visible" id="visible" value="1" {{ old('visible', $apartment->visible) ? 'checked' : '' }}>
        <label class="text-white" for="visible">Not Visible</label>
        <input type="radio" name="visible" id="visible" value="0" {{ old('visible', $apartment->visible) ? 'checked' : '' }}>
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
                                value="{{ $service->id }}"
                                {{ in_array($service->id, old('services', $apartment->services)) ? 'checked' : '' }}>
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

    <button type="submit" class="btn btn-success">Save</button>
    <button type="reset" class="btn btn-primary">Reset</button>

        </form>
    </section>
@endsection