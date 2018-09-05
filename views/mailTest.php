<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

//need to set time
date_default_timezone_set('Etc/EST');

//new instance
$mail = new PHPMailer(TRUE);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

/* Username (email address). */
$mail->Username = 'teampearlrit@gmail.com';

/* Google account password. */
$mail->Password = 'T3@mP3@rl!';

try{
    $mail->setFrom('team-pearl-rit@gmail.com', 'RIT Team Pearl');

/* Add a recipient. */
    $mail->addAddress('grd2747@g.rit.edu', 'Gavin');

/* Set the subject. */
    $mail->Subject = 'test';

/* Set the mail message body. */
    $mail->Body = 'TESTING';

/* Finally send the mail. */
    $mail->send();
}
catch (\Exception $e)
{
   /* PHP exception (note the backslash to select the global namespace Exception class). */
   echo $e->getMessage();
}
//Set to SMTP
//$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Fill in authentication details here
//Either the gmail account owner, or the user that gave consent
$email = 'grd2747@g.rit.edu';
$clientId = '610257855588-gsa0t6it7a4lbnrnsjg96qlisn5gnr1c.apps.googleusercontent.com';
$clientSecret = 'ver-4-zzzuGown_XomQuLl10';
//Obtained by configuring and running get_oauth_token.php
//after setting up an app in Google Developer Console.
$refreshToken = 'RANDOMCHARS-----DWxgOvPT003r-yFUV49TQYag7_Aod7y0';
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
