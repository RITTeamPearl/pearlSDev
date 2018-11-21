<?php
    session_start();
    require_once '../database/data_layer.php';
    $dataLayer = new data_layer();
    if (isset($_POST)){
        $userID = $_POST['userID'];
        if (isset($_POST['delete'])){
            if (isset($_POST['user'])) {
                // code...
                //$dataLayer->deleteData('user','userID',$_POST['userID']);
                //
            }
            if (isset($_POST['noti'])) {
                //$dataLayer->deleteData('notification','notificationID',$_POST['notificationID']);
            }
        }
        else {
            //not a delete. start by removing hyphens from phone
            $_POST['phone'] = str_replace("-","",$_POST['phone']);
            unset($_POST['userID']);
            $dataLayer->updateUser($_POST,'userID',$userID);
        }
    }else {
        echo "Here???";
    }
?>
