<?php
    session_start();
    require_once '../data_layer.php';
    $dataLayer = new data_layer();
    //$connection = mysqli_connect("localhost", "root", "student", "rrcc_pearl_db") or die (mysqli_error()); //Connect to server
    //$email = mysqli_real_escape_string($connection, $_POST['email']);
    //$password = mysqli_real_escape_string($connection, $_POST['password']);
    $loginSuccess = $dataLayer->checkLogin($_POST['email'], $_POST['password']);

    if ($loginSuccess) {
        echo "correct Phone and Pass";
    }
    else {
        echo "WRONG";
    }


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
        //Print '<script>window.location.assign("index.php");</script>'; // redirects to index.php
    }
?>
