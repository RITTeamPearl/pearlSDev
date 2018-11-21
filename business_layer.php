<?php
session_start();
require '../vendor/autoload.php';
require '../database/data_layer.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;

function test_input($info)
{
    $info = trim($info);
    $info = stripslashes($info);
    $info = htmlspecialchars($info);
    return $info;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    $busLayer = new business_layer();

    switch ($_POST['action']) {
        case 'validateForm':
            echo json_encode($busLayer->validateAndSanitize());
            break;
        case 'createUser':
            echo ('user created');
            break;
    }
}

class business_layer
{

    function sendEmail($address, $subject, $body, $attachmentPath = 0)
    {

        //need to set time for smtp purposes
        date_default_timezone_set('America/New_York');

        //new instance, set to google smtp settings standards
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        //email address to send from
        $mail->Username = $_SERVER['EMAIL_ADDR'];
        //password
        $mail->Password = $_SERVER['EMAIL_PASSWORD'];

        try {
            //set attributes to passed in
            $mail->setFrom('team-pearl-rit@gmail.com', 'RIT Team Pearl');
            $mail->addAddress($address);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->IsHTML(true);
            //add the attachment
            if ($attachmentPath) {
                //make db path relative using ../
                $mail->addAttachment("../" . $attachmentPath);
            }
            //Send it
            $mail->send();
            return true;
        } catch (\Exception $e)//Slash is for generic exception class
        {
            echo $e->getMessage();
            return false;
        }
    }

