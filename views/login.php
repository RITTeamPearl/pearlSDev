<?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "") or die (mysqli_error()); //Connect to server
    mysqli_select_db($connection, "sdevTest") or die ("Cannot connect to database"); //Connect to database
    
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $bool = true;
   
    $query = mysqli_query($connection,"Select * from USER WHERE email='$email'"); // Query the users table
    $exists = mysqli_num_rows($query); //Checks if email exists
    $table_user = "";
    $table_password = "";
   
    if($exists > 0) //IF there are no returning rows or no existing email
    {
       while($row = mysqli_fetch_assoc($query)) // display all rows from query
       {
            $table_user = $row['email']; // the first email row is passed on to $table_user, and so on until the query is finished
            $table_password = $row['password']; // the first password row is passed on to $table_password, and so on until the query is finished
       }
       
       if(($email == $table_user))// checks if there are any matching fields
       {
            if($password == $table_password)
            {
                $_SESSION['user'] = $email; //set the email in a session. This serves as a global variable
                header("location: home.php"); // redirects the user to the authenticated home page
            }
            else
            {
                Print '<script>alert("Incorrect Password!");</script>'; // Prompts the user
                Print '<script>window.location.assign("index.php");</script>'; // redirects to index.php
            }
       }
       else
       {
            Print '<script>alert("Incorrect email!");</script>'; // Prompts the user
            Print '<script>window.location.assign("index.php");</script>'; // redirects to index.php
       }
    }
    else
    {
        Print '<script>alert("This user does not exists.");</script>'; // Prompts the user
        Print '<script>window.location.assign("index.php");</script>'; // redirects to index.php
    }
?>