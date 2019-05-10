<?php

use Illuminate\Http\Request;
use App\Rental;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {   
    return view('home');
});

Route::post('/charge', function (Request $request) {
    // Set your secret key: remember to change this to your live secret key in production
    // See your keys here: https://dashboard.stripe.com/account/apikeys
    \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

    $intent = \Stripe\PaymentIntent::create([
        'amount' => $request->eur.$request->ct,
        'currency' => 'eur',
    ]);
    return view('checkout', [
        'intent' => $intent
    ]);
});

Route::get('/rentals', function() {
    $rentals = Rental::all();
    return view('index', [
        'rentals'=> $rentals
    ]);
});

Route::post('/rentals', function(Request $request) {
    $rental = new Rental;
    $rental->start = $request->start;
    $rental->end = $request->end;
    $rental->price = $request->price;

    $rental->save();

    return back();
});

Route::get('/rentals/{id}/edit', function($id) {
    $item = Rental::find($id);
    /* return $item; */
    return view('edit', [
        'item' =>  $item
    ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
