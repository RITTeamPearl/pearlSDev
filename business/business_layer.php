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

    function validateAndSanitize($postData){

        //$validatedPOST = array();
        //$validatedPOST['phone'] = $postData['phone'];// this should be validated and sanitized
        //return $validatedPOST;
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
            $currDeptID = $thisUserArray['deptID'];

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

                <tr id = "row-97" class='un-collapsed'>
                    <td colspan='3' class='leftUnCollapsed'>
                        <h2>Active</h2>
                        <select disabled name='activeYN' class='disabledDrop'>
                            <option value='1'>Yes</option>
                            <option value='2'>No</option>
                        </select>

                        <h2>Department</h2>
                        <select disabled name='department' class='disabledDrop'>
                            <option value='1'>HR</option>
                            <option value='2'>Admin</option>
                            <option value="3">Sales</option>
                            <option value="4">Production</option>
                            <option value="5">Operations</option>
                            <option value="6">Food and Beverage</option>
                            <option value="7">Garage</option>
                        </select>

                        <h2>Email</h2>
                        <input type="text" name="email" class='email' disabled value="masonsantora@gmail.com">
                    </td>
                    <td colspan='2' class='rightUnCollapsed'>
                        <h2>Authorization</h2>
                        <select disabled name='authorization' class='disabledDrop fullWidth'>
                            <option value='1'>Employee</option>
                            <option value='2'>Depart. Head</option>
                            <option value='2'>Administrator</option>
                        </select>

                        <h2>Phone Number</h2>
                        <input type="text" name="phone" disabled value="555-555-5555">
                    </td>
                </tr>
            </form>
END;
        }

        return $string;
    }


}
