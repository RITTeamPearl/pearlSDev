<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home View</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>

<body>
    <?php
        session_start(); //starts the session
        
        if($_SESSION['user']){ // checks if the user is logged in  
            Print
            '<h2>Welcome <?php Print $user ?> to Your RRCC Home</h2>
    
            <form action="logout.php" method="POST">
                <input type = "submit" value= "Log out"/>
            </form>
        
            <p>Be sure to read through all the post!</p>';
        }
        else{
        header("location: index.php"); // redirects if user is not logged in
        }
        $user = $_SESSION['user']; //assigns user value
    ?>    

</body>
</html>