    function sendText($text)
    {
        // Twilio credentials
        $account_sid = $_SERVER['TWILIO_SID'];
        $auth_token = $_SERVER['TWILIO_TOKEN'];
        $twilio_number = $_SERVER['TWILIO_PHONE'];;

        //Create twilio client using our credentials
        $client = new Client($account_sid, $auth_token);
        //send message
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            '+15856455810',//should be a passed in $phoneNumber but free version doesnt allow it.
            array(
                'from' => $twilio_number,
                'body' => $text
            )
        );
    }

    function uploadFile($fileArray, $callBack)
    {
        //Passed in name of the file with extension
        $name = $fileArray['name'];
        if ($callBack == 'csv') $name = "currEmpCSV.csv";
        //Temp file stored from upload. Full path
        $tempFile = $fileArray['tmp_name'];
        //targetFile
        $targetFile = "../assets/uploads/" . $name;
        //File type
        $type = strtolower(end(explode('.', $name)));

        $notAllowedTypes = array("exe", "js", "sql", "php");

        //only go forward if the file type is allowed
            //only go forward if it doesnt exst
        if (!in_array($type, $notAllowedTypes) || ($callBack == 'csv' && $type == 'csv')) {
            //if it already exists overwrite it
            move_uploaded_file($tempFile, $targetFile);
            return true;
        } else {
            //Not correct file type
            return false;
        }
    }

    function sendPasswordResetEmail($email)
    {
        //$hashedEmail = password_hash($email,PASSWORD_DEFAULT);

        //$subject = "RRCC Account Password Reset";
        //$body = "<h1>Please click the link below to reset your password</h1>";
        //$body .= "<h2 href='localhost/views/resetConfirm.php?email=$hashedEmail' >Reset</h2>";

        //generate url to send in email. ../resetPassword.php?emailToReset
        //send email
        //confirm email that is being reset
        //if input matches $_GET as well as some unique identifier then reset the password


        $dataLayer = new data_layer();
        // generate a random 10 character string.
        $genPass = substr(md5(microtime()), rand(0, 26), 10);
        // database call to set new password and update temp password flag
        $dataLayer->setUserTempPass($email, $genPass);
        // echo "your new password is $genPass";

        //address , subject line, body
        if ($bizLayer->sendEmail($email, 'New Temporary Password', "Your New Password is $genPass. Please log in and replace it as soon as possible.")) {
        };
    }

    function validateAndSanitize()
    {

        $validatedPOST = [];
        $formErrors = [];
        //$validatedPOST['phone'] = $postData['phone'];// this should be validated and sanitized
        //return $validatedPOST;

        //variables for error message
        $phoneErr = $pwdErr = $pwdConfirmErr = $fnameErr = $lnameErr = $emailErr = "";
        //variables for good user input (val and san checks)
        $phone = $pwd = $pwdConfirm = $fname = $lname = $email = "";
        
        //check if form was submitted with POST 
        if ($_POST['formSection'] == 'screen1') {
            //echo $_POST['phoneNumber'];
            //checks for createAcct page
            //check phoneNumber, password, passwordConfirm, fName, lName, and email
            //check phone number

            //turns $_POST string into usable array called formArrary
            $formArray = array();
            $json = $_POST['formData'];
            $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($json, true)),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($jsonIterator as $key => $val) {
                if (is_array($val)) {
                    //echo "$key:\n";
                    $formArray[$val[0]] = $val[1];
                } else {
                }
            }

            if (empty($formArray['phoneNumber'])) {
                $phoneErr = "Phone Number is required";
                array_push($formErrors, [
                    'location' => '#phoneSpan',
                    'msg' => $phoneErr
                ]);
            } else if ($formArray['phoneNumber'] != "") {
                $input = $formArray['phoneNumber'];
                $phone = test_input($input);
                //^1?([1-9])(\d{9}) - phone regex
                //^\d{3}-\d{3}-\d{4}$ - another phone option
                $pattern = '/^\d{3}-\d{3}-\d{4}$/';
                if (!preg_match($pattern, $phone)) {
                    $phoneErr = "Invalid phone number";
                    array_push($formErrors, [
                        'location' => '#phoneSpan',
                        'msg' => $phoneErr
                    ]);
                }
                //built in php functions that i think could work as well, if needed
                $phone = filter_var($formArray['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
                //filter_var($postData['phone'], FILTER_VALIDATE_INT);
            }//end of phone number checks...this works

                //checks password
            if (empty($formArray['password']) && empty($formArray['passwordConfirm'])) {
                $pwdErr = "Password is required";
                $pwdConfirmErr = "Confirmation Password is required";
                array_push($formErrors, [
                    'location' => '#passwordSpan',
                    'msg' => $pwdErr
                ]);
                array_push($formErrors, [
                    'location' => '#passwordConfirmSpan',
                    'msg' => $pwdConfirmErr
                ]);
            } else {
                $pwd = test_input($formArray['password']);
                $pwdConfirm = test_input($formArray['passwordConfirm']);
                //check that password and passwordConfirm match
                if ($pwdConfirm == $pwd) {
                    //check for length of at least 8
                    if (!(strlen($pwd) >= 8)) {
                        $pwdErr = "Password must contain at least 8 characters";
                        array_push($formErrors, [
                            'location' => '#passwordSpan',
                            'msg' => $pwdErr
                        ]);
                    }
                    //check for one upper case
                    if (!preg_match("/[A-Z]/", $pwd)) {
                        $pwdErr = "Password must contain at least 1 uppercase letter";
                        array_push($formErrors, [
                            'location' => '#passwordSpan',
                            'msg' => $pwdErr
                        ]);
                    }
                    //check for one lower case
                    if (!preg_match("/[a-z]/", $pwd)) {
                        $pwdErr = "Password must contain at least one lowercase letter";
                        array_push($formErrors, [
                            'location' => '#passwordSpan',
                            'msg' => $pwdErr
                        ]);
                    }
                    //check for one number
                    if (!preg_match("/[1-9]/", $pwd)) {
                        $pwdErr = "Password must contain at least one digit";
                        array_push($formErrors, [
                            'location' => '#passwordSpan',
                            'msg' => $pwdErr
                        ]);
                    }
                } else {
                    $pwdConfirmErr = "Confirmation password must be the same as the above password";
                    array_push($formErrors, [
                        'location' => '#passwordConfirmSpan',
                        'msg' => $pwdConfirmErr
                    ]);
                }
            }//end of password and passwordConfirm check...this works
        }//end of checks for screen 1 on the create account page
        
        //turns $_POST string into usable array called formArrary
        if ($_POST['formSection'] == 'screen2') {

            $formArray = array();
            $json = $_POST['formData'];
            $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($json, true)),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($jsonIterator as $key => $val) {
                if (is_array($val)) {
                    //echo "$key:\n";
                    $formArray[$val[0]] = $val[1];
                } else {
                }
            }

            //checks fname
            if (empty($formArray['fName'])) {
                $fnameErr = "First name is required";
                array_push($formErrors, [
                    'location' => '#firstNameSpan',
                    'msg' => $fnameErr
                ]);
            } else {
                $fname = test_input($formArray['fName']);
                if (!preg_match("/^[A-Za-z]+$/", $fname)) {
                    $fnameErr = "First name can only contain letters with no spaces";
                    array_push($formErrors, [
                        'location' => '#firstNameSpan',
                        'msg' => $fnameErr
                    ]);
                }
                $fname = filter_var($formArray['fName'], FILTER_SANITIZE_STRING);
            }

            //checks lname
            if (empty($formArray['lName'])) {
                $lnameErr = "Last name is required";
                array_push($formErrors, [
                    'location' => '#lastNameSpan',
                    'msg' => $lnameErr
                ]);
            } else {
                $lname = test_input($formArray['lName']);
                if (!preg_match("/^[A-Za-z]+$/", $lname)) {
                    $lnameErr = "Last name can only contain letters with no spaces";
                    array_push($formErrors, [
                        'location' => '#lastNameSpan',
                        'msg' => $lnameErr
                    ]);
                }
                $lname = filter_var($formArray['lName'], FILTER_SANITIZE_STRING);
            }
        }

        if ($_POST['formSection'] == 'screen3') {
            //echo $_POST['phoneNumber'];
            //checks for createAcct page
            //check phoneNumber, password, passwordConfirm, fName, lName, and email
            //check phone number

            //turns $_POST string into usable array called formArrary
            $formArray = array();
            $json = $_POST['formData'];
            $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($json, true)),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($jsonIterator as $key => $val) {
                if (is_array($val)) {
                    //echo "$key:\n";
                    $formArray[$val[0]] = $val[1];
                } else {
                }
            }

            //checks email Testing
            $dLayer = new data_layer();
            if (isset($formArray['submitCreateAcct'])) {
                if (empty($formArray['email'])) {
                    $emailErr = "Email is required";
                    array_push($formErrors, [
                        'location' => '#emailSpan',
                        'msg' => $emailErr
                    ]);
                } else {
                    $email = test_input($formArray['email']);
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid format for email";
                        array_push($formErrors, [
                            'location' => '#emailSpan',
                            'msg' => $emailErr
                        ]);
                    }
                    $dLayer->createNewUser($formArray);
                    header('Location: ../index.php');
                }
            }
        } //end of screen3 check

        if ($_POST['formSection'] == 'screen4') { //profile screen check
                    //echo $_POST['phoneNumber'];
                    //check phoneNumber, password, passwordConfirm, and email only if their fields isn't empty
                    //check phone number

                    //turns $_POST string into usable array called formArrary
                    $formArray = array();
                    $json = $_POST['formData'];
                    $jsonIterator = new RecursiveIteratorIterator(
                        new RecursiveArrayIterator(json_decode($json, true)),
                        RecursiveIteratorIterator::SELF_FIRST
                    );
                    foreach ($jsonIterator as $key => $val) {
                        if (is_array($val)) {
                            //echo "$key:\n";
                            $formArray[$val[0]] = $val[1];
                        } else {
                        }
                    }

                    if ($formArray['phoneNumber'] != "") {
                        $input = $formArray['phoneNumber'];
                        $phone = test_input($input);
                        //^1?([1-9])(\d{9}) - phone regex
                        //^\d{3}-\d{3}-\d{4}$ - another phone option
                        $pattern = '/^\d{3}-\d{3}-\d{4}$/';
                        if (!preg_match($pattern, $phone)) {
                            $phoneErr = "Invalid phone number";
                            array_push($formErrors, [
                                'location' => '#phoneSpan',
                                'msg' => $phoneErr
                            ]);
                        }
                        //built in php functions that i think could work as well, if needed
                        $phone = filter_var($formArray['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
                        //filter_var($postData['phone'], FILTER_VALIDATE_INT);
                    }//end of phone number checks...this works


                    if ($formArray['email'] != "") {
                    $email = test_input($formArray['email']);
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emailErr = "Invalid format for email";
                            array_push($formErrors, [
                                'location' => '#emailSpan',
                                'msg' => $emailErr
                            ]);
                        }
                    }

                        //checks password
                    if (!(empty($formArray['password'])) && (!(empty($formArray['passwordConfirm'])))) {
                        $pwd = test_input($formArray['password']);
                        $pwdConfirm = test_input($formArray['passwordConfirm']);
                        //check that password and passwordConfirm match
                        if ($pwdConfirm == $pwd) {
                            //check for length of at least 8
                            if (!(strlen($pwd) >= 8)) {
                                $pwdErr = "Password must contain at least 8 characters";
                                array_push($formErrors, [
                                    'location' => '#passwordSpan',
                                    'msg' => $pwdErr
                                ]);
                            }
                            //check for one upper case
                            if (!preg_match("/[A-Z]/", $pwd)) {
                                $pwdErr = "Password must contain at least 1 uppercase letter";
                                array_push($formErrors, [
                                    'location' => '#passwordSpan',
                                    'msg' => $pwdErr
                                ]);
                            }
                            //check for one lower case
                            if (!preg_match("/[a-z]/", $pwd)) {
                                $pwdErr = "Password must contain at least one lowercase letter";
                                array_push($formErrors, [
                                    'location' => '#passwordSpan',
                                    'msg' => $pwdErr
                                ]);
                            }
                            //check for one number
                            if (!preg_match("/[1-9]/", $pwd)) {
                                $pwdErr = "Password must contain at least one digit";
                                array_push($formErrors, [
                                    'location' => '#passwordSpan',
                                    'msg' => $pwdErr
                                ]);
                            }
                        } else {
                            $pwdConfirmErr = "Confirmation password must be the same as the above password";
                            array_push($formErrors, [
                                'location' => '#passwordConfirmSpan',
                                'msg' => $pwdConfirmErr
                            ]);
                        }
                    }//end of password and passwordConfirm check
                }//end of checks for screen 4 on the profile page

            if ($_POST['formSection'] == 'screen5') { //checks the AdminConsole notifications
                        //check Title, Body, Survey Link.
                        //check phone number

                        //turns $_POST string into usable array called formArray
                        $formArray = array();
                        $json = $_POST['formData'];
                        $jsonIterator = new RecursiveIteratorIterator(
                            new RecursiveArrayIterator(json_decode($json, true)),
                            RecursiveIteratorIterator::SELF_FIRST
                        );
                        foreach ($jsonIterator as $key => $val) {
                            if (is_array($val)) {
                                //echo "$key:\n";
                                $formArray[$val[0]] = $val[1];
                            } else {
                            }
                        }

                        if (empty($formArray['title'])) {
                                        $titleErr = "title is required";
                                        array_push($formErrors, [
                                            'location' => '#titleSpan',
                                            'msg' => $titleErr
                                        ]);
                        } else {
                                        $body = test_input($formArray['title']);
                                        if (!preg_match("/^[a-zA-Z\s]*$/", $title)) {
                                            $titleErr = "Title can only contain letters and spaces";
                                            array_push($formErrors, [
                                                'location' => '#titleSpan',
                                                'msg' => $titleErr
                                            ]);
                                        }
                                $title = filter_var($formArray['title'], FILTER_SANITIZE_STRING);
                        }

                        if (empty($formArray['body'])) {
                                        $bodyErr = "body is required";
                                        array_push($formErrors, [
                                            'location' => '#bodySpan',
                                            'msg' => $bodyErr
                                        ]);
                        } else {
                                        $body = test_input($formArray['body']);
                                        if (!preg_match("/^[a-zA-Z\s]*$/", $body)) {
                                            $bodyErr = "Body can only contain letters and spaces";
                                            array_push($formErrors, [
                                                'location' => '#bodySpan',
                                                'msg' => $bodyErr
                                            ]);
                                        }
                                $body = filter_var($formArray['body'], FILTER_SANITIZE_STRING);
                        }

                        if (!(empty($formArray['surveyLink']))) {
                            $survey = test_input($formArray['survey']);
                            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $survey)) {
                            $surveyErr = "Survey is not a URL";
                                array_push($formErrors, [
                                'location' => '#surveySpan',
                                'msg' => $surveyErr
                                ]);
                            }
                        }

                        if (empty($_POST['depts'])) {
                            $deptsErr = "Needs at least one department checked";
                            array_push($formErrors, [
                                                            'location' => '#deptCheckSpan',
                                                            'msg' => $deptsErr
                                                            ]);
                        }

                        if (empty($_POST['mediaCheck']))) {
                            $mediaErr = "Needs at least one media checked;"
                            array_push($formErrors, [
                                                    'location' => '#deptCheckSpan',
                                                    'msg' => $deptsErr
                                                    ]);
                        }

                    }//end of checks for screen 5 on the create account page

            if ($_POST['formSection'] == 'screen6') { //checks the AdminConsole New Employee
                                    //check First name, last name, email, phone number. Active, Authorization doesn't need additional val/san. Department already has required tag.

                                    //turns $_POST string into usable array called formArray
                                    $formArray = array();
                                    $json = $_POST['formData'];
                                    $jsonIterator = new RecursiveIteratorIterator(
                                        new RecursiveArrayIterator(json_decode($json, true)),
                                        RecursiveIteratorIterator::SELF_FIRST
                                    );
                                    foreach ($jsonIterator as $key => $val) {
                                        if (is_array($val)) {
                                            //echo "$key:\n";
                                            $formArray[$val[0]] = $val[1];
                                        } else {
                                        }
                                    }

                                    if (empty($formArray['fName'])) {
                                                    $fNameErr = "First name is required";
                                                    array_push($formErrors, [
                                                        'location' => '#empFirstSpan',
                                                        'msg' => $fNameErr
                                                    ]);
                                    } else {
                                                    $fName = test_input($formArray['fName']);
                                                    if (!preg_match("/^[a-zA-Z\s]*$/", $fName)) {
                                                        $fNameErr = "First Name can only contain letters and spaces";
                                                        array_push($formErrors, [
                                                            'location' => '#empFirstSpan',
                                                            'msg' => $fNameErr
                                                        ]);
                                                    }
                                            $fName = filter_var($formArray['fName'], FILTER_SANITIZE_STRING);
                                    }

                                    if (empty($formArray['lName'])) {
                                                    $lNameErr = "Last Name is required";
                                                    array_push($formErrors, [
                                                        'location' => '#empLastSpan',
                                                        'msg' => $lNameErr
                                                    ]);
                                    } else {
                                                    $lName = test_input($formArray['lName']);
                                                    if (!preg_match("/^[a-zA-Z\s]*$/", $lName)) {
                                                        $lNameErr = "Body can only contain letters and spaces";
                                                        array_push($formErrors, [
                                                            'location' => '#empLastSpan',
                                                            'msg' => $lNameErr
                                                        ]);
                                                    }
                                            $lName = filter_var($formArray['lName'], FILTER_SANITIZE_STRING);
                                    }

                                    if (empty($formArray['phoneNumber'])) {
                                        $input = $formArray['phoneNumber'];
                                                                $phone = test_input($input);
                                                                $pattern = '/^\d{3}-\d{3}-\d{4}$/';
                                                                if (!preg_match($pattern, $phone)) {
                                                                    $phoneErr = "Invalid phone number";
                                                                    array_push($formErrors, [
                                                                        'location' => '#empPhoneSpan',
                                                                        'msg' => $phoneErr
                                                                    ]);
                                                                }
                                                                //built in php functions that i think could work as well, if needed
                                                                $phone = filter_var($formArray['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
                                                                //filter_var($postData['phone'], FILTER_VALIDATE_INT);
                                    }

                                    if (empty($formArray['email']))) {
                                                        $emailErr = "Email is required";
                                                        array_push($formErrors, [
                                                            'location' => '#empEmailSpan',
                                                            'msg' => $emailErr
                                                            ]);
                                                        } else {
                                                        $email = test_input($formArray['email']);
                                                        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                                                        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                                                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                                $emailErr = "Invalid format for email";
                                                                array_push($formErrors, [
                                                                    'location' => '#empEmailSpan',
                                                                    'msg' => $emailErr
                                                                ]);
                                                            }
                                                        }

                                }//end of checks for screen 6 on the create account page

                if ($_POST['formSection'] == 'screen7') { //checks the videos page links
                                                    //check video url
                    if (empty($formArray['link']))) {
                     } else {
                        $link = test_input($formArray['link']);
                        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $survey)) {
                            $linkErr = "The link is not a valid URL";
                            array_push($formErrors, [
                                    'location' => '#linkSpan',
                                    'msg' => $linkErr
                                ]);
                            }
                     }
                }//end of checks for screen 7 on videos page

                if ($_POST['formSection'] == 'screen8') { //checks the reset password.
                                                                    //check email, form already checks if empty

                                                        $email = test_input($formArray['email']);
                                                        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                                                        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                                                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                                $emailErr = "Invalid format for email";
                                                                array_push($formErrors, [
                                                                    'location' => '#emailSpan',
                                                                    'msg' => $emailErr
                                                                ]);
                                                            }
                                    }
                }//end of checks for screen 8 on resetPassword
            

        //      //checks for adminConsole
        //      //variables for a good user input 
        //      $title = $body = "";
        //      //variables for error message
        //      $titleErr = $bodyErr = "";
             
        //      //check title
        //      if(empty($_POST['title'])){
        //          $titleErr = "A title is required";
        //      }
        //      else{
        //          $title = test_input($_POST['title']);
        //          $title = filter_var($title, FILTER_SANITIZE_STRING);
 
        //      }
        //      //check body
        //      if(empty($_POST['body'])){
        //          $bodyErr = "The body of your notification cannot be empty";
        //      }
        //      else{
        //          $body = test_input($_POST['body']);
        //          $body = filter_var($body, FILTER_SANITIZE_STRING);
        //      }
        // }
        if (empty($formErrors)) {
            return ['isValidForm' => true];
        }
        return $formErrors;
    }

    //test to see if the user input has extra whitespaces, slashes, or special characters. Removes them if so.

    function createNewsTable($notificationArray)
    {
        $string = "";
        $rowCount = 1;
        $nextRowCount = 2;
        foreach ($notificationArray as $rowArray) {
            $currNotiID = $rowArray['notificationID'];
            $currTitle = $rowArray['title'];
            $currSurvey = $rowArray['surveyLink'];
            $currBody = $rowArray['body'];
            //split file path using /
            //Take the end of the array becuse it is the name of the file
            $currAttachmentName = end(explode("/", $rowArray['attachment']));
            //echo "Attachment: $currAttachmentName";
            if ($currAttachmentName == "") {
                $currAttachmentName = "No Attachment";
            }
            $currActiveYN = (intval($rowArray['active']));

            $string .= <<<END
            <form class="" action="adminAction.php?id={$currNotiID}" method="post" enctype="multipart/form-data">
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>
                    <input type="text" name="title" disabled value="{$currTitle}">
                </td>
                <td>
                    <select disabled name='activeYN' class='disabledDrop'>
                        <option value='1'>Yes</option>
                        <option
END;
            if (!$currActiveYN) $string .= " selected ";
            $string .= <<<END

                         value='0'>No</option>
                    </select>
                </td>
                <td>
                    <i id='notiEditButton' onclick="dropDownModify(this,'noti');" class='fas fa-pencil-alt'></i>
                    <button class="hidden" id='notiSaveEditButton' type= "submit" name="modifyNoti" value="modifyNoti"><i class="fas fa-save"></i></button>
                </td>
                <td>
                    <button type="submit" name= "deleteNoti" value="deleteNoti"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <tr class='collapsed' style="display: none">
                <td colspan='5' class='full'>
                    <h2>Body</h2>
                    <textarea id='bodyContent' name="body" disabled>{$currBody}</textarea>
                    <h2>Survey Link</h2>
                    <input type="text" class='block inputNoIcon' value="{$currSurvey}" disabled name="surveyLink">
                    <h2>Attachment</h2>
                    <button type="submit" name= "removeNotiAttachment" value="removeNotiAttachment"><i class="fas fa-times-circle"></i></button>
                    <span>{$currAttachmentName}</span>
                    <h2>User Ack. Report</h2>
                    <i onclick="location.href='downloadAckReport.php?id={$currNotiID}'" class="fas fa-download"></i><span>user_report.csv</span>
                </td>
            </tr>
            <tr class='spacer'><td></td></tr>
        </form>
END;
            $rowCount++;
            $nextRowCount++;
        }

        return $string;
    }

    function createUserTable($allUserArray)
    {
        //$phone,$fName,$lName,$tempPassYN,$password, $email, $deptID, $authID, $userID, $activeYN
        $string = '';
        foreach ($allUserArray as $thisUserArray) {
            $currID = $thisUserArray['userID'];
            $currFName = $thisUserArray['fName'];
            $currLName = $thisUserArray['lName'];
            $currActiveYN = $thisUserArray['activeYN'];
            $currEmail = $thisUserArray['email'];
            $currDeptID = $thisUserArray['deptID'];
            $currAuthID = $thisUserArray['authID'];
            $currPhone = $thisUserArray['phone'];
            if ($currAuthID != 1) {

                $string .= <<<END
            <form class="" action="adminAction.php?id={$currID}" method="post">
                <tr class='collapsed'>
                    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                    <td><input type="text" name="fName" disabled value="{$currFName}"></td>
                    <td><input type="text" name="lName" disabled value="{$currLName}"></td>
                    <td>
                        <i id='empEditButton' onclick="dropDownModify(this,'emp');" class='fas fa-pencil-alt'></i>
                        <button class="hidden" id='empSaveEditButton' type= "submit" name="modifyEmp" value="modifyEmp"><i class="fas fa-save" onclick=''></i></button>
                    </td>
                    <td>
                        <button type="submit" name= "deleteEmp" value="deleteEmp"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>

                <tr class='spacer'><td></td></tr>

                <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

                <tr class='collapsed' style="display: none">
                    <td colspan='3' class='leftUnCollapsed'>
                        <h2>Active</h2>
                        <select disabled name='activeYN' class='disabledDrop'>
                            <option value=1>Yes</option>
                            <option
END;
                if (!$currActiveYN) $string .= " selected ";
                $string .= <<<END
                        value=0>No</option>

                        </select>

                        <h2>Department</h2>
                        <select disabled name='deptID' class='disabledDrop'>
                            <option
END;
                if ($currDeptID == 1) $string .= " selected ";
                $string .= <<<END
                            value=1>HR</option>
                            <option
END;
                if ($currDeptID == 2) $string .= " selected ";
                $string .= <<<END
                            value=2>Admin</option>
                            <option
END;
                if ($currDeptID == 3) $string .= " selected ";
                $string .= <<<END
                            value=3>Sales</option>
                            <option
END;
                if ($currDeptID == 4) $string .= " selected ";
                $string .= <<<END
                            value=4>Production</option>
                            <option
END;
                if ($currDeptID == 5) $string .= " selected ";
                $string .= <<<END
                            value=5>Operations</option>
                            <option
END;
                if ($currDeptID == 6) $string .= " selected ";
                $string .= <<<END
                            value=6>Food and Beverage</option>
                            <option
END;
                if ($currDeptID == 7) $string .= " selected ";
                $string .= <<<END
                            value=7>Garage</option>
                        </select>

                        <h2>Email</h2>
                        <input type="text" name="email" class='email' disabled value="{$currEmail}">
                    </td>
                    <td colspan='2' class='rightUnCollapsed'>
                        <h2>Authorization</h2>
                        <select disabled name='authID' class='disabledDrop fullWidth'>
                            <option
END;
                if ($currAuthID == 2) $string .= " selected ";
                $string .= <<<END
                            value=2>Employee</option>
                            <option
END;
                if ($currAuthID == 3) $string .= " selected ";
                $string .= <<<END
                            value=3>Depart. Head</option>
                            <option
END;
                if ($currAuthID == 4) $string .= " selected ";
                $string .= <<<END
                            value=4>Administrator</option>
                        </select>

                        <h2>Phone Number</h2>
                        <input type="text" name="phone" disabled value="{$currPhone}">
                    </td>
                </tr>
            </form>
END;
            }
        }

        return $string;
    }

    function createPendingUserTable($allUserArray)
    {
        $string = '';
        foreach ($allUserArray as $thisUserArray) {
            $currID = $thisUserArray['userID'];
            $currFName = $thisUserArray['fName'];
            $currLName = $thisUserArray['lName'];
            $currActiveYN = $thisUserArray['activeYN'];
            $currEmail = $thisUserArray['email'];
            $currDeptID = $thisUserArray['deptID'];
            $currAuthID = $thisUserArray['authID'];
            $currPhone = $thisUserArray['phone'];

            $string .= <<<END
            <form class="" action="adminAction.php?id={$currID}" method="post">
                <tr class='collapsed'>
                    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                    <td>{$currFName}</td>
                    <td>{$currLName}</td>
                    <td>
                        <button type="submit" name= "confirmPendEmp" value="confirmPendEmp"><i class="fas fa-check-circle"></i></button>
                    </td>
                    <td>
                        <button type="submit" name= "denyPendEmp" value="denyPendEmp"><i class="fas fa-minus-circle"></i></button>
                    </td>
                </tr>

                <tr class='spacer'><td></td></tr>
                <tr class='un-collapsed'>
                    <td colspan="5">
                        <h2>Authorization Level</h2>
                        <select name='pendingAuthID' id='authLevel'>
                            <option value=2>Employee</option>
                            <option value=3>Department Head</option>
                            <option value=4>Admin</option>
                        </select>
                    </td>
                </tr>
                <tr class='spacer'><td></td></tr>
            </form>
END;
        }

        return $string;

    }

    function createLandingNewsTable($notificationArray)
    {
        $string = "";
        $imgNum = 1;
        foreach ($notificationArray as $currNotiArray) {
            $currNotiID = $currNotiArray['notificationID'];
            $currTitle = $currNotiArray['title'];
            $currBody = $currNotiArray['body'];
            $timeStamp = $currNotiArray['time'];
            $webAppYN = $currNotiArray['webAppYN'];
            $activeYN = $currNotiArray['active'];

            $now = new DateTime(null, new DateTimeZone('America/New_York'));
            $dateStamp = new DateTime($timeStamp, new DateTimeZone('America/New_York'));

            $mins = $dateStamp->diff($now)->format("%i");
            $hours = $dateStamp->diff($now)->format("%h");
            $days = $dateStamp->diff($now)->format("%d");
            if (intval($hours) < 1) {
                $timesig = $mins . "m ago";
            } else if (intval($days) < 1) {
                //display using hours
                $timesig = $hours . "h ago";
            } else if (intval($days) >= 1 && intval($days) >= 6) {
                //display using days
                $timesig = $days . "d ago";
            } else if (intval($days) >= 7) {
                //display using weeks
                $timesig = ($days % 7) . "w ago";
            }

            if ($imgNum <= 6) {
                $imgNum++;
            } else {
                $imgNum = 1;
            }
            if ($webAppYN && $activeYN) {
                $string .= <<<END
                    <div class='notifContainer' id='{$currNotiID}'>
                        <div class='overlay'>
                            <img src='../assets/images/{$imgNum}.jpg'>
                        </div>

                        <h2 class='title'>{$currTitle}</h2>

                        <div class='subtitle block'>
                            <div class='posted inline'>
                                <i class="far fa-clock"></i>
                                <span class='inline'>{$timesig}</span>
                            </div>
                            <a type='submit' class='inline' href='notification.php?id={$currNotiID}&img={$imgNum}'>read more</a>
                        </div>

                        <!-- Admin Feature only -->
                        <button type="button" class="button
END;
                if ($_SESSION['authID'] < 4) $string .= " hidden";
                $string .= <<<END
"><i class="far fa-edit"></i></button>
                        <div class='buttonOptions' style="display:none" >
                            <ul class='spaced'>
                                <li>Modify<i class='fas fa-pencil-alt'></i></li>
                                <li>Delete<i class="fas fa-trash-alt"></i></li>
                            </ul>
                        </div>
                    </div>
END;
            }
        }
        return $string;
    }


    function createIndividualNotification($notiArray, $imgNum)
    {
        $currTitle = $notiArray[0]['title'];
        $currNotiID = $notiArray[0]['notificationID'];
        $currBody = $notiArray[0]['body'];
        $currSurvey = $notiArray[0]['surveyLink'];
        $timeStamp = $notiArray[0]['postDate'];
        $currAttachmentName = end(explode("/", $notiArray[0]['attachment']));
        //echo "Attachment: $currAttachmentName";
        if ($currAttachmentName == "") {
            $currAttachmentName = "No Attachment";
        }

        $now = new DateTime(null, new DateTimeZone('America/New_York'));
        $dateStamp = new DateTime($timeStamp, new DateTimeZone('America/New_York'));
        $mins = $dateStamp->diff($now)->format("%i");
        $hours = $dateStamp->diff($now)->format("%h");
        $days = $dateStamp->diff($now)->format("%d");
        //less than an hour use mins
        if (intval($hours) < 1) {
            $timesig = $mins . "m ago";
        } else if (intval($days) < 1) {
            //display using hours
            $timesig = $hours . "h ago";
        } else if (intval($days) >= 1 && intval($days) >= 6) {
            //display using days
            $timesig = $days . "d ago";
        } else if (intval($days) >= 7) {
            //display using weeks
            $timesig = ($days % 7) . "w ago";
        }

        $string = <<<END
        <div class='imageContainer'>
            <div class='overlay'>
                <img src='../assets/images/{$imgNum}.jpg'> <!-- Needs to be same image as on landing page -->
            </div>
        </div>

        <!-- Content -->
        <div class='container'>
            <h2 class='title'>{$currTitle}</h2>
            <div class='subtitle block'>
                <i class="fas fa-download inline"></i>
                <span class='inline'>{$currAttachmentName}</span>
                <i class="far fa-clock inline"></i>
                <span class='inline'>{$timesig}</span>
            </div>
            <span class='copy block'>{$currBody}</span>
END;
        if (strlen($currSurvey) > 2) {;
            $string .= "<a href='{$currSurvey}'><i class='fas fa-link'></i>Survey Link</a>";
        }
        $string .= <<<END
            <form action="notiAck.php?id={$currNotiID}&img={$imgNum}" method="post">
                <button type="submit">I Acknowledge</button>
            </form>
        </div>
END;
        return $string;
    }
}
