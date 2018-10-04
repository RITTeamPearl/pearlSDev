<?php
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();

$dataLayer->deleteNotification($_GET['id']);
header("Location: adminConsole.php");


 ?>
