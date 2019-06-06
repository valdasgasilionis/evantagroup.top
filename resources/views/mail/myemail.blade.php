{{-- form to submitt field data for the custom email --}}
@extends('layouts.app')
@section('content') 
<div class="container fluid">

    <form action="/myemail" method="post">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name ="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Jūsų amžius (0-99)</label>
            <input type="number" name="age" class="form-control" id="exampleInputPassword1" min="0" max="99">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="agree" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Sutinkate su privatumo sąlygomis</label>
        </div>
        <div class="form-group form-check">
            <button type="submit" class="btn btn-primary">Pateikti (bus išsiųstas laiškas jūsų e-paštu)</button>
        </div>
    </form>
</div>
  
@endsection