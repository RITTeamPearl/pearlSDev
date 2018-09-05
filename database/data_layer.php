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
        //escape vars before checking. This should do more validation.
        $phone = mysqli_real_escape_string($this->connection, $phone);
        $password = mysqli_real_escape_string($this->connection, $password);
        //create prepared statement for SELECT
        if ($stmt = $this->connection->prepare("select phone, password from user where phone = ? and password = ?")){
            $stmt->bind_param("ss",$phone,$password);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($username,$password);
            //found a row with that UN and password
            if ($stmt->num_rows > 0){
                return true;
            }
            //Did not find
            else {
                return false;
            }
        }
    }

    function createNewUser($postData){
        var_dump($postData);
        //TODO: get input -> validate / sanitize -> deal with temporary / deptID conversions
        //create prepared statement for INSERT
        //hard code active as true and authorization as waiting
        // if ($stmt = $this->connection->prepare("INSERT INTO user (phone,fname,lname,tempPassYN,password,email,deptID,authID) VALUES (?,?,?,1,?,?,?,1)")){
        //     $stmt->bind_param("sssssi",$phone,$fname,$lname,$password,$email,$deptID);
        //     $stmt->execute();
        // }
    }

    function selectUsers(){
        if ($stmt = $this->connection->prepare("select phone from user")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($phoneNum);
            if ($stmt->num_rows > 0){
                while($stmt->fetch()){
                    //loop through all results. use vars from bind
                    echo $phoneNum;
                }
            }
        }
    }



}
