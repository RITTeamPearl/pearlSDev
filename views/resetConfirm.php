<?php
// require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
// $dataLayer = new data_layer();
$bizLayer = new business_layer();

    //validate and sanitize input

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

<body id='forgotPwdPage'>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['email'])) {
                //check if the hashed email that was passed into the url is the same as the user input
                if(password_verify($_POST['email'],$_GET['email'])){
                    //actually reset the password.
                    $bizLayer->sendPasswordResetEmail("email");
                }
            }
        }
        ?>

        <form class='formContainer' method='POST'>
                <input style="width: 20%; margin-left: 40%"; class='block' id='email'  placeholder= 'Email Address' name='email'>
                <i class='fas fa-mail' aria-hidden='true'></i>
                <input class='block submit centered' id='confirmEmail' type = 'submit' value= 'Confirm Email'/>
        </form>
</body>
</html>
