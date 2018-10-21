<?php
require '../vendor/autoload.php';
//require '../database/data_layer.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;

function test_input($info) {
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
            echo('user created');
            break;
    }
}

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

        $validatedPOST = [];
        $formErrors  = [];
        //$validatedPOST['phone'] = $postData['phone'];// this should be validated and sanitized
        //return $validatedPOST;

        //variables for error message
        $phoneErr = "";
        //$pwdErr = $pwdConfirmErr = $fnameErr = $lnameErr = $emailErr = "";
        //variables for good user input (val and san checks)
        $phone = "";
        //$pwd = $pwdConfirm = $fname = $lname = $email = "";
        
        //check if form was submitted with POST 
        if($_POST['formSection'] == 'screen1'){
            //checks for createAcct page
            //check phoneNumber, password, passwordConfirm, fName, lName, and email
            //check phone number
            if(empty($_POST['formData']['phoneNumber'])){
                $phoneErr = "Phone Number is required";
                array_push($formErrors, [
                    'location' => '#phoneSpan',
                    'msg' => $phoneErr
                ]);
            } 
            //above works
            else if(isset($_POST['phoneNumber'])){
                $input = $_POST['phoneNumber'];
                $phone = test_input($input);
                //^1?([1-9])(\d{9}) - phone regex
                //^\d{3}-\d{3}-\d{4}$ - another phone option
                $pattern = '/^1?([1-9])(\d{9})/';
                if(!preg_match($pattern, $phone)){
                    $phoneErr = "Invalid phone number";
                    array_push($formErrors, [
                        'location' => '#phoneSpan',
                        'msg' => $phoneErr
                    ]);
                }
                //built in php functions that i think could work as well, if needed
                $phone = filter_var($_POST['phoneNumber'],FILTER_SANITIZE_NUMBER_INT);
                //filter_var($postData['phone'], FILTER_VALIDATE_INT);
            }
        }


        //     //checks password
        //     if(empty($_POST['password'] && empty($_POST['passwordConfirm']))){
        //         $pwdErr = "Password is required";
        //         $pwdConfirmErr = "Confirmation Password is required";
        //     }
        //     else{
        //         $pwd = test_input($_POST['password']);
        //         $pwdConfirm = test_input($_POST['passwordConfirm']);
        //         //check that password and passwordConfirm match
        //         if($pwdConfirm == $pwd){
        //             //check for length of at least 8
        //             if(!strlen($pwd >= 8)){
        //                 $pwdErr = "Password contain 8 characters";
        //             }
        //             //check for one upper case
        //             if(!preg_match("/[A-Z]/", $pwd)){
        //                 $pwdErr = "Password must contain at least 1 uppercase letter";
        //             }
        //             //check for one lower case
        //             if(!preg_match("/[a-z]/", $pwd)){
        //                 $pwdErr = "Password must contain at least one lowercase letter";
        //             }
        //             //check for one number
        //             if(!preg_match("/[1-9]/",$pwd)){
        //                 $pwdErr = "Password must contain at least one digit";
        //             }
        //         }
        //     }
        //     //checks fname
        //     if(empty($_POST['fName'])){
        //         $fnameErr = "First name is required";
        //     }
        //     else{
        //         $fname = test_input($_POST['fName']);
        //         if(!preg_match("/^[A-Za-z]+$/", $fname)){
        //             $fnameErr = "First name can only contain letters with no spaces";
        //         }
        //         $fname = filter_var($_POST['fName'],FILTER_SANITIZE_STRING);
        //     }
        //     //checks lname
        //     if(empty($_POST['lName'])){
        //         $lnameErr = "Last name is required";
        //     }
        //     else{
        //         $lname = test_input($_POST['lName']);
        //         if(!preg_match("/^[A-Za-z]+$/", $lname)){
        //             $lnameErr = "Last name can only contain letters with no spaces";
        //         }
        //         $lname = filter_var($_POST['lName'],FILTER_SANITIZE_STRING);
        //     }
        //     //checks email
        //     if(empty($_POST['email'])){
        //         $emailErr = "Email is required";
        //     }
        //     else{
        //         $email = test_input($_POST['email']);
        //         $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        //         if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        //             $emailErr = "Invalid format for email";
        //         }
        //      }

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
            $currActiveYN = (intval($rowArray['active'])) ? ('yes') : ('no');

            $string .= <<<END
            <form class="" action="adminAction.php?id={$currNotiID}" method="post">
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-up'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>
                    <input type="text" name="title" disabled value="{$currTitle}">
                </td>
                <td>
                    <select disabled name='activeYN' class='disabledDrop'>
                        <option value='1'>Yes</option>
                        <option
END;
            if ($currActiveYN === 'no') $string .= " selected ";
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

            <tr class='un-collapsed'>
                <td colspan='5' class='full'>
                    <h2>Body</h2>
                    <textarea id='bodyContent' name="body" disabled>{$currBody}</textarea>
                    <h2>Attachment</h2>
                    <i class="fas fa-times-circle"></i><span>{$currAttachment}</span>

                    <h2>User Ack. Report</h2>
                    <i class="fas fa-download"></i><span>user_report.csv</span>
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

    function createUserTable($allUserArray){
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

                <tr class='un-collapsed'>
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

        return $string;
    }


}
