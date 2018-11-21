<?php
    session_start();
    require_once '../database/data_layer.php';
    $dataLayer = new data_layer();
    if (isset($_POST)){
        print_r($_POST);
        $_POST['phone'] = str_replace("-","",$_POST['phone']); 
        $userID = $_POST['userID'];
        unset($_POST['userID']);
        $dataLayer->updateUser($_POST,'userID',$userID);
    }else {
        echo "Here???";
    }
?>
