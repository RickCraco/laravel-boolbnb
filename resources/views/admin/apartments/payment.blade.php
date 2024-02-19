@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="text-white">You are boosting {{ $apartment->title }}</h2>
    <h3 class="text-white">Sponsor: {{ $sponsor->name }}</h3>
    <h3 class="text-secondary">Price: {{ $sponsor->price }}$</h3>
    <form method="post" action="{{ route('admin.apartments.process', ['apartment' => $apartment]) }}" id="form-payment">
        @csrf
        <input type="hidden" name="sponsor_id" value="{{ $sponsor->id }}">
        <div class="dropin-container" style="display: flex;justify-content: center;align-items: center;"></div>
        <div style="display: flex;justify-content: center;align-items: center; color: white">
            <button type="submit" id="form-button" class="btn  btn-success">Submit payment</button>
            <input type="hidden" name="nonce" id="nonce">
        </div>
    </form>
</div>

<script>
    const form = document.querySelector('#form-payment');
        braintree.dropin.create({
            authorization: "{{ $clientToken }}",
            container: ".dropin-container"
        },(error, dropinInstance) => {
            if(error){
                console.log(error);
            }
            form.addEventListener('submit', event => {
                event.preventDefault();
                dropinInstance.requestPaymentMethod((error, payload) => {
                    if(error){
                        console.log(error);
                    }
                    document.querySelector('#nonce').value = payload.nonce;
                    document.querySelector('#form-button').style.display = 'none';
                    form.submit();
                })
            })
        })
</script>
@endsection
