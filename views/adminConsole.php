<?php
session_start();
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
    <script type='text/javascript' src='/js/scripts.js'></script>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='adminConsole' onload="resizeTextArea(bodyContent)">
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
        <li onclick="updateAdminView(this)" id="news_Button" class='inline active'>News(13)</li>
        <li onclick="updateAdminView(this)" id="employee_Button" class='inline'>Employees(231)</li>
        <li onclick="updateAdminView(this)" id="pending_Button" class='inline'>Pending(3)</li>
        <li onclick="updateAdminView(this)" id="compare_Button" class='inline hidden '>Compare Employee Lists</li>
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
                echo $bizLayer->createNewsTable($dataLayer->getAllNotifcations())
             ?>


        <form class="" action="adminAction.php?id=30" method="post">
            <tr id = "row-10"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>
                    <input type="text" name="title" disabled value="Heavy Rain to delay bla bla bla bla bla">
                </td>
                <td>
                    <select disabled name='activeYN' class='disabledDrop'>
                        <option value='1'>Yes</option>
                        <option value='2'>No</option>
                    </select>
                </td>
                <td>
                    <i id='notiEditButton' onclick="dropDownModify(this,'noti');" class='fas fa-pencil-alt'></i>

                    <!--*Make this appear when edit is clicked, hide pencil...vice versa*-->
                    <button class="hidden" id='notiSaveEditButton' type= "submit" name="modify"><i class="fas fa-save" onclick=''></i></button>
                </td>
                <td>
                    <button type="submit" name= "delete" value="delete"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->
            <!-- JQUERY Animate function does not work on TR so eventually we might want to convert this to a ul? -->
            <tr id = "row-11" class='un-collapsed'>
                <td colspan='5' class='full'>
                    <h2>Body</h2>
                    <textarea id='bodyContent' name="body" disabled>Lorem ipsum dolor sit amet, consecteur adiposing elit. Sed autor ligula quis ante pretium lacreet.Nuno semper erat dignissim placerate feugiat.

                    Aenean commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan. commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan</textarea>

                    <h2>Attachment</h2>
                    <!-- Make this 'fas fa-file-upload' with blue color, if no file exists and text saying 'No attachment' Create functionality for upload -->
                    <i class="fas fa-times-circle"></i><span>document.pdf</span>

                    <h2>User Ack. Report</h2>
                    <i class="fas fa-download"></i><span>user_report.csv</span>
                </td>
            </tr>
            <tr class='spacer'><td></td></tr>
        </form>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Notification -->
            <tr id = "row-12" class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-plus-circle'></i></td>
                <td colspan='4'>Add New Notification</td>
            </tr>
            <tr class='spacer'><td></td></tr>
            <tr id = "row-13" class='un-collapsed'>
                <td colspan='5'>
                    <!-- Form that takes user input to add a new notification -->
                    <form class="addNewForm" action="adminAction.php" method="post" enctype="multipart/form-data">
                        <h2>Title</h2>
                        <input type="text" class='block inputNoIcon' name="title" required>
                        <h2>Body</h2>
                        <input type="text" class='block inputNoIcon' name="body" required>
                        <h2>Attachment</h2>
                        <div class='inputWithIcon addAttachment'>
                            <input class='block' type='file' name='attachment'/>
                            <i class="fas fa-file-upload" aria-hidden='true'></i>
                        </div>
                        <h2>Notify</h2>
                        <div class='checkBox'>
                            <input id='cbPhone' type='checkbox'>
                            <label for='cbPhone' class='checkBoxContainer'>Phone</label>
                            <input id='cbEmail' type='checkbox'>
                            <label for='cbEmail' class='checkBoxContainer'>Email</label>
                            <input id='cbWebApp' type='checkbox'>
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
                echo $bizLayer->createUserTable($dataLayer->getAllUsers());
             ?>

        <form class="" action="adminAction.php?id=31" method="post">
            <tr id = "row-96"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                <td><input type="text" name="fName" disabled value="Mason"></td>
                <td><input type="text" name="lName" disabled value="Santora"></td>
                <td>
                    <i id='empEditButton' onclick="dropDownModify(this,'emp');" class='fas fa-pencil-alt'></i>
                    <button class="hidden" id='empSaveEditButton' type= "submit" name="modifyEmp" value="modifyEmp"><i class="fas fa-save" onclick=''></i></button>
                </td>
                <td>
                    <button type="submit" name= "deleteEmp" value="deleteEmp"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

            <tr id = "row-97" class='un-collapsed'>
                <td colspan='3' class='leftUnCollapsed'>
                    <h2>Active</h2>
                    <select disabled name='activeYN' class='disabledDrop'>
                        <option value=1>Yes</option>
                        <option value=2>No</option>
                    </select>

                    <h2>Department</h2>
                    <select disabled name='department' class='disabledDrop'>
                        <option value=1>HR</option>
                        <option value=2>Admin</option>
                        <option value=3>Sales</option>
                        <option value=4>Production</option>
                        <option value=5>Operations</option>
                        <option value=6>Food and Beverage</option>
                        <option value=7>Garage</option>
                    </select>

                    <h2>Email</h2>
                    <input type="text" name="email" class='email' disabled value="masonsantora@gmail.com">
                </td>
                <td colspan='2' class='rightUnCollapsed'>
                    <h2>Authorization</h2>
                    <select disabled name='authorization' class='disabledDrop fullWidth'>
                        <option value=1>Employee</option>
                        <option value=2>Depart. Head</option>
                        <option value=3>Administrator</option>
                    </select>

                    <h2>Phone Number</h2>
                    <input type="text" name="phone" disabled value="555-555-5555">
                </td>
            </tr>
        </form>

            <tr class='spacer'><td></td></tr>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Employee -->
            <tr class='collapsed'>
                <td><i class='fas fa-plus-circle'></i></td>
                <td colspan='6'>Add New Employee</td>
            </tr>

            <tr class='spacer'><td></td></tr>
            <tr id = "row-14" class='un-collapsed'>
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

            <!-- Begin PHP Iterative process to dynamically create employees -->
            <form class="" action="adminAction.php?id=1" method="post">

            <!-- Row that is collapsed -->
                <tr class='collapsed'>
                    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                    <td>Amanda</td>
                    <td>Ho</td>
                    <td>
                        <button type="submit" name= "confirmPendEmp" value="confirmPendEmp"><i class="fas fa-check-circle"></i></button>
                    </td>
                    <td>
                        <button type="submit" name= "denyPendEmp" value="denyPendEmp"><i class="fas fa-minus-circle"></i></button>
                    </td>
                </tr>
                <tr class='spacer'><td></td></tr>
                <tr class='un-collapsed'>
                    <td colspan="5">
                        <h2>Authorization Level</h2>
                        <select name='department' id='authLevel'>
                            <option value=1>Employee</option>
                            <option value=2>Department Head</option>
                            <option value=3>Admin</option>
                        </select>
                    </td>
                </tr>
                <tr class='spacer'><td></td></tr>
            </form>

            <!-- Spacer puts padding in-between table rows -->


            <tr id = "row-12"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                <td>Mason</td>
                <td>Santora</td>
                <td>
                    <button type="submit" name= "confirmPendEmp" value="confirmPendEmp"><i class="fas fa-check-circle"></i></button>
                </td>
                <td>
                    <button type="submit" name= "denyPendEmp" value="denyPendEmp"><i class="fas fa-minus-circle"></i></button>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

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

    </section>

    <!-- Compare Employees to Payroll section -->
    <section id='compare'>
        <div class='centered'>
            <h2 class='title'>Upload CSV file to compare Active Employee List<br>with the Payroll Employee List</h2>

            <div class='uploadContainer'>
                <i class='fas fa-upload' onclick="fileUploadClick(this)"></i><span class='fileName'>No file selected</span>
                <input type='file' id='fileUpload' class='hidden'>
            </div>


        </div>


    </section>

    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><i class="fas fa-newspaper"></i></li>
            <li class='inline'><i class="fas fa-video"></i></li>
            <li class='inline'><i class="fas fa-user"></i></li>
            <li class='inline'><i class="fas fa-bell"></i></li>
            <li class='inline'><i class="fas fa-toolbox"></i></li>
        </ul>

    </div>
</body>
</html>
