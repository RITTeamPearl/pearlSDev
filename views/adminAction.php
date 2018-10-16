<?php
ob_start();
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();


if (isset($_POST['sendNoti'])){
    //business layer stuff to ACTUALLY SEND THE NOTIFICATION
    $dataLayer->createNotification($_POST);
    header("Location: adminConsole.php#noti");

}
//var_dump($_POST);
if (isset($_POST['deleteNoti'])) {
    $dataLayer->deleteNotification($_GET['id']);
    header("Location: adminConsole.php#noti");
}

if (isset($_POST['modifyNoti'])) {
    $dataLayer->updateNotification($_GET['id'],$_POST);
    header("Location: adminConsole.php#noti");
}

if (isset($_POST['addEmp'])){
    //Generate a random 10 character password
    $genPass = substr(md5(microtime()),rand(0,26),10);
    $_POST['password'] = $genPass;
    //pass in 1 becaue it is a temp pass.
    //Also pass in the auth value individually to make things easier
    $dataLayer->createNewUser($_POST, 1, $_POST['authID'], $_POST['activeYN']);
    header("Location: adminConsole.php#emp");

}

if (isset($_POST['deleteEmp'])) {
    $dataLayer->deleteUser($_GET['id']);
    header("Location: adminConsole.php#emp");
    //var_dump($_POST);
}

if (isset($_POST['modifyEmp'])) {
    //During validation and sanitization the button value should be removed from post data
    //Im going to set it to null for now
    $_POST['modifyEmp'] = null;
    $dataLayer->updateUser($_POST,'userID',$_GET['id']);
    header("Location: adminConsole.php#emp");
    //var_dump($_POST);
}


ob_end_flush();
?>
