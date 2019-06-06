<?php

namespace App\Http\Controllers;

use Aws\Credentials\CredentialProvider;
use Illuminate\Http\Request;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class CustomEmailController extends Controller
{
    public function input() {
        return view('mail.myemail');
    }

    public function send() {
        //require vendor autoload
        include('../vendor/autoload.php');

        dd('hit passed');
    }
}
