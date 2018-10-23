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
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/notification.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='notifcationPage'>
    
    <!-- Image -->
    <div class='imageContainer'>
        <div class='overlay'>
            <img src='../assets/images/6.jpg'> <!-- Needs to be same image as on landing page -->
        </div>
    </div>
    
    <!-- Content -->
    <div class='container'>
        
        <h2 class='title'>Convention center to be closed on August 24, 2018</h2>
        
        <div class='subtitle block'>
            <i class="fas fa-download inline"></i>
            <span class='inline'>document.pdf</span>
            <i class="far fa-clock inline"></i>
            <span class='inline'>1w ago</span>
        </div>
        
        <span class='copy block'>Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Sed auctor ligula quis ante pretium laoreet.
        Nunc semper erat dignissim placerat feugiat.
        Aenean commodo risus consequat ligula aliquet
        portitor.<br/><br/>

        You can signup at this link:
        https://www.spotify.com/us/<br/><br/>

        Mattis massa felis accumsan. Ipsum dolor sit 
        amet, consectetur adipiscing elit. vitae commodo
        mattis, massa felis. Mattis massa felis accumsan. 
        Ipsum dolor sit amet, consectetur adipiscing elit.
        Vitae commodo mattis, massa felis.</span>
    </div>
    
    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><i class="fas fa-newspaper"></i></li>
            <li class='inline'><i class="fas fa-video"></i></li>
            <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <li class='inline'><i class="fas fa-bell"></i></li>
            <li class='inline'><a href="adminConsole.php"><i class="fas fa-toolbox"></i></a></li>
        </ul>
    </div>

</body>
</html>
