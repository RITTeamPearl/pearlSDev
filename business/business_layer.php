<?php
require '../vendor/autoload.php';
require '../database/data_layer.php';
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
        //password.....in plain text
        $mail->Password = $_SERVER['EMAIL_PASSWORD'];

        try{
            //set attributes to passed in
            $mail->setFrom('team-pearl-rit@gmail.com', 'RIT Team Pearl');
            $mail->addAddress($address);
            $mail->Subject = $subject;
            $mail->Body = $body;

            //Send it
            $mail->send();
            return true;
        }
        catch (\Exception $e)
        {
           echo $e->getMessage();
           return false;
        }
    }

    function sendText(){
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = $_SERVER['TWILIO_SID'];
        $auth_token = $_SERVER['TWILIO_TOKEN'];

        // A Twilio number you own with SMS capabilities
        $twilio_number = $_SERVER['TWILIO_PHONE'];;

        $client = new Client($account_sid, $auth_token);
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
        //generate url to send in email. ../resetPassword.php?emailToReset
        //send email
        //confirm email that is being reset
        //if input matches $_GET as well as some unique identifier then reset the password
        $dataLayer = new data_layer();
        //generate a random 10 character string
        $genPass = substr(md5(microtime()),rand(0,26),10);
        //database call to set new password and update temp password flag
        $dataLayer->setUserTempPass($email,$genPass);
        //echo "your new password is $genPass";

        //address , subject line, body
        //if($bizLayer->sendEmail($_POST['email'], 'Password Reset test', "Your New Password is $genPass")){
        //};


    }


}
