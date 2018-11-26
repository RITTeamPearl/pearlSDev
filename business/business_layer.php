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
        $error = array();
        foreach ($postData as $key => $val) {
            if ($key == "phoneNumber"){
                //regex to sanitize make sure its only digits
                $val = preg_replace("/[^0-9]+/", "", $val);
                //make sure it is a string of only digits. if not add to the error array
                if (!ctype_digit($val)) $error[] = $key;
            }

            if ($key == "email"){
                //sanitize email
                $val = filter_var($val,FILTER_SANITIZE_EMAIL);
                //make sure its a valid email. if not add to the error array
                if (!filter_var($val,FILTER_VALIDATE_EMAIL)) $error[] = $key;
            }
            if ($key == "fName" || $key == "lName"){
                //regex to sanitize string
                $val = preg_replace("/[^a-zA-Z]+/", "", $val);
                //check to make sure its between 2-26 chars. uf not add to error
                if (!preg_match('/[a-zA-Z]{2,26}/', $val)) $error[] = $key;
            }
            //if the key has ID in it they work the same way
            if (strpos($key,"ID") !== false) {
                //santize to make sure its only digits
                $val = preg_replace("/[^0-9]+/","",$val);
                //assure its an int
                $val = intval($val);
                //if its not an int add it to the error array
                if (!filter_var($val, FILTER_VALIDATE_INT)) $error[] = $key;
            }
            //if key contains YN its a binary flag
            if( strpos($key,"YN") !== false) {
                //binary regex for sanitization
                $val = preg_replace("/[^0-1]+/","",$val);
                $val = intval($val);
                //if it is not binary add it to the error array
                if (preg_match('/[^0-1]+/', $val)) $error[] = $key;
            }
            if ($key == "password"){
                // lower case, upper case, number, 6 characters min
                if (!preg_match('/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $val)) $error[] = $key;
            }
            //put the value back into the array for returning purposes
            $postData[$key] = $val;
        }
        //if error array is empty return the updated $postData
        return (count($error) == 0) ? ($postData) : (false);
    }
    function valAndSanNoti($postData){
        //There are filters for pretty much anything
        //https://www.w3schools.com/php/php_ref_filter.asp
        //regex tends to work better for sanitizing these, these ones are helpful:
        //preg_replace("/[^0-9]+/","",$val); get rid of anything other than 0-9
        //preg_replace("/[^a-zA-Z]+/", "", $val); get rid of anything other than A-Z
        $error = array();
        foreach ($postData as $key => $value) {
            //if key = viewableBy check to make sure it is a string of digits
                //this one is similar to phoneNumber
            //if key = surveyLink
                //regex to make sure it is a correct url
                //FILTER_VALIDATE_URL
            //if key title, body check to make sure its more than 2 chars
                    //this one is similar to fName and lName
            //if key = sentBy
                //this one is similar to id
            //if key contains YN make sure its binary.
            //This one is directly from above...
                // if( strpos($key,"YN") !== false) {
                //     //binary regex for sanitization
                //     $val = preg_replace("/[^0-1]+/","",$val);
                //     $val = intval($val);
                //     //if it is not binary add it to the error array
                //     if (preg_match('/[^0-1]+/', $val)) $error[] = $key;
                // }
            //if key contains dept it is a checkbox, make sure its a string of digits
                //this one is similar to phoneNumber
                //if( strpos( $key, "dept" ) !== false) {
                //ctype_digit($value)
                //}
            //if key = attachment
                //regex to make sure it is a correct attacment: ../../file.type
            //if key = sendNoti,deleteNoti,modifyNoti,removeNotiAttachment
                //these are just to tell what button was pressed. set it = ""
            //if key = contains Check it is another checkbox.
                //these are a string of a single digit
                //make sure it is a single number 1-9
        }
        return (count($error) == 0) ? ($postData) : (false);

    }




}
