<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $connection = mysqli_connect("localhost", "root","") or die(mysqli_error()); //Connect to server
    mysqli_select_db($connection, "sdevTest") or die("Cannot connect to database"); //Connect to database
    
    $firstName = '';
    $lastName = '';
    $carrier = '';
    $department = 0;

    if(isset($_POST['fname'])){
        $firstName = mysqli_real_escape_string($connection, $_POST['fname']);
    }

    if(isset($_POST['lname'])){
        $lastName = mysqli_real_escape_string($connection, $_POST['lname']);
    }

    if(isset($_POST['carriers'])){
        $carrier = mysqli_real_escape_string($connection, $_POST['carriers']);
    }

    if(isset($_POST['departments'])){
        $department = mysqli_real_escape_string($connection, $_POST['departments']);
    }

    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    
    $bool = true;
    
    $query = mysqli_query($connection, "Select * from USER"); //Query the user table
    
    while($row = mysqli_fetch_array($query)) //display all rows from query
        {
            $table_user = $row['email']; // the first email row is passed on to $table_user, and so on until the query is finished
            if($email == $table_user) // checks if there are any matching fields
            {
                $bool = false; // sets bool to false
                Print '<script>alert("email already exists.");</script>'; //Prompts the user
                Print '<script>window.location.assign("createAcct.html");</script>'; // redirects to createAcct.html
            }
        }
        if($bool) // checks if bool is true
            {
                //Inserts the value to table user
                mysqli_query($connection, "INSERT INTO USER (email, password, phone, fName, lName, carrier, deptID) VALUES ('$email','$password', '$phone', '$firstName', '$lastName', '$carrier', '$department')"); 

                Print '<script>alert("Successfully Registered!");</script>'; // Prompts the user
                Print '<script>window.location.assign("createAcct.html");</script>'; // redirects to createAcct.html
            }
}
?>