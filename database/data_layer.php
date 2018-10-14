<?php
class data_layer{
    private $connection;

    /**
     * Data Layer Constructor, will automatically connect to DB.
     */
    function __construct(){
        $this->connection = new mysqli("127.0.0.1","root", "student", "rrcc_pearl_db");
        if ($this->connection->connect_error){
            echo "connection failed: ".mysqli_connect_error();
            die();
        }
    }

    /**
     * Checks username and password with DB.
     * @param  $phone comes from login page
     * @param  $password comes from login page
     * @return Bool Tells if username and password are correct
     */
    function checkLogin($phone,$password){
        if ($stmt = $this->connection->prepare("select password, authID from user where phone = ?")){
            $stmt->bind_param("s",$phone);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashedPassword,$authLevel);
            while ($stmt->fetch()) {
                if (password_verify($password,$hashedPassword)){
                    return ($authLevel > 1) ? (true) : (false);
                }
                else {
                    return false;
                }
            }
        }
    }

    function checkEmailExists($email){
        if ($stmt = $this->connection->prepare("select * from user where email = ?")){
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0){
                return true;
            }
        }
        return false;
    }

    //default values are when a user create their own account
    function createNewUser($postData, $tempPassYN = 0, $authID = 1, $activeYN = 1){
        $hashedPassword = password_hash($postData["password"],PASSWORD_DEFAULT);
        if ($stmt = $this->connection->prepare("INSERT INTO user (phone,fname,lname,tempPassYN,password,email,deptID,authID,activeYN) VALUES (?,?,?,$tempPassYN,?,?,?,$authID,$activeYN)")){
            $stmt->bind_param("sssssi",str_replace("-","", $postData["phoneNumber"]),$postData["fName"],$postData["lName"],$hashedPassword,$postData["email"],intval($postData["deptID"]));
            $stmt->execute();
            echo $stmt->affected_rows . " rows inserted";
        }
    }

    function getAllNotifcations(){
        if ($stmt = $this->connection->prepare("select * from notification")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($notificationID,$title,$body,$attachment,$active);
            $returnArray = array();
            while ($stmt->fetch()) {
                $currRowArray = array('notificationID' => $notificationID, 'title' => $title,
                'body'=> $body, 'attachment'=> $attachment, 'active'=>$active);
                array_push($returnArray,$currRowArray);
            }
            return $returnArray;
        }
    }

    function createNotification($postData){
        if ($stmt = $this->connection->prepare("INSERT INTO notification (title,body,attachment,activeYN) VALUES (?,?,?,1)")){
            $stmt->bind_param("sss", $postData['title'], $postData['body'], $postData['attachment']);
            $stmt->execute();
            //echo $stmt->affected_rows . " rows inserted";
        }
    }

    function updateNotification($notificationID, $postData){
        if ($stmt = $this->connection->prepare("UPDATE notification SET title = ?,body = ?, activeYN = ? WHERE notificationID = ?")){
            $stmt->bind_param("ssii",$postData['title'],$postData['body'],intval($postData['active']),intval($notificationID));
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0){
                return true;
            }
        }
        return false;
    }

    function deleteNotification($id){
        if ($stmt = $this->connection->prepare("DELETE FROM notification WHERE notificationID = ?")){
            $stmt->bind_param("i", $id);
            $stmt->execute();
            //echo $stmt->affected_rows . " rows deleted";
        }
    }


    function setUserTempPass($email, $newPass){
        //hash the new password first
        $hashedPassword = password_hash($newPass,PASSWORD_DEFAULT);
        //put it into the DB
        if ($stmt = $this->connection->prepare("UPDATE user SET tempPassYN = 1, password = ? WHERE email = ?")){
            $stmt->bind_param("ss",$hashedPassword,$email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0){
                return true;
            }
        }
        return false;
    }

    function updateUser($postData, $idField, $id){
        $query = "UPDATE user SET";
        foreach ($postData as $key => $value) {
            if ($value != null) $query .= " $key = ?, ";
        }
        //get rid of extra ,
        $query = substr($query,0,-2);
        $query .= " WHERE {$idField} = ?";
        echo $query;
        //var_dump($postData);
        //$postData;
    }

    function getAllUsers(){
        if ($stmt = $this->connection->prepare("select * from user")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($phone,$fName,$lName,$tempPassYN,$password, $email, $deptID, $authID, $userID, $activeYN);
            $returnArray = array();
            while ($stmt->fetch()) {
                $currRowArray = array('phone' => $phone, 'fName' => $fName, 'lName' => $fName,'tempPassYN' => $tempPassYN,
                'password'=> $password, 'email'=> $email, 'deptID'=>$deptID, 'authID' => $authID, 'userID' => $userID, 'activeYN' => $activeYN);
                array_push($returnArray,$currRowArray);
            }
            return $returnArray;
        }
    }

    function getData($table, $fields, $id){

    }

    function deleteData ($table, $id){

    }
}
