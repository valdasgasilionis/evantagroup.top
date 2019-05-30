@extends('layouts.app')
    @section('content')        
    
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
                @if (session('newuser'))
                    <div class="alert alert-success" role="alert">
                        {{ session('newuser') }}
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
    @if (auth()->check())
        
   
    @if (auth()->user()->isAdmin())

    <div class="container-fluid turinys">
        <h3>List of users</h3>
            <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">email</th>
                        <th scope="col">Registration date</th>
                        <th scope="col">Verify</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                          @foreach ($vartotojai as $vartotojas)
                            @if ($vartotojas->email_verified_at === null)           
                                <tr>
                                    <td>{{$vartotojas->id}}</th>
                                    <td>{{$vartotojas->email}}</td>
                                    <td>{{$vartotojas->created_at}}</td>
                                    <td>
                                        <form action="/verify_user"  method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$vartotojas->id}}">
                                            <input type="checkbox" name="email_verified_at" onchange='this.form.submit()'>
                                       {{--  {{$vartotojas->email_verified_at ? "yes" : "no"}} --}}
                                        </form>
                                    </td> 
                                </tr>
                            @endif     
                          @endforeach             
                    </tbody>
                  </table>
    </div> 
    @endif 
    @endif
@endsection


