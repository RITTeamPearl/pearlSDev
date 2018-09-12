<?php
if (isset($_POST['phone'])) {
    echo "send text to phone";
}

if (isset($_POST['email'])) {
    echo "send email";
}

 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Demo</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/login.css'>
    <link href='assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='loginPage'>
    <!-- Header -->
    <h1 id='title' class='centered'>New Tech Demo</h1>

    <!-- Login Form -->
    <form class='' action='demoHomepage.php' method='POST'>
            <input style="width: 20%; margin-left: 40%"; class='block' id='phone'  placeholder= 'Phone Number' name='phone'>
            <i class='fas fa-phone' aria-hidden='true'></i>
        <input class='block submit centered' id='sendText' type = 'submit' value= 'Send Test Text'/>
    </form>

    <form class='' action='demoHomepage.php' method='POST'>
            <input style="width: 20%; margin-left: 40%" class='block' id='email'  placeholder= 'Email Address' name='email'>
            <i class='fas fa-person' aria-hidden='true'></i>
        <input class='block submit centered' id='sendEmail' type = 'submit' value= 'Send Test email'/>
    </form>

</body>
</html>
