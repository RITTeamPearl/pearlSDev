<?php
require '../vendor/autoload.php';
require '../database/data_layer.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
class business_layer{

    function sendEmail($address,$subject, $body){
        //need to set time for smtp purposes
        date_default_timezone_set('Etc/EST');

        //new instance, set to google smtp settings standards
        $mail = new PHPMailer(TRUE);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        //email address to send from
        $mail->Username = 'teampearlrit@gmail.com';
        //password.....in plain text
        $mail->Password = 'T3@mP3@rl!';

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
        $account_sid = 'AC03cb27728fb0055b67a9fe7bd9e2d826';
        $auth_token = '131fffeed54d706660bd6f36f774c19b';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = "+18572145309";

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
