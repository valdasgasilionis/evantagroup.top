<?php

use Illuminate\Http\Request;
use App\Rental;
use App\Notifications\Webhook;

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
        'metadata' => ['rent_id' => $request->id]
    ]);
    $id_number = $request->id;

    return view('checkout', [
        'intent' => $intent,
        'id_number' => $id_number
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
    $number = $id;
    return view('edit', [
        'item' =>  $item
    ]);
});
//UPDATE rental status to 'booked'
Route::get('/rentals/{id}/update', function($id) {
    $rent = Rental::find($id);     
    $rent->reserved = 1;   
    $rent->save();

    return redirect('/rentals');
});
Route::post('/rentals/{id}/finalize', function($id) {
    $case = Rental::find($id);
    $case->paid = 1;
    $case->save();

    return back();
});
//webhook testing STRIPE
Route::post('/webhook', function(Request $request) {
    // Retrieve the request's body and parse it as JSON:
    $request = @file_get_contents('php://input');
    $event_json = json_decode($request, true);
    $id_number = $event_json[0]["metadata"]["rent_id"];
   

    // Do something with $event_json
   
    $user = App\User::find(1);
    $user->notify(new Webhook($id_number));
    // Return a response to acknowledge receipt of the event
   /*  http_response_code(200); */ // PHP 5.4 or greater
    http_response_code(200);
    });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function() {   
    return view('test');
});