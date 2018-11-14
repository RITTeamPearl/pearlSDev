<?php

session_start();

require_once '../database/data_layer.php';
$dataLayer = new data_layer();

            //checks email Testing
        if (isset($_POST['submitCreateAcct'])) {
            if (empty($_POST['email'])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST['email']);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid format for email";
                }
                $dataLayer->createNewUser($_POST, $tempPassYN = 0, $authID = 1, $activeYN = 1);
                header('Location: ../index.php');
            }
        }
?>