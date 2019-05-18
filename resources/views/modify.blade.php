@extends('layouts.app')
    @section('content')
        @if (auth()->check())
            <div class="col-md-8">
                <form action="/modify" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label>RENT ID number {{$rent->id}}</label>
                            <input type="hidden" name="id" class="form-control" value="{{$rent->id}}">
                        </div>
                    </div>
                    <div class="form-row">           
                        <div class="col">
                            <label for="start date">start date</label>
                            <input type="date" name="start" class="form-control" value="{{$rent->start}}">
                        </div>
                        <div class="col">
                            <label for="end date">end date</label>
                            <input type="date" name="end" class="form-control" value="{{$rent->end}}">
                        </div>
                        <div class="col">
                            <label for="price">price</label>
                            <input type="number" name="price" class="form-control" value="{{$rent->price}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="submit" id="button" class="btn btn-warning" value="Modify">
                        </div>
                </form>
                <form action="/delete" method="POST">
                    @csrf
                    <input type="hidden" name="id" class="form-control" value="{{$rent->id}}">
                        <div class="col">
                            <input type="submit" id="button" class="btn btn-danger" value="DELETE">
                        </div>
                </form>
            </div>
        @endif
    @endsection