<?php
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();

var_dump($_POST);
if ($_POST['delete']) {
    $dataLayer->deleteNotification($_GET['id']);
    header("Location: adminConsole.php");
}

if ($_POST['modify']) {
    $businessLayer->validateAndSanitize();
    header("Location: adminConsole.php");
}



 ?>
