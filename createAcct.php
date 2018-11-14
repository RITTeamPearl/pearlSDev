<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST){
    require_once '../database/data_layer.php';
    require_once '../business/business_layer.php';
    $businessLayer = new business_layer();
    $dataLayer = new data_layer();

    if (count($_POST) == 7) {
        //business layer validation / sanitization of $_POST
        $businessLayer->validateAndSanitize();
        $dataLayer->createNewUser($_POST);
        header('location: ../index.php');
    }
}
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='../style/css/createAcct.css'>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type='text/javascript' src='../js/scripts.js'></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body onload="addMask()" id='createAcctPage' class='backgroundImage'>
    <div class='container'>
        <!-- Landing Section -->
        <section id='create-form'>
            <!-- Header -->
            <div class='header'>
                <h1 class='title centered'>Create Account</h1>
                <h2 class='subtitle centered'>We just need a little information for the<br/>HR department to activate your account.</h2>
            </div>

            <!-- Screen Identifier Visual Graphic -->
            <div id='screenIdentifier' class='block centered'>
                <div id='screenContainer'>
                    <div class='dotCont' id='dotCont1'>
                        <i class='fas fa-circle inline' id='dot1'></i>
                        <i class='far fa-dot-circle inline' id='circle1'></i>
                        <hr class='whiteLine inline'>
                    </div>

                    <div class='dotCont' id='dotCont2'>
                        <i class='fas fa-circle inline' id='dot2'></i>
                        <i class='far fa-dot-circle inline' id='circle2'></i>
                        <hr class='whiteLine inline'>
                    </div>

                    <div class='dotCont' id='dotCont3'>
                        <i class='fas fa-circle inline' id='dot3'></i>
                        <i class='far fa-dot-circle inline' id='circle3'></i>
                    </div>
                </div>
            </div>

            <!-- Send Password Form -->
            <form class='formContainer' action='createAcct.php' method='POST'>

                <!-- Create Screen 1. Outer div is for JS -->
                <div class="formStep" id="formStep1">
                    <div class='inputWithIcon'>
                        <input class='block' id='phoneNumber' type = 'text' placeholder= 'Phone Number' name='phoneNumber' required="required">
                        <i class='fas fa-phone' aria-hidden='true'></i>
                        <span class='error' id='phoneSpan'></span>
                    </div>

                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='password' type = 'password' placeholder= 'Password' name='password' required="required">
                        <i class='fas fa-key pwIcon' aria-hidden='true'></i>
                        <span class='error' id='passwordSpan'></span>
                    </div>

                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='passwordConfirm' type = 'password' placeholder= 'Confirm Password' name='passwordConfirm' required="required">
                        <i class='fas fa-lock pwIcon' aria-hidden='true'></i>
                        <span class='error' id='passwordConfirmSpan'></span>
                    </div>
                    <input class='block submit centered' id='next' type = 'button' value= 'Continue' onclick="nextStepTwo(1)"/>
                    <a id='login' href='../index.php'>Log In</a>
                </div>

                <!-- Create Screen 2 -->
                <div class="formStep" id="formStep2" style="display: none;">
                    <div class='inputWithIcon'>
                        <input class='block' id='fName' type = 'text' placeholder= 'First Name' name='fName' required="required" autofocus>
                        <i class='fas fa-address-card' aria-hidden='true'></i>
                        <span class='error' id='firstNameSpan'></span>
                    </div>
                    <div class='inputWithIcon'>
                        <input class='block' id='lName' type = 'text' placeholder= 'Last Name' name='lName' required="required" autofocus>
                        <i class='fas fa-address-card' aria-hidden='true'></i>
                        <span class='error' id='lastNameSpan'></span>
                    </div>
                    <input class='block submit centered' id='next' type = 'button' value= 'Continue' onclick="nextStepTwo(2)"/>
                    <a id='login' href='../index.php'>Log In</a>
                </div>

                <div class="formStep" id="formStep3" style="display: none;">
                    <div class='inputWithIcon'>
                        <input class='block' id='email' type = 'text' placeholder= 'E-mail Address' name='email' required="required" autofocus>
                        <i class='fas fa-user' aria-hidden='true'></i>
                        <span class='error' id='emailSpan'></span>
                    </div>

                    <div class='inputWithIcon'>
                        <select class='block inputWithIcon' id='dept' name='deptID' required="required">
                            <option value="" disabled selected>Department</option>
                            <option value="1">HR</option>
                            <option value="2">Admin</option>
                            <option value="3">Sales</option>
                            <option value="4">Production</option>
                            <option value="5">Operations</option>
                            <option value="6">Food and Beverage</option>
                            <option value="7">Garage</option>
                        </select>
                            <span class='error' id='deptSpan'></span>
                        <i class='fas fa-building' aria-hidden='true'></i>
                    </div>
                    <input class='block submit centered' id='submit' type = 'submit' value= 'Send Request'/>
                    <a id='login' href='../index.php'>Log In</a>
                </div>

            </form>
        </section>
    </div>
</body>
</html>
