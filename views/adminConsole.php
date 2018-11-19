<?php
session_start();

if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}

if(isset($_SESSION['authID']) && $_SESSION['authID'] != 4){
    //logged in but dont have correct access send to news page
    header("Location: news.php");
}
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/adminConsole.css'>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script type='text/javascript' src='/js/scripts.js'></script>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='adminConsole' onload="setNavBar();initCsvListener();addMask();checkNotifcationModify();">
    <!-- Header -->
    <div class='header'>
        <h1 id='title' class='centered'>Administrator Console</h1>
    </div>

    <!-- Navigation -->
    <ul class='block' id='navigation'>
        <!-- 1. Add Total amount of entries to these 3 top li elements
             2. Onclick show/hide other sections
             3. Currently displayed section navigation title should be active
             4. Disable Compare on mobile, Enable on desktop
        -->
            <a href="#n"><li onclick="updateAdminView(this)" id="news_Button" class='inline active'>News</li></a>
            <a href="#e"><li onclick="updateAdminView(this)" id="employee_Button" class='inline'>Employees</li></a>
            <a href="#p"><li onclick="updateAdminView(this)" id="pending_Button" class='inline'>Pending</li></a>
            <a href="#c"><li onclick="updateAdminView(this)" id="compare_Button" class='inline'>Compare Employee Lists</li></a>
        <hr>
    </ul>

    <!-- News Section of Admin Console -->
    <section id='news'>

        <!-- Search Bar -->
        <div class='searchBar inputWithIcon'>
            <input type='text' placeholder='Search'>
            <i class="fas fa-search"></i>
        </div>

        <!-- Table that appends database entries of news articles into rows -->
        <table>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Active</th>
                <th>Action</th>
                <th></th>
            </tr>

            <!-- Begin PHP Iterative process to dynamically create News -->

            <?php
                if (isset($_GET['page'])) {
                    $pageNum = $_GET['page'];
                }
                else {
                    $pageNum = 1;
                }
                echo $bizLayer->createNewsTable(array_reverse($dataLayer->getAllNotifcations()),$pageNum);
                //echo $bizLayer->createNewsTable(array_reverse($dataLayer->getAllNotifcations()));
             ?>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Notification -->
            <tr id = "row-12" class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-plus-circle'></i></td>
                <td colspan='4'>Add New Notification</td>
            </tr>
            <tr class='spacer'><td></td></tr>
            <tr class='collapsed' style="display:none">
                <td colspan='5'>
                    <!-- Form that takes user input to add a new notification -->
                    <form class="addNewForm" action="adminAction.php" method="post" enctype="multipart/form-data">
                        <h2>Title</h2>
                        <input type="text" class='block inputNoIcon' name="title" required>
                        <h2>Body</h2>
                        <textarea class='block inputNoIcon' name="body"></textarea>
                        <!--<input type="text" class='block inputNoIcon' name="body" required>-->
                        <h2>Survey Link</h2>
                        <input type="text" class='block inputNoIcon' name="surveyLink">
                        <h2>Attachment</h2>
                        <div class='inputWithIcon addAttachment'>
                            <input class='block' type='file' name='attachment'/>
                            <i class="fas fa-file-upload" aria-hidden='true'></i>
                        </div>
                        <h2>Departments to Notify</h2>

                        <div class='checkBox deptChecks'>
                            <input name= 'dept_hr' id='cbHr' value="1" type='checkbox'>
                            <label for='cbHr' class='checkBoxContainer'>HR</label>
                            <input name='dept_admin' id='cbAdmin'value="2" type='checkbox'>
                            <label for='cbAdmin' class='checkBoxContainer'>Admin</label>
                            <input name ='dept_sales' id='cbSales' value="3" type='checkbox'>
                            <label for='cbSales' class='checkBoxContainer'>Sales</label>
                            <input name ='dept_production' id='cbProduction' value="4" type='checkbox'>
                            <label for='cbProduction' class='checkBoxContainer'>Production</label>
                            <input name ='dept_operations' id='cbOperations' value="5" type='checkbox'>
                            <label for='cbOperations' class='checkBoxContainer'>Operations</label>
                            <input name ='dept_food' id='cbFood' value="6" type='checkbox'>
                            <label for='cbFood' class='checkBoxContainer'>Food and Beverages</label>
                            <input name ='dept_garage' id='cbGarage' value="7" type='checkbox'>
                            <label for='cbGarage' class='checkBoxContainer'>Garage</label>
                        </div>

                        <h2>Notify Via</h2>
                        <div class='checkBox'>
                            <input name= 'phoneCheck' id='cbPhone' type='checkbox'>
                            <label for='cbPhone' class='checkBoxContainer'>Phone</label>
                            <input name='emailCheck' id='cbEmail' type='checkbox'>
                            <label for='cbEmail' class='checkBoxContainer'>Email</label>
                            <input name ='webAppCheck' id='cbWebApp' type='checkbox' checked>
                            <label for='cbWebApp' class='checkBoxContainer'>Web App</label>
                        </div>
                        <input type="submit" class='block addSubmit inputNoIcon' name="sendNoti" value="Send Notification">
                    </form>
                </td>
            </tr>

        </table>

        <!-- Pagination
        1. Counts Entries on screen (max 5, but could be less)
        2. Count Total Number of Entries
        3. Shows previous page of entries, disable when none to show
        4. Shows next page of entries, disable when none to show
        5. If 'Back' or 'Next' is clickable add 'clickable' class to it
        -->
        <div class='pagination block'>
            <?php
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }
            else {
                $pageNum = 1;
            }
            echo $bizLayer->makeNewsPaginationLinks($pageNum,$dataLayer->getAllNotifcations());
             ?>
        </div>
    </section>

    <!-- Employees section of Admin Console -->
    <section id='employees' class='hidden'>

        <!-- Search Bar -->
        <div class='searchBar inputWithIcon'>
            <input type='text' placeholder='Search'>
            <i class="fas fa-search"></i>
        </div>

        <!-- Table that appends database entries of employees into rows -->
        <table>
            <tr>
                <th></th>
                <th>First</th>
                <th>Last</th>
                <th>Action</th>
            </tr>

            <!-- Begin PHP Iterative process to dynamically create employees -->
            <?php
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }
            else {
                $pageNum = 1;
            }
                echo $bizLayer->createUserTable($dataLayer->getAllUsers(),$pageNum);
             ?>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Employee -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-plus-circle'></i></td>
                <td colspan='6'>Add New Employee</td>
            </tr>

            <tr class='spacer'><td></td></tr>
            <tr class='collapsed' style="display:none">
                <td colspan='6'>
                    <!-- Form that takes user input to add a new employee
                        * Make sure to automatically set a temporary password and send via phone # and email
                    -->
                    <form class="addNewForm" action="adminAction.php" method="post">

                        <!-- Input Fields -->
                        <h2>First Name</h2>
                        <div class='inputWithIcon'>
                            <input class='block' id='fName' type = 'text' placeholder= 'First Name' name='fName' required="required">
                            <i class='fas fa-address-card' aria-hidden='true'></i>
                        </div>
                        <h2>Last Name</h2>
                        <div class='inputWithIcon'>
                            <input class='block' id='fName' type = 'text' placeholder= 'Last Name' name='lName' required="required">
                            <i class='fas fa-address-card' aria-hidden='true'></i>
                        </div>
                        <!-- Add phone mask -->
                        <h2>Phone</h2>
                        <div class='inputWithIcon'>
                            <input class='block' id='phoneNumber' type = 'text' placeholder= 'Phone Number' name='phoneNumber' required="required">
                            <i class='fas fa-phone' aria-hidden='true'></i>
                        </div>
                        <h2>Email</h2>
                        <div class='inputWithIcon'>
                            <input class='block' id='email' type = 'text' placeholder= 'E-mail Address' name='email' required="required">
                            <i class='fas fa-user' aria-hidden='true'></i>
                        </div>
                        <h2>Active</h2>
                        <div class='inputWithIcon'>
                            <select class='block inputWithIcon' id='active' name='activeYN' required="required">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <i class='fas fa-flag' aria-hidden='true'></i>
                        </div>
                        <h2>Authorization</h2>
                        <div class='inputWithIcon'>
                            <select class='block inputWithIcon' id='auth' name='authID' required="required">
                                <option value="2">Employee</option>
                                <option value="3">Department Head</option>
                                <option value="4">Administrator</option>
                            </select>
                            <i class='fas fa-users' aria-hidden='true'></i>
                        </div>
                        <h2>Department</h2>
                        <div class='inputWithIcon'>
                            <select class='block inputWithIcon' id='dept' name='deptID' required="required">
                                <option value="" disabled selected>Department</option>
                                <option value="1">HR</option>
                                <option value="2">Admin</option>
                                <option value="3">Sales</option>
                                <option value="4">Production</option>
                                <option value="5">Operations</option>
                                <option value="6">Food and Beverage</option>
                                <option value="7">Garage</option>
                            </select>
                            <i class='fas fa-building' aria-hidden='true'></i>
                        </div>

                        <!-- Form submit -->
                        <input type="submit" class='block addSubmit inputNoIcon' name="addEmp" value="Add Employee">
                    </form>
                </td>
            </tr>

        </table>

        <!-- Pagination -->
        <div class='pagination block'>
            <?php
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }
            else {
                $pageNum = 1;
            }
                echo $bizLayer->createUserTablePaginationLinks($dataLayer->getAllUsers(),$pageNum);
             ?>
        </div>

    </section>

    <!-- Pending Users waiting for authorization -->
    <section id='pending' class='hidden'>

        <!-- Search Bar -->
        <div class='searchBar inputWithIcon'>
            <input type='text' placeholder='Search'>
            <i class="fas fa-search"></i>
        </div>

        <!-- Table that appends database entries of unauthorized employees into rows -->
        <table>
            <tr>
                <th></th>
                <th>First</th>
                <th>Last</th>
                <th>Action</th>
            </tr>

            <?php
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }
            else {
                $pageNum = 1;
            }
            //getData($table, $fields, $idField="" ,$id= 0)
            echo $bizLayer->createPendingUserTable($dataLayer->getData('user',array('*'),'authID',1),$pageNum);
            ?>



        </table>

        <!-- Pagination -->
        <div class='pagination block'>
            <?php
            echo $bizLayer->createPendingTablePaginationLinks($dataLayer->getData('user',array('*'),'authID',1),$pageNum);
             ?>
        </div>

    </section>

    <!-- Compare Employees to Payroll section -->
    <section id='compare' class='hidden'>
        <div class='centered'>
            <h2 class='title'>Upload CSV file to compare Active Employee List<br>with the Payroll Employee List</h2>
                <form class="" action="adminAction.php" method="post" enctype="multipart/form-data">
                    <div class='uploadContainer'>
                        <!--
                            this uses the javascript, but it doesnt pass any data when it submits.
                        <i class='fas fa-upload' id="csvFileUploadButton"></i><span class='fileName' accept='.csv'>No file selected</span>
                        <input type='file' id='fileUpload' class='hidden'>
                        -->
                        <div class='inputWithIcon addAttachment'>
                            <input class='block' type='file' name='attachment'/>
                            <i class="fas fa-file-upload" aria-hidden='true'></i>
                        </div>

                        <button id='csvUpload' type="submit" name="csvUpload" value="csvUpload">Submit</button>
                    </div>
                </form>

            <h2 class='title hidden' id='csvSuccess'>No discrepencies found</h2>

            <div id='csvFailedListContainer' class='hidden'>
                <h2 class='title' id='csvFailed'>Discrepencies found</h2>

                <table>
                <tr>
                    <th></th>
                    <th>First</th>
                    <th>Last</th>
                </tr>

                <form class="" action="" method="post">
                    <tr id = "row-96"class='collapsed'>
                        <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                        <td><input type="text" name="fName" disabled value="Mason"></td>
                        <td><input type="text" name="lName" disabled value="Santora"></td>
                    </tr>

                    <tr class='spacer'><td></td></tr>

                    <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

                    <tr id = "row-97" class='un-collapsed'>
                        <td colspan='2' class='leftUnCollapsed'>
                            <h2>Active</h2>
                            <span>Yes</span>

                            <h2>Department</h2>
                            <span>HR</span>

                            <h2>Email</h2>
                            <span>masonsantora@gmail.com</span>
                        </td>
                        <td colspan='1' class='rightUnCollapsed'>
                            <h2>Authorization</h2>
                            <span>Employee</span>

                            <h2>Phone Number</h2>
                            <span>555-555-5555</span>
                        </td>
                    </tr>
                </form>
                </table>

                <!-- Pagination -->
                <div class='pagination block'>
                    <div class='number inline'>
                        <span>1-5 of 13</span>
                    </div>

                    <div class='back inline'>
                        <i class='fas fa-chevron-left'></i><span>Back</span>
                    </div>

                    <div class='next inline'>
                        <span>Next</span><i class='fas fa-chevron-right'></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
            <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
            <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <li class='inline active'><a href="adminConsole.php"><i class="fas fa-toolbox"></i></a></li>
        </ul>
    </div>

</body>
</html>
