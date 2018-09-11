<?php
require '../vendor/autoload.php';
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
            //$mail->send();
            return true;
        }
        catch (\Exception $e)
        {
           echo $e->getMessage();
           return false;
        }
    }
}
