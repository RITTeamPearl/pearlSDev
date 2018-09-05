<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST){
    require_once '../database/data_layer.php';
    $dataLayer = new data_layer();

    if (count($_POST) == 6) {
        $dataLayer->createNewUser($_POST);
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

<body id='createaAcctPage'>
    <div class='container'>
        <!-- Landing Section -->
        <section id='create-form'>
            <!-- Header -->
            <div class='header'>
                <i class="fa fa-user-circle fa-5x" aria-hidden='true'></i>
                <h2 id='title' class='centered'>Create Account</h2>
                <p class='subtitle centered'>Please fill in the form to create an account</p>
            </div>

            <!-- Send Password Form -->
            <form id='formContainer' action='createAcct.php' method='POST'>
                <div class='inputWithIcon'>
                    <input class='block' id='phone' type = 'text' placeholder= 'Phone Number' name='phone' required="required" autofocus>
                    <i class='fas fa-phone' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <input class='block' id='email' type = 'email' placeholder= 'E-mail Address' name='email' required="required">
                    <i class='fas fa-envelope-square' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <input class='block' id='fName' type = 'text' placeholder= 'First Name' name='fName' required="required">
                    <i class='fas fa-signature' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <input class='block' id='lName' type = 'text' placeholder= 'Last Name' name='lName' required="required">
                    <i class='fas fa-signature' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <input class='block' id='password' type = 'password' placeholder= 'Password' name='password' required="required">
                    <i class='fas fa-key' aria-hidden='true'></i>
                </div>

                <div class='inputWithIcon'>
                    <select class='block' id='dept' name='dept' required="required">
                        <option value="1">HR</option>
                        <option value="2">Admin</option>
                        <option value="3">Sales</option>
                        <option value="4">Production</option>
                        <option value="5">Operations</option>
                        <option value="6">Food and Beverage</option>
                        <option value="7">Garage</option>
                    </select>
                </div>

                <input class='block submit centered' id='submit' type = 'submit' value= 'Create'/>
                <a id='login' href='../index.php'>Log In</a>
            </form>
        </section>
    </div>
</body>
</html>
