<?php

namespace App\Http\Controllers;

use Aws\Credentials\CredentialProvider;
use Illuminate\Http\Request;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class CustomVerificationController extends Controller
{
    public function mail() {
    //require vendor autoload
        include('../vendor/autoload.php');
    //initiate client with credentials inside .aws/credentials file under [default]
        $SesClient = new SesClient([
            'profile' => 'default',
            'version' => '2010-12-01',
            'region' => 'eu-west-1',
        ]);
    //input parameters
    $EmailAddress = 'ugnegasi2010@gmail.com';
    $TemplateName = 'custom_email';

    try {
        $result = $SesClient->sendCustomVerificationEmail([
            'EmailAddress' => $EmailAddress,
            'TemplateName' => $TemplateName,
        ]);
    } catch (AwsException $e) {
                // output error message if fails
                echo $e->getMessage();
                echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                echo "\n";
            } 
    }
}
