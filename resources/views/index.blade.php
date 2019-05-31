@extends('layouts.app')
    @section('content')
    <div class="row justify-content-center">
            <div class="col-md-8">
                @if (count($rentals) > 0)            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                    @if (auth()->check())
                                        @if (auth()->user()->isAdmin())
                                            <th scope="col">ID</th>  
                                        @endif  
                                    @endif                                
                                <th scope="col">start</th>
                                <th scope="col">End</th>
                                <th scope="col">Price</th>
                                    @if (auth()->check())
                                        @if (auth()->user()->isAdmin())
                                <th scope="col">Available?</th>                                    
                                <th scope="col">Paid?</th> 
                                        @endif                                
                                    @endif
                            </tr>
                        </thead>
                        <tbody>                        
                            @foreach ($rentals as $rent)
                                <tr>
                                        @if (auth()->check())
                                            @if (auth()->user()->isAdmin())
                                    <td>{{$rent->id}}</td>
                                            @endif                                       
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
                {{-- if auth user is admin he can finalize the case and edit the case--}}
                                        @if (auth()->check())
                                            @if (auth()->user()->isAdmin())
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
                                        @endif
                {{-- end code for the admin privileges --}}
                                </tr>
                            @endforeach    
                        </tbody>
                    </table>          
                @endif           
            </div>
{{-- if auth user is admin - he can enter new rental items --}}
        @if (auth()->check())
            @if (auth()->user()->isAdmin())
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
        @endif
    </div>
    @endsection