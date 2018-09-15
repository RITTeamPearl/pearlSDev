<?php
    session_start();
    require_once '../database/data_layer.php';
    $dataLayer = new data_layer();
    //$connection = mysqli_connect("localhost", "root", "student", "rrcc_pearl_db") or die (mysqli_error()); //Connect to server
    //$email = mysqli_real_escape_string($connection, $_POST['email']);
    //$password = mysqli_real_escape_string($connection, $_POST['password']);
    $loginSuccess = $dataLayer->checkLogin($_POST['phone'],$_POST['password']);

    if ($loginSuccess) {
        header("Location: demoHomepage.php");
    }
    else {
        header("Location: ../index.php");
    }

?>
