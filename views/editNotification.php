<?php
ob_start();
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();

//var_dump($_POST);
if ($_POST['delete']) {
    $dataLayer->deleteNotification($_GET['id']);
    header("Location: adminConsole.php");
}

if ($_POST['modify'] === "") {
    $dataLayer->updateNotification($_GET['id'],$_POST);
    header("Location: adminConsole.php");
}
ob_end_flush();
?>