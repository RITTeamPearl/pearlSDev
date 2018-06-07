<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <h2>Create Account</h2>

    <form action= "createAcct.php" method="POST">
        <br><input type = "email" value= "Email" name="email" required="required"/></br>
        <br><input type = "password" value= "Password" name="password" required="required"/></br>
        <br><input type = "text" value= "First Name" name="fname" required="required"/></br>

        <br><input type = "text" value= "Last Name" name="lname" required="required"/></br>
        <br><input type = "number" value= "Phone Number" name="phone" required="required"/></br>
       
        <br><select name="carriers">
            <option value="carrier">Select a Carrier</option>
            <option value="verizon">Verizon</option>
            <option value="tmobile">T-Mobile</option>
            <option value="sprint">Sprint</option>
            <option value="cricket">Cricket</option>
            <option value="att">AT&T</option>
        </select></br>

         <br><select name="departments">
            <option value="departments">Select a Department</option>
            <option value="hr">HR</option>
            <option value="sales">Sales</option>
            <option value="ops">Operations</option>
            <option value="garage">Garage</option>
            <option value="fb">Food & Beverages</option>
            <option value="prod">Production</option>
            <option value="admin">Administration</option>
        </select></br>

        <br><input type = "submit" value= "Request Creation"/></br>
    </form>
</body>
</html>