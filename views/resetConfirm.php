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

<body id='forgotPwdPage' class='backgroundImage'>

        <?php
        $email = $_GET['email'];

        if($_SERVER["REQUEST_METHOD"] == "POST"){
             //actually reset the password.
             $bizLayer->sendPasswordResetEmail($email);
        }
        ?>
        <div class='container'>
            <section id='reset-confirm'>
                 <form class='formContainer' method='POST'>
                    <h3>Click below to confirm that you want your password reset.</h3>
                    <i class='fas fa-mail' aria-hidden='true'></i>
                    <input class='block submit centered' id='confirmEmail' type = 'submit' value= 'Confirm Email'/>
                </form>
            </section>
        </div>
</body>
</html>
