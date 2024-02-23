@extends('layouts.app')
@section('content')

<div class="container my-5 pt-5">
    {{-- <h2 class="text-center pb-2 text-white">{{ $message->name }} {{ $message->surname }}</h2>
    <div class="row d-flex justify-content-center">
        <div class="card px-0">
            <div class="card-header">
              <h3 class="ms-3">{{ $message->email }}</h3>
            </div>
            <ul class="list-group list-group-flush">
                @if($message->phone_number)
                    <li class="list-group-item fs-5 fw-bold ms-3">Phone number: <br> <span class="fw-normal">{{ $message->phone_number }}</span></li>
                @endif
                <li class="list-group-item fs-5 fw-bold ms-3">Message: <br> <span class="fw-normal">{{ $message->body }}</span></li>
            </ul>
          </div>
    </div> --}}
    <center class="wrapper">
        <table class="top-panel center" width="602" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td class="title text-white fs-2" width="300">BoolBnB</td>
                <td class="subject" width="300"><a class="strong text-white" href="#" target="_blank">www.BoolBnB.com</a></td>
            </tr>
            <tr>
                <td class="border" colspan="2">&nbsp;</td>
            </tr>
            </tbody>
        </table>

        <div class="spacer">&nbsp;</div>

        <table class="main center" width="602" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td class="column">
                    <div class="column-top">&nbsp;</div>
                    <table class="content" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="padded">
                              <h1>Email Received From:</h1>

                              <table style="width:100%">
                              <tr>
                                <td><strong>Name</strong></td>
                                <td>{{ $message->name }}</td>
                              </tr>
                              <tr>
                                <td><strong>Surname</strong></td>
                                <td>{{ $message->surname }}</td>
                              </tr>
                              @if ($message->phone_number)
                                <tr>
                                    <td><strong>Phone Number<strong></td>
                                    <td>{{ $message->phone_number }}</td>
                                </tr>
                              @endif
                              <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $message->email }}</td>
                              </tr>
                              <tr>
                                <td><strong>Received At:</strong></td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                              </tr>
                            </table><br>
                            <p>Message:</p>
                              <p class="caption fs-6">{{ $message->body }}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="column-bottom">&nbsp;</div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="spacer">&nbsp;</div>

        <table class="footer center" width="602" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td class="border" colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="subscription" width="300">
                    <div>
                        <img src="public/img/logo.png" alt="logo" width="70" height="70">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </center>

</div>

@endsection
