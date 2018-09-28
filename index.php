<?php
session_start();

// if(isset($_SESSION['phone'])){
//     //alredy logged in redirect to admin console
//     header("Location: views/adminConsole.php");
// }
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/login.css'>
    <link href='assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='loginPage'>
    <!-- Header -->
    <h1 id='title' class='centered'>Rochester Riverside Convention Center</h1>

    <!-- Login Form -->
    <form class='formContainer' action='views/login.php' method='POST'>
        <div class='inputWithIcon'>
            <input class='block' id='phone'  placeholder= 'Phone Number' name='phone' required="required" autofocus>
            <i class='fas fa-phone' aria-hidden='true'></i>
        </div>
        <div class='inputWithIcon'>
            <input class='block' id='password' type = 'password' placeholder= 'Password' name='password' required='required'/>
            <i class="fas fa-lock" aria-hidden='true'></i>
        </div>
        <a id='forgot' href='/views/forgotPwd.php'>Forgot password?</a>
        <input class='block submit centered' id='login' type = 'submit' value= 'Log in'/>
        <a id='signup' href='/views/createAcct.php'> Sign up</a>
    </form>

</body>
</html>
