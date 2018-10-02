<?php
    session_start();
    require_once '../database/data_layer.php';
    require_once '../business/business_layer.php';
    $businessLayer = new business_layer();
    $dataLayer = new data_layer();

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
