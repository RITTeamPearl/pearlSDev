<?php
// require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
// $dataLayer = new data_layer();
$bizLayer = new business_layer();
$bizLayer->validateAndSanitize();
//$bizLayer->passwordReset("email");

    //validate and sanitize input
    //check if email exists
        //Yes
            //Generate temp Password
            //Set DB Flag to temp pass
            //Send email with temp pass
            //route to send page
        //NO
            //Put error message on screen saying email could not be found


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
        if (isset($_POST['email'])) {
            //check if the hashed email that was passed into the url is the same as the user input
            if(password_verify($_POST['email'],$_GET['email'])){
                //actually reset the password.
            }
        }

        ?>

        <form class='' action="demoHomepage.php?{$_GET['email']}" method='POST'>
                <input style="width: 20%; margin-left: 40%"; class='block' id='email'  placeholder= 'Email Address' name='email'>
                <i class='fas fa-mail' aria-hidden='true'></i>
                <input class='block submit centered' id='confirmEmail' type = 'submit' value= 'Confirm Email'/>
        </form>
</body>
</html>
