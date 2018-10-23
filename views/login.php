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
        $userData = $dataLayer->getData('user',array('authID','phone','fName','lName'), 'phone', $_POST['phone']);
        $_SESSION['authID'] = $userData[0]['authID'];
        $_SESSION['phone'] = $userData[0]['phone'];
        $_SESSION['name'] = $userData[0]['fName']. " ". $userData[0]['lName'];
        header("Location: adminConsole.php");
    }
    else {
        header("Location: ../index.php");
    }

?>
