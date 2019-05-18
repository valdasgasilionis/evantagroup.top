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
//first let's check if reserved status is still - available
    $rent_id_number = $request->id;
    $rental = Rental::find($rent_id_number);
    if ($rental->reserved === 0) {        
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $request->eur.$request->ct,
            'currency' => 'eur',
            'metadata' => ['rent_id' => $request->id]
        ]);
        /* $id_number = $request->id; */

        return view('checkout', [
            'intent' => $intent,
            'id' => $request->id
    ]);
    } else {
        return redirect('/booked');
    }
    
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
    if ($item->reserved === 0) {
        return view('edit', [
        'item' =>  $item
    ]);
    } else {
        return redirect('/booked');
    }
    
    
});
//UPDATE rental status to 'booked'
/* Route::get('/rentals/{id}/update', function($id) {
    $rent = Rental::find($id);     
    $rent->reserved = 1;   
    $rent->save();

    return redirect('/rentals');
}); */
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
    $id_number = $event_json["data"]["object"]["metadata"]["rent_id"]; // this is how to access rent id number
    $webhook_id = $event_json["id"];

    // Do something with $event_json
        //find rental id number from webhook data
        //insert webhook id into notes collumn;
        //update status to reserved ->yes;
    $rental = Rental::find($id_number);
    $rental->notes = $webhook_id;
    /* $rental->reserved = 1; */
    $rental->save(); 

        //create notification -> now it is stored in notifications table only;
    $user = App\User::find(1);
    $user->notify(new Webhook($id_number));
    // Return a response to acknowledge receipt of the event
    // PHP 5.4 or greater
    http_response_code(200);
    });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function() { 
    return view('test');
});
Route::get('/booked', function() {
    return view('booked');
});
// AJAX testing
/* Route::get('ajax', function() {
   
    return view('ajax'); 
}); */
Route::post('ajax', function(Request $request) {
    $rental = Rental::find($request);      
    $var = json_encode($rental);
   
    //send ajax response and process payment
    return response()->json($var);    
});

Route::post('/intent', function(Request $request) {
    $request = @file_get_contents('php://input');
    $event_json = json_decode($request, true);
    $id_number = $event_json["data"]["object"]["metadata"]["rent_id"]; // this is how to access rent id number
    $webhook_id = $event_json["id"];
//update reserved status to lock for payment processing
    $rental = Rental::find($id_number);
    $rental->reserved = 1;
    $rental->save();

// Do something with $event_json

// Return a response to acknowledge receipt of the event
http_response_code(200); // PHP 5.4 or greater
});
//change status back to reserved->0 if redirected form expired session
Route::get('/expired/{id}', function($id) {
    $rental = Rental::find($id);
//check if no webhook was stored for the proccessed payment, only then restore reserved status to 0
    if (empty($rental->notes)) {
       $rental->reserved = 0;
    $rental->save();    
    }
     return redirect('/rentals');
});
