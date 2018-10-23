<?php
session_start();
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
        echo $bizLayer->createLandingNewsTable($dataLayer->getAllNotifcations());

        ?>
        <div class='notifContainer' id='1'>
            <div class='overlay'>
                <img src='../assets/images/1.jpg'>
            </div>

            <h2 class='title'>Convention center to be closed on August 24, 2018</h2>

            <div class='subtitle block'>
                <div class='posted inline'>
                    <i class="far fa-clock"></i>
                    <span class='inline'>8h ago</span>
                </div>
                <a class='inline' href=''>read more</a>
            </div>

            <!-- Admin Feature only -->
            <button type='button' class='button hidden'><i class="far fa-edit"></i></button>

            <div class='buttonOptions hidden'>
                <ul class='spaced'>
                    <li>Modify<i class='fas fa-pencil-alt'></i></li>
                    <li>Delete<i class="fas fa-trash-alt"></i></li>
                </ul>
            </div>

        </div>

        <div class='notifContainer' id='2'>
            <div class='overlay'>
                <img src='../assets/images/2.jpg'>
            </div>

            <h2 class='title'>Heavy Rain to delay conference center opening</h2>

            <div class='subtitle block'>
                <div class='posted inline'>
                    <i class="far fa-clock"></i>
                    <span class='inline'>1w ago</span>
                </div>
                <a class='inline' href=''>read more</a>
            </div>

            <!-- Admin Feature only -->
            <button type='button' class='button hidden'><i class="far fa-edit"></i></button>

            <div class='buttonOptions hidden'>
                <ul class='spaced'>
                    <li>Modify<i class='fas fa-pencil-alt'></i></li>
                    <li>Delete<i class="fas fa-trash-alt"></i></li>
                </ul>
            </div>

        </div>

        <!-- Footer -->
        <div class='footer block'>
            <ul class='iconContainer'>
                <li class='inline'><i class="fas fa-newspaper"></i></li>
                <li class='inline'><i class="fas fa-video"></i></li>
                <li class='inline'><i class="fas fa-user"></i></li>
                <li class='inline'><i class="fas fa-bell"></i></li>
                <li class='inline'><a href="adminConsole.php"><i class="fas fa-toolbox"></i></a></li>
            </ul>

        </div>
    </div>
</body>
</html>
