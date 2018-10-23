<?php
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: ../index.php");
}
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/profile.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='profilePage'>

    <div class='container'>
        <!-- Landing Section -->
        <section id='create-form'>
            <!-- Header -->
            <div class='header'>
                <h1 class='title centered'>Hello, <?php echo $_SESSION['name'] ?></h1>
                <h2 class='subtitle centered'>You may view/update any account<br/>information here</h2>
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
            <form class='formContainer' action='profile.php' method='POST'>

                <!-- Create Screen 1. Outer div is for JS -->
                <div class="formStep" id="formStep1">
                    <div class='inputWithIcon'>
                        <input class='block' id='phoneNumber' type = 'text' placeholder= 'Phone Number' name='phoneNumber' required="required">
                        <i class='fas fa-phone' aria-hidden='true'></i>
                    </div>

                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='password' type = 'password' placeholder= 'Password' name='password' required="required">
                        <i class='fas fa-key pwIcon' aria-hidden='true'></i>
                    </div>

                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='passwordConfirm' type = 'password' placeholder= 'Confirm Password' name='passwordConfirm' required="required">
                        <i class='fas fa-lock pwIcon' aria-hidden='true'></i>
                    </div>
                    <input class='block submit centered' id='next' type = 'button' value= 'Continue' onclick="nextStep(1)"/>
                </div>

                <div class="formStep" id="formStep3" style="display: none;">
                    <div class='inputWithIcon'>
                        <input class='block' id='email' type = 'text' placeholder= 'E-mail Address' name='email' required="required" autofocus>
                        <i class='fas fa-user' aria-hidden='true'></i>
                    </div>

                    <input class='block submit centered' id='submit' type = 'submit' value= 'Send Request'/>
                </div>
            </form>

            <form class="" action="profile.php" method="post">
                <div class='logoutContainer'>
                    <button type= 'submit' class='block submit centered' name= "logout" id='logout'>Logout</button>
                </div>
            </form>

        </section>
    </div>

    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
            <li class='inline'><i class="fas fa-video"></i></li>
            <li class='inline active'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <li class='inline'><i class="fas fa-bell"></i></li>
            <?php
            if ($_SESSION['authID'] == 4) {
                echo '<li class="inline"><a href="adminConsole.php"><i class="fas fa-toolbox"></i></a></li>';
            }
             ?>
        </ul>
    </div>

</body>
</html>
