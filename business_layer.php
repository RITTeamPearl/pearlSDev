<?php
require '../vendor/autoload.php';
//require '../database/data_layer.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
class business_layer{

    function sendEmail($address,$subject, $body){
        //need to set time for smtp purposes
        date_default_timezone_set('America/New_York');

        //new instance, set to google smtp settings standards
        $mail = new PHPMailer(TRUE);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        //email address to send from
        $mail->Username = $_SERVER['EMAIL_ADDR'];
        //password
        $mail->Password = $_SERVER['EMAIL_PASSWORD'];

        try{
            //set attributes to passed in
            $mail->setFrom('team-pearl-rit@gmail.com', 'RIT Team Pearl');
            $mail->addAddress($address);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->IsHTML(true);

            //Send it
            $mail->send();
            return true;
        }
        catch (\Exception $e)//Slash is for generic exception class
        {
           echo $e->getMessage();
           return false;
        }
    }

    function sendText(){
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
                'body' => 'It Works!'
            )
        );
    }

    function passwordReset($email){
        $hashedEmail = password_hash($email,PASSWORD_DEFAULT);

        $subject = "RRCC Account Password Reset";
        $body = "<h1>Please click the link below to reset your password</h1>";
        $body .= "<h2 href='localhost/views/resetConfirm.php?email=$hashedEmail' >Reset</h2>";
        //generate url to send in email. ../resetPassword.php?emailToReset
        //send email
        //confirm email that is being reset
        //if input matches $_GET as well as some unique identifier then reset the password


        // $dataLayer = new data_layer();
        // //generate a random 10 character string.
        // $genPass = substr(md5(microtime()),rand(0,26),10);
        // //database call to set new password and update temp password flag
        // $dataLayer->setUserTempPass($email,$genPass);
        //echo "your new password is $genPass";

        //address , subject line, body
        //if($bizLayer->sendEmail($_POST['email'], 'Password Reset test', "Your New Password is $genPass")){
        //};
    }

    function validateAndSanitize(){

        //$validatedPOST = array();
        //$validatedPOST['phone'] = $postData['phone'];// this should be validated and sanitized
        //return $validatedPOST;

        //variables for error message
        $phoneErr = $pwdErr = $pwdConfirmErr = $fnameErr = $lnameErr = $emailErr = "";
        //variables for good user input (val and san checks)
        $phone = $pwd = $pwdConfirm = $fname = $lname = $email = "";
        
        //check if form was submitted with POST 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //checks for createAcct page
            //check phoneNumber, password, passwordConfirm, fName, lName, and email
            //check phone number
            if(empty($_POST['phone'])){
                $phoneErr = "Phone Number is required";
            }
            else{
                $phone = test_input($_POST['phone']);
                if(!preg_match("^1?([1-9])(\\d{9})", $phone)){
                    $phoneErr = "Invalid phone number";
                }
                //built in php functions that i think could work as well, if needed
                //filter_var($postData['phone'],FILTER_SANITIZE_NUMBER_INT);
                //filter_var($postData['phone'], FILTER_VALIDATE_INT);
            }
            //checks password
            if(empty($_POST['password'] && empty($_POST['passwordConfirm']))){
                $pwdErr = "Password is required";
                $pwdConfirmErr = "Confirmation Password is required";
            }
            else{
                $pwd = test_input($_POST['password']);
                $pwdConfirm = test_input($_POST['passwordConfirm']);
                //check that password and passwordConfirm match
                if($pwdConfirm == $pwd){
                    //check for length of at least 8
                    if(!strlen($pwd >= 8)){
                        $pwdErr = "Password contain 8 characters";
                    }
                    //check for one upper case
                    if(!preg_match("/[A-Z]/", $pwd)){
                        $pwdErr = "Password must contain at least 1 uppercase letter";
                    }
                    //check for one lower case
                    if(!preg_match("/[a-z]/", $pwd)){
                        $pwdErr = "Password must contain at least one lowercase letter";
                    }
                    //check for one number
                    if(!preg_match("/[1-9]/",$pwd)){
                        $pwdErr = "Password must contain at least one digit";
                    }
                }
            }
            //checks fname
            if(empty($_POST['fName'])){
                $fnameErr = "First name is required";
            }
            else{
                $fname = test_input($_POST['fName']);
                if(!preg_match("/^[A-Za-z]+$/", $fname)){
                    $fnameErr = "First name can only contain letters with no spaces";
                }
                //filter_var($postData['fName'],FILTER_SANITIZE_STRING);
            }
            //checks lname
            if(empty($_POST['lName'])){
                $lnameErr = "Last name is required";
            }
            else{
                $lname = test_input($_POST['lName']);
                if(!preg_match("/^[A-Za-z]+$/", $lname)){
                    $lnameErr = "Last name can only contain letters with no spaces";
                }
                //filter_var($postData['fName'],FILTER_SANITIZE_STRING);
            }
            //checks email
            if(empty($_POST['email'])){
                $emailErr = "Email is required";
            }
            else{
                $email = test_input($_POST['email']);
                $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $emailErr = "Invalid format for email";
                }
             }

             //checks for adminConsole
             //variable for a good tile 
             $title = "";
             //variable for a bad title error message
             $titleErr = "";
             
             //check title. Not checking the body because the admin should be able to enter whatever they want.
             if(empty($_POST['title'])){
                 $titleErr = "A title is required";
             }
             else{
                 $title = test_input($_POST['title']);
             }
        }
    }

    //test to see if the user input has extra whitespaces, slashes, or special characters. Removes them if so.
    function test_input($info) {
        $info = trim($info);
        $info = stripslashes($info);
        $info = htmlspecialchars($info);
        return $info;
      }

    function createNewsTable($notificationArray){
        $string = "";
        $rowCount = 1;
        $nextRowCount = 2;
        foreach ($notificationArray as $rowArray) {
            //var_dump($rowArray);
            $currNotiID = $rowArray['notificationID'];
            $currTitle = $rowArray['title'];
            $currBody = $rowArray['body'];
            $currAttachment = $rowArray['attachment'];
            $currActiveYN = ($rowArray['active']) ? ('yes') : ('no');

            $string .= <<<END
            <tr id = "row-{$rowCount}"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>{$currTitle}</td>
                <td>{$currActiveYN}</td>
                <td><i onclick="dropDownModify(this);" class='fas fa-pencil-alt'></i></td>
                <td><form action="delete.php?id={$currNotiID}" method="post"> <button style="background-color: transparent; border-color:transparent" type="submit"><i class='fas fa-trash-alt'></i></button></form></td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->
            <!-- JQUERY Animate function does not work on TR so eventually we might want to convert this to a ul? -->
            <tr id = "row-{$nextRowCount}" class='un-collapsed'>
                <td colspan='3' class='leftUnCollapsed'>
                    <h2>Body</h2>
                    <span>{$currBody}</span>
                </td>
                <td colspan='2' class='rightUnCollapsed'>
                    <h2>Attachment</h2>
                    <!-- Make this 'fas fa-file-upload' with blue color, if no file exists and text saying 'No attachment' Create functionality for upload -->
                    <i class="fas fa-times-circle"></i><span>{$currAttachment}</span>

                    <h2>User Ack. Report</h2>
                    <i class="fas fa-download"></i><span>user_report.csv</span>
                </td>
            </tr>
            <tr class='spacer'><td></td></tr>
END;
        $rowCount++;
        $nextRowCount++;
        }

        return $string;
        //var_dump($notificationArray);
    }


}
