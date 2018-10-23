<?php
session_start();
if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/news.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='newsPage'>

    <!-- News -->
    <div class='bodyContainer'>
        <?php
        echo $bizLayer->createLandingNewsTable(array_reverse($dataLayer->getAllNotifcations()));
        ?>

        <!-- Footer -->
        <div class='footer block'>
            <ul class='iconContainer'>
                <li class='inline active'><i class="fas fa-newspaper"></i></li>
                <li class='inline'><i class="fas fa-video"></i></li>
                <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
                <li class='inline'><i class="fas fa-bell"></i></li>
                <?php
                if ($_SESSION['authID'] == 4) {
                    echo '<li class="inline"><a href="adminConsole.php"><i class="fas fa-toolbox"></i></a></li>';
                }
                 ?>
            </ul>
        </div>

    </div>
</body>
</html>
