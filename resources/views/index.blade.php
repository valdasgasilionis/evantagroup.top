@extends('layouts.app')
    @section('content')
    <div class="row justify-content-center">
            <div class="col-md-8">
                @if (count($rentals) > 0)            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                    @if (auth()->check())
                                <th scope="col">ID</th>    
                                    @endif                                
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
                                <tr>
                                        @if (auth()->check())
                                    <th scope="row">{{$rent->id}}</th>    
                                        @endif                                    
                                    <td>{{$rent->start}}</td>
                                    <td>{{$rent->end}}</td>
                                    <td>{{$rent->price}}.00 eur</td>
                                    <td>
                                        @if (empty($rent->notes))
                                            @if ($rent->reserved === 0)
                                                <a href="rentals/{{$rent->id}}/edit" class="text-primary font-weight-bold">Book it</a>
                                            @endif 
                                            @if ($rent->reserved === 1)
                                                <span class="text-warning font-italic">Reserved</span>
                                            @endif                                           
                                        @elseif(!empty($rent->notes)) 
                                            <span><i>Not Available</i></span>
                                        @endif
                                    </td>
                                        @if (auth()->check())
                                    <td>
                                            @if ($rent->reserved === 1)
                                                <form action="/rentals/{{$rent->id}}/finalize" method="POST">
                                                    @csrf
                                                <input type="checkbox" {{$rent->paid ? "checked" : ""}} onChange='this.form.submit()'>
                                                </form>
                                            @endif                                        
                                    </td>
                                    <td>
                                        <a href="/rentals/{{$rent->id}}/modify">Edit</a>
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