@extends('layouts.app')
@section('content')
    <section class="container">
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
        <form action="{{ route('admin.apartments.store') }}"  method="POST" enctype="multipart/form-data">
        @csrf
     <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
                required minlength="3" maxlength="200">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
    </div>

    <div class="mb-3">
        <label for="rooms">Rooms</label>
        <input type="text" class="form-control @error('rooms') is-invalid @enderror" name="rooms" id="rooms">
        @error('rooms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="beds">Beds</label>
        <input type="text" class="form-control @error('beds') is-invalid @enderror" name="beds" id="beds">
        @error('beds')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="bathrooms">Bathrooms</label>
        <input type="text" class="form-control @error('bathrooms') is-invalid @enderror" name="bathrooms" id="bathrooms" >
        @error('bathrooms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="square_meters">Square Meters</label>
        <input type="text" class="form-control @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters">
        @error('square_meters')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address">Address</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address">
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
        <label for="cover_img">Preview Image</label>
        <input type="file" class="form-control @error('cover_img') is-invalid @enderror" name="cover_img" id="cover_img">
        @error('cover_img')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="images">Images</label>
        <input type="file" class="@error('images') is-invalid @enderror" name="images[]" id="images" multiple>
        @error('images')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="visible">Visible</label>
        <input type="radio" name="visible" id="visible" value="1">
        @error('visible')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <h6>Services:</h6>
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
                        <label class="form-check-label">
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