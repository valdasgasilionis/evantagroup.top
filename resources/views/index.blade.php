@extends('layouts.app')
    @section('content')
    <div class="row justify-content-center">
            <div class="col-md-8">
                @if (count($rentals) > 0)            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">start</th>
                                <th scope="col">End</th>
                                <th scope="col">Price</th>
                                <th scope="col">Available?</th>
                                    @if (auth()->check())
                                        <th scope="col">Paid?</th>
                                    @endif
                            </tr>
                        </thead>
                        <tbody>                        
                            @foreach ($rentals as $rent)
                                <tr id="{{$rent->paid ? "rent-paid" : ""}}">
                                    <th scope="row">{{$rent->id}}</th>
                                    <td>{{$rent->start}}</td>
                                    <td>{{$rent->end}}</td>
                                    <td>{{$rent->price}}</td>
                                    <td>
                                        @if ($rent->reserved === 0)
                                            <a href="rentals/{{$rent->id}}/edit">Book it</a>
                                        @else
                                            {{$rent->reserved ? "no" : "yes"}}
                                        @endif
                                    </td>
                                        @if (auth()->check())
                                    <td>
                                        <form action="/rentals/{{$rent->id}}" method="POST">
                                            @csrf
                                            <input type="checkbox" {{$rent->paid ? "checked" : ""}} onChange='this.form.submit()'>
                                        </form>
                                    </td>
                                        @endif
                                </tr>
                            @endforeach    
                        </tbody>
                    </table>          
                @endif           
            </div>
        @if (auth()->check())
            <div class="col-md-8">
                <form action="/rentals" method="post">
                    @csrf
                    <div class="form-row">           
                        <div class="col">
                            <label for="start date">start date</label>
                            <input type="date" name="start" class="form-control">
                        </div>
                        <div class="col">
                            <label for="end date">end date</label>
                            <input type="date" name="end" class="form-control">
                        </div>
                        <div class="col">
                            <label for="price">price</label>
                            <input type="number" name="price" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="submit" id="button" class="btn btn-warning" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
    @endsection