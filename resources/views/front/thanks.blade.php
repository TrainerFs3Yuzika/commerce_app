@extends('front.layouts.app')
@section('content')

    <section class="container">
        <div class="col-md-12 text-center py-5">
            
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <img src="{{ asset('front-assets/images/Thanks-foto.png') }}" style="width:500px;" >
            <p>Pesanan Kamu dengan Id: {{$id}} </p>
        </div>
    </section>
@endsection