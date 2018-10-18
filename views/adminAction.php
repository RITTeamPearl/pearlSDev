<?php
//This file will look at what button was clicked (form submission) in the admin console and act accordingly
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();

//Sending a new notification was clicked
if (isset($_POST['sendNoti'])){
    //business layer stuff to deal with the attachment ($_FILES)
    if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0 ){
        if ($businessLayer->uploadFile($_FILES['attachment'])){
            //file successfully uploaded
            //set the post var for the attachment. This will be the path to the uploaded file
            //Putting it in POST will upload it to the DB
            $_POST['attachment'] = "assets/uploads/".$_FILES['attachment']['name'];
        }
        else {
            echo "Error uploading file";
        }

    }
    //business layer stuff to ACTUALLY SEND THE NOTIFICATION
    $dataLayer->createNotification($_POST);
    header("Location: adminConsole.php#noti");

}
//Delete notification button was clicked
if (isset($_POST['deleteNoti'])) {
    $dataLayer->deleteNotification($_GET['id']);
    header("Location: adminConsole.php#noti");
}

//Delete notification button was clicked
if (isset($_POST['modifyNoti'])) {
    $dataLayer->updateNotification($_GET['id'],$_POST);
    header("Location: adminConsole.php#noti");
}

//add new employee button was clicked
if (isset($_POST['addEmp'])){
    //Generate a random 10 character password
    $genPass = substr(md5(microtime()),rand(0,26),10);
    $_POST['password'] = $genPass;
    //pass in 1 becaue it is a temp pass.
    //Also pass in the auth value individually to make things easier
    $dataLayer->createNewUser($_POST, 1, $_POST['authID'], $_POST['activeYN']);
    header("Location: adminConsole.php#emp");

}

//delete employee button was clicked
if (isset($_POST['deleteEmp'])) {
    $dataLayer->deleteUser($_GET['id']);
    header("Location: adminConsole.php#emp");
    //var_dump($_POST);
}

//Modify employee was clicked
if (isset($_POST['modifyEmp'])) {
    //During validation and sanitization the button value should be removed from post data
    //Im going to set it to null for now
    $_POST['modifyEmp'] = null;
    $dataLayer->updateUser($_POST,'userID',$_GET['id']);
    header("Location: adminConsole.php#emp");
    //var_dump($_POST);
}

if(isset($_POST['confirmPendEmp'])){
    //send text / email saying you have been confirmed
    //datalayer set authID
    //$getAuthID = $dataLayer->getData('user', array('deptID'),'userID',$_GET['id']);
    //$dataLayer->updateUser(array('authID' => intval($_POST['pendingAuthID']) ),'userID',17);
    //$dataLayer->updateUser(array('authID' => intval($_POST['pendingAuthID']) ),'userID',$_GET['id']);
    //echo "confirm userID {$_GET['id']} \n";
    echo "Confirm User with authID {$_POST['pendingAuthID']}";
    echo "</br>In progress, go back to admin console via URL";
}

if(isset($_POST['denyPendEmp'])){
    //delete from  user where id = $_GET['id']
    //$dataLayer->deleteData('user','userID',$_GET['id']);
    echo "</br>In progress, go back to admin console via URL";

}


ob_end_flush();
?>
