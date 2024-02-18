@extends('layouts.app')

@section('content')

<form method="post" action="{{ route('admin.apartments.process', ['apartment' => $apartment]) }}" id="form-payment">
    @csrf
    <input type="hidden" name="sponsor_id" value="{{ $sponsor->id }}">
    <div class="dropin-container" style="display: flex;justify-content: center;align-items: center;"></div>
    <div style="display: flex;justify-content: center;align-items: center; color: white">
        <button type="submit" id="form-button" class="btn btn-sm btn-success">Submit payment</button>
        <input type="hidden" name="nonce" id="nonce">
    </div>
</form>

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