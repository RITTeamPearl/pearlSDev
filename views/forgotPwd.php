<?php
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();


if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //validate and sanitize input
    //check if email exists
    $inputEmail = $_POST["email"];
    if($dataLayer->checkEmailExists($inputEmail)){
        //Yes
            //redirect to resetConfirm.php ignore the rest of code, it'll be done on resetConfirm.php
            $_SERVER["ForgotPwdEmail"] = $inputEmail;
            header("Location: resetConfirm.php");


            //Set DB Flag to temp pass
            //Send email with temp pass (also create temp password)
            //$bizLayer->passwordReset($inputEmail);
            //$bizLayer->sendPasswordResetEmail("email");
            //route to send page
            //echo "<script type='text/javascript'>
            //var landing = document.getElementById('section-landing');
            //var success = document.getElementById('seciton-success');
            //landing.style.display == 'none';
            //success.style.display == 'block';
            //</script>";

    }   else { //NO
            //Put error message on screen saying email could not be found
            $emailError = "<h2 color='red'>Error: Email Address Not Found.</h2>";
            echo "<script type='text/javascript'>alert('$emailError');</script>";
    }
}
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

<body id='forgotPwdPage' class='backgroundImage'>
    <div class='container'>
        <!-- Landing Section -->
        <section id='section-landing'>
            <!-- Header -->
            <div class='header'>
                <i class="fas fa-lock" aria-hidden='true'></i>
                <h1 id='title' class='centered'>Forgot Password?</h1>
                <h2 class='subtitle centered'>We just need your registered email address to<br/>send you a temporary password</h2>
            </div>

            <!-- Send Password Form -->
            <form class='formContainer' method='POST'>
                <div class='inputWithIcon'>
                    <input class='block' id='email' type = 'email' placeholder= 'E-mail Address' name='email' required="required" autofocus>
                    <i class='fas fa-user' aria-hidden='true'></i>
                </div>
                <input class='block submit centered' id='login' type = 'submit' value= 'Send password'/>
                <a id='login' href='../index.php'>Log in</a>
            </form>
        </section>

        <!-- Password Sent Successfully Section -->
        <section id='section-success'>
            <!-- Header -->
            <div class='header'>
                <i class="fas fa-check" aria-hidden='true'></i>
                <h2 id='title' class='centered'>Sent Temporary<br/>Password</h2>
                <p class='subtitle centered'>You have successfully reset your password.<br/>Please use the new password<br/>sent to your email</p>
                <div id='formContainer'>
                    <a class='submit success' href='../index.php'>Log In</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
