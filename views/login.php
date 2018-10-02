<?php
    session_start();
    require_once '../database/data_layer.php';
    require_once '../business/business_layer.php';
    $dataLayer = new data_layer();
    $businessLayer = new business_layer();
    //$connection = mysqli_connect("localhost", "root", "student", "rrcc_pearl_db") or die (mysqli_error()); //Connect to server
    //$email = mysqli_real_escape_string($connection, $_POST['email']);
    //$password = mysqli_real_escape_string($connection, $_POST['password']);

    $validatedPOST = $businessLayer->validateAndSanitize($_POST);
    //$loginSuccess = $dataLayer->checkLogin($validatedPOST['phone'],$validatedPOST['password']);
    $loginSuccess = $dataLayer->checkLogin($_POST['phone'],$_POST['password']);

    if ($loginSuccess) {
        $_SESSION['phone'] = $_POST['phone'];
        header("Location: adminConsole.php");
    }
    else {
        header("Location: ../index.php");
    }

?>
