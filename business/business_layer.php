<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/vendor/autoload.php";
//require '../database/data_layer.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
class business_layer{
    /**
     * sends an email using passed in params
     * @param  [string]  $address        [who the email is going to]
     * @param  [string]  $subject        [subject line]
     * @param  [string]  $body           [body]
     * @param  [string] $attachmentPath [this is set if an attachment is added]
     */
    function sendEmail($address,$subject, $body, $attachmentPath=0){
        //need to set time for smtp purposes
        date_default_timezone_set('America/New_York');
        //new instance of PHPMailer, set to google smtp settings standards
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
            //set attributes to passed in vars
            $mail->setFrom('team-pearl-rit@gmail.com', 'RIT Team Pearl');
            $mail->addAddress($address);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->IsHTML(true);
            //default value is zero, so it only adds attachment if passed
            if ($attachmentPath){
                //make db path relative using ../
                $mail->addAttachment("../".$attachmentPath);
            }
            //Send it
            $mail->send();
            return true;
        }
        catch (\Exception $e)//Slash is for generic exception class
        {
            //something went wrong
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Sends a text using passed in
     * @param  [type] $text [text merssage to send]
     * @param  [type] $phoneNumber [defaults to my phone because twillio]
     */
    function sendText($text,$phoneNumber='15856455810'){
        // Twilio credentials
        $account_sid = $_SERVER['TWILIO_SID'];
        $auth_token = $_SERVER['TWILIO_TOKEN'];
        $twilio_number = $_SERVER['TWILIO_PHONE'];;

        //Create twilio client using our credentials
        $client = new Client($account_sid, $auth_token);
        //send message
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phoneNumber,//should be a passed in $phoneNumber but free version doesnt allow it.
            array(
                'from' => $twilio_number,
                'body' => $text
            )
        );
    }

    function uploadFile($fileArray,$callBack){
        //Passed in name of the file with extension
        $name = $fileArray['name'];
        //File type
        $type = strtolower(end(explode('.',$name)));

        if ($callBack == 'csv') $name = "currEmpCSV.$type";
        //Temp file stored from upload. Full path
        $tempFile = $fileArray['tmp_name'];
        //targetFile
        $targetFile = "../assets/uploads/" . $name;


        $notAllowedTypes = array("exe", "js", "sql", "php");

        //if its not the csv checker make sure the type is not in the blacklist
        if($callBack != 'csv' && !in_array($type,$notAllowedTypes)){
            //if it already exists overwrite it
            move_uploaded_file($tempFile,$targetFile);
            return true;
        }

        //if it is the csv checker and the type is csv upload it
        if ($callBack == 'csv' && $type == 'csv'){
            //if it already exists overwrite it
            move_uploaded_file($tempFile,$targetFile);
            return true;
        }
        if ($callBack == 'csv' && $type != 'csv'){
            //if they upload a type that is not a csv delete the current one so it doest think they just uploaded it
            if(file_exists("../assets/uploads/currEmpCSV.csv")) unlink("../assets/uploads/currEmpCSV.csv");
            return false;
        }

        else {
            //Not correct file type
            return false;
        }
    }

    function valAndSanUser($postData){
        $validatedPOST = array();
        foreach ($postData as $key => $value) {
            //if key = phoneNumber check to make sure it is a string of digits
                //ctype_digit($value)
            //if key = email
                //filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                //filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            //if key fname, lName check to make sure its more than 2 chars
                //filter_var($_POST['l/fname'], FILTER_VALIDATE_STRING);
            //if key contains ID make sure its an int
                //if( strpos( $key, "ID" ) !== false) {
                    //filter_var($int, FILTER_VALIDATE_INT) === 0 || filter_var($int, FILTER_VALIDATE_INT)
                //}
            //if key contains YN make sure its binary
                //if( strpos( $key, "YN" ) !== false) {
                    //binary regex
                    //preg_match('~^[01]+$~', $value)
                //}
            //if key = password regex check the password
                // lower case, upper case, number, 6 characters min
                //preg_match('^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$', $value)
        }
        //return $validatedPOST;
    }
    function valAndSanNoti($postData){
        $validatedPOST = array();
        foreach ($postData as $key => $value) {
            //if key = viewableBy check to make sure it is a string of digits
                //ctype_digit($value)
            //if key = surveyLink
                //regex to make sure it is a correct url
                //FILTER_VALIDATE_URL
            //if key title, body check to make sure its more than 2 chars
                //filter_var($_POST['l/fname'], FILTER_VALIDATE_STRING);
            //if key = sentBy
                //make sure its an int
                //filter_var($int, FILTER_VALIDATE_INT)
            //if key contains YN make sure its binary
                //make sure its an int
                //if( strpos( $key, "YN" ) !== false) {
                    //binary regex
                    //preg_match('~^[01]+$~', $value)
                //}
            //if key contains dept it is a checkbox, make sure its a string of digits
                //sanitize string first
                    //if( strpos( $key, "dept" ) !== false) {
                        //ctype_digit($value)
                    //}
            //if key = attachment
                //regex to make sure it is a correct attacment: ../../file.type
            //if key = sendNoti,deleteNoti,modifyNoti,removeNotiAttachment
                //these are just to tell what button was pressed. set it = ""
            //if key = contains Check it is another checkbox.
                //these are a string of a single digit
                //ctype_digit($value)
        }
        //return $validatedPOST;
    }




}
