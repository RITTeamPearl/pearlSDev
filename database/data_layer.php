<?php
class data_layer{
    private $connection;

    /**
     * Data Layer Constructor, will automatically connect to DB.
     */
    function __construct(){
        $this->connection = new mysqli("localhost","root", "student", "rrcc_pearl_db");
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
        if ($stmt = $this->connection->prepare("select password from user where phone = ?")){
            $stmt->bind_param("s",$phone);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashedPassword);
            while ($stmt->fetch()) {
                if (password_verify($password,$hashedPassword)){
                    return true;
                }
                else {
                    return false;
                }
            }
        }
    }

    function createNewUser($postData){
        $hashedPassword = password_hash($postData["password"],PASSWORD_DEFAULT);
        if ($stmt = $this->connection->prepare("INSERT INTO user (phone,fname,lname,tempPassYN,password,email,deptID,authID) VALUES (?,?,?,0,?,?,?,1)")){
            $stmt->bind_param("sssssi",str_replace("-","", $postData["phoneNumber"]),$postData["fName"],$postData["lName"],$hashedPassword,$postData["email"],intval($postData["dept"]));
            $stmt->execute();
            echo $stmt->affected_rows . " rows inserted";
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



}
