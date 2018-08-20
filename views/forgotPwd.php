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
    <div class='container'>
    <!-- Header -->
    <div class='header'>
        <i class="fas fa-lock" aria-hidden='true'></i>
        <h2 id='title' class='centered'>Forgot Password?</h2>
        <p class='subtitle centered'>We just need your registered email address to<br/>send you a temporary password</p>
    </div>
    
    <!-- Send Password Form -->
    <form id='formContainer' action='blank.php' method='POST'>
        <div class='inputWithIcon'>
            <input class='block' id='email' type = 'email' placeholder= 'E-mail Address' name='email' required="required" autofocus>
            <i class='fas fa-user' aria-hidden='true'></i>
        </div>
        
        <input class='block formSubmit centered' id='login' type = 'submit' value= 'Send Password'/>
        <a id='login' href='../index.php'>Log In</a>
    </form>
    </div>
</body>
</html>