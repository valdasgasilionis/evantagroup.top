@extends('layouts.app')
    @section('content')
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form action="/charge" method="post">                
                    @csrf
                        <div class="form-group row">
                            <label for="price" class="col-sm-6 col-form-label">
                     You are booking 7 day rent starting from {{$item->start}} till {{$item->end}}. Price is: </label>
                                <div class="col-sm-2">
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                    <input type="text" class="form-control" name="eur" value="{{$item->price}}" readonly>
                                </div>
                                <label class="col-sm-1 col-form-label">eur</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control" name="ct" value="00"  readonly>                                 
                                </div>
                                    <label class="col-sm-1 col-form-label">ct</label>
                            <button type="submit" class="btn btn-primary col-sm-1">Pay</button>
                        </div>                 
                </form>
            </div>
        </div>
    @endsection