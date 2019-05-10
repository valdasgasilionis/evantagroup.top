@extends('layouts.app')

@section('content')
<div class="container">
   
        <div class="col-md-12">
            <div><h1 class="text-center">Sveiki! - Tai jūsų poilsis VANAGUPĖJE!</h1></div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <div class="containter">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="/rentals">REZERVACIJA</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/galery">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/policies">Policies</a>
                        </li>
                        </ul>
                    </div>
                </nav>              
            </div>
        </div>
    
</div>
@endsection
