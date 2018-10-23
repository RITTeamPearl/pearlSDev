<?php
class data_layer{
    private $connection;

    /**
     * Data Layer Constructor, will automatically connect to DB.
     */
    function __construct(){
        $this->connection = new mysqli("127.0.0.1","root", "student", "rrcc_pearl_db");
        if ($this->connection->connect_error){
            //echo "connection failed: ".mysqli_connect_error();
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
            //echo $stmt->affected_rows . " rows inserted";
        }
    }

    function getAllNotifcations(){
        if ($stmt = $this->connection->prepare("select * from notification")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($notificationID,$title,$body,$attachment,$active,$webAppYN,$timestamp);
            $returnArray = array();
            while ($stmt->fetch()) {
                $currRowArray = array('notificationID' => $notificationID, 'title' => $title,
                'body'=> $body, 'attachment'=> $attachment, 'active'=>$active,'webAppYN'=>$webAppYN,'time'=>$timestamp);
                array_push($returnArray,$currRowArray);
            }
            return $returnArray;
        }
    }

    function createNotification($postData){
        if ($stmt = $this->connection->prepare("INSERT INTO notification (title,body,attachment,activeYN,webAppYN) VALUES (?,?,?,1,?)")){
            $stmt->bind_param("sssi", $postData['title'], $postData['body'], $postData['attachment'],intval($postData['webAppYN']));
            $stmt->execute();
            //echo $stmt->affected_rows . " rows inserted";
        }
    }

    function updateNotification($notificationID, $postData){
        if ($stmt = $this->connection->prepare("UPDATE notification SET title = ?,body = ?, activeYN = ? WHERE notificationID = ?")){
            $stmt->bind_param("ssii",$postData['title'],$postData['body'],intval($postData['activeYN']),intval($notificationID));
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0){
                return true;
            }
        }
        return false;
    }

    function removeNotiAttachment($notificationID){
        if ($stmt = $this->connection->prepare("UPDATE notification SET attachment = '' WHERE notificationID in (?)")){
            $stmt->bind_param("i", $notificationID);
            $stmt->execute();
            //echo $stmt->affected_rows . " rows deleted";
        }
    }

    function deleteNotification($id){
        if ($stmt = $this->connection->prepare("DELETE FROM notification WHERE notificationID = ?")){
            $stmt->bind_param("i", $id);
            $stmt->execute();
            //echo $stmt->affected_rows . " rows deleted";
        }
    }

    function deleteUser($id){
        if ($stmt = $this->connection->prepare("DELETE FROM user WHERE userID = ?")){
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

    //works like setUserTempPass, but closes the temp flag.
    function setUserPassword($email, $newPass) {
        $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
        if ($stmt = $this->connection->prepare("UPDATE user SET tempPassYN = 0, password = ? WHERE email = ?")){
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
        $bindParamTypes = '';
        $bindParamArray = array();
        foreach ($postData as $key => $value) {
            if ($value != null){
                //add it to the query string
                $query .= " $key = ?, ";
                //add value to binding array for prepared statement
                if (is_numeric($value) && $key != "phone"){
                    $bindParamTypes .= "i";
                    array_push($bindParamArray,intval($value));
                }
                else {
                    $bindParamTypes .= "s";
                    array_push($bindParamArray,$value);
                }
            }
            //if it is a password then hash it
            if ($key === "password") {
                $postData[$key] = password_hash($value,PASSWORD_DEFAULT);
            }
        }
        //add letter for id based on string or int
        $bindParamTypes .= (is_numeric($id)) ? ("i"):("s");
        //add value to binding array for prepared statement
        array_push($bindParamArray,(is_numeric($id)) ? (intval($id)):($id));
        //get rid of extra space and ,
        $query = substr($query,0,-2);
        //Add where field
        $query .= " WHERE {$idField} = ?";

        //Execute the query
        if ($stmt = $this->connection->prepare($query)){
            //bind params based on vars in $postData
            $stmt->bind_param($bindParamTypes,...$bindParamArray);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->affected_rows > 0){
                //echo "it worked";
                return true;
            }
        }
    }

    function getAllUsers(){
        if ($stmt = $this->connection->prepare("select * from user")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($phone,$fName,$lName,$tempPassYN,$password, $email, $deptID, $authID, $userID, $activeYN);
            $returnArray = array();
            while ($stmt->fetch()) {
                $currRowArray = array('phone' => $phone, 'fName' => $fName, 'lName' => $lName,'tempPassYN' => $tempPassYN,
                'password'=> $password, 'email'=> $email, 'deptID'=>$deptID, 'authID' => $authID, 'userID' => $userID, 'activeYN' => $activeYN);
                array_push($returnArray,$currRowArray);
            }
            return $returnArray;
        }
    }

    function getData($table, $fields, $idField="" ,$id= 0){
        //inital select query
        $query = "Select ";
        //each passed in field
        foreach ($fields as $fieldName) {
            $query .= "{$fieldName}, ";
        }
        //remove extra space and ,
        $query = substr($query,0,-2);
        //add table
        $query .= " FROM {$table} ";
        //If there is a where then add it
        if($idField != "" && $id){
            $query .= "WHERE {$idField} = ?";
        }

        if ($stmt = $this->connection->prepare($query)){
            //bind $idField if it exists
            if($idField != "" && $id){
                if(is_numeric($id)){
                    $stmt->bind_param("i", intval($id));
                }
                else{
                    $stmt->bind_param("s", strval($id));
                }
            }
            $stmt->execute();
            $metaData = $stmt->result_metadata();
            $bindFieldArray = array();
            //Array of variables named using fields.
            while ($field = $metaData->fetch_field()) {
                $var = $field->name;
                $$var = null;
                $bindFieldArray[$var] = &$$var;
            }
            //bind query results to fieldArray
            call_user_func_array(array($stmt,'bind_result'),$bindFieldArray);
            //initalize return array (this will be 2d with each row as an internal array)
            $returnArray = array();
            //loop through each row returned, make an array for that row, push to 2d return array
            while ($stmt->fetch()) {
                foreach($bindFieldArray as $k => $v){
                    $currRowArray[$k] = $v;
                }
                array_push($returnArray,$currRowArray);
            }
        }
        return $returnArray;
    }

    function deleteData ($table, $idField, $id){
        $query = "DELETE FROM {$table} WHERE {$idField} = ?";
        if ($stmt = $this->connection->prepare($query)){
            //look at type of id and bind with correct type
            if(is_numeric($id)){
                $stmt->bind_param("i", intval($id));
            }
            else{
                $stmt->bind_param("s", strval($id));
            }
            $stmt->execute();
        }
    }
}
