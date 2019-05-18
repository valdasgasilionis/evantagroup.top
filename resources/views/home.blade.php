@extends('layouts.app')
    @section('content')        
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="turinys">


<!-- Create a button that your customers click to complete their purchase. Customize the styling to suit your branding. -->
    <div class="container align-content-center">   
        <div class="container">
            <div class="turinys"><h2>Sveiki! - Tai jūsų poilsis VANAGUPĖJE!</h2></div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <div class="containter">
                <nav class="navbar navbar-expand-lg navbar-light turinys">
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
        <div class="col-md-9 turinys">
                <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-interval="3000">
                            <img src="/css/Palanga.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item" data-interval="3000">
                            <img src="/css/palanga1.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item" data-interval="3000">
                            <img src="/css/palanga2.jpg" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                        {{-- <span class="carousel-control-prev-icon" aria-hidden="true"></span> --}}
                        <span style="font-size:32px; color:greenyellow" aria-hidden="true">←</span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                        {{-- <span class="carousel-control-next-icon" aria-hidden="true"></span> --}}
                        <span style="font-size:32px; color:greenyellow" aria-hidden="true">→</span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
        </div>    
    </div>
    </main>
</div>
@endsection


