<?php
require '../vendor/autoload.php';
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'AC03cb27728fb0055b67a9fe7bd9e2d826';
$auth_token = '131fffeed54d706660bd6f36f774c19b';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+18572145309";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+15856455810',
    array(
        'from' => $twilio_number,
        'body' => 'It Works!'
    )
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/forgotPwd.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='createaAcctPage'>
    <div class='container'>
        <!-- Landing Section -->
        <section id='create-form'>
            <!-- Header -->
            <div class='header'>
                <h2 id='title' class='centered'>Send A Text</h2>
            </div>

            <!-- Send Password Form -->
            <form id='formContainer' action='textTest.php' method='POST'>
                <div class='inputWithIcon'>
                    <input class='block' id='phone' type = 'text' placeholder= 'Number To Send To' name='phone' required="required" autofocus>
                    <i class='fas fa-phone' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <input class='block' id='email' type = 'text' placeholder= 'Text To Send' name='email' required="required">
                    <i class='fas fa-envelope-square' aria-hidden='true'></i>
                </div>
                <input class='block submit centered' id='submit' type = 'submit' value= 'Create'/>
                <a id='login' href='../index.php'>Log In</a>
            </form>
        </section>
    </div>
</body>
</html>
