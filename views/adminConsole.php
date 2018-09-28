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

<body id='adminConsole'>
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
        <li class='inline active'>News(13)</li>
        <li class='inline'>Employees(231)</li>
        <li class='inline'>Pending(3)</li>
        <li class='inline hidden'>Compare</li>
        <hr>
    </ul>

    <!-- News Section of Admin Console -->
    <section id='news' class='hidden'>

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

            <!-- Row that is collapsed -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>Heavy Rain to delay bla bla bla bla bla</td>
                <td>Yes</td>
                <td><i class='fas fa-pencil-alt'></i></td>
                <td><i class='fas fa-trash-alt'></i></td>
            </tr>

            <!-- Spacer puts padding in-between table rows -->

            <tr class='spacer'><td></td></tr>

            <tr id = "row-10"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>Heavy Rain to delay bla bla bla bla bla</td>
                <td>Yes</td>
                <td><i class='fas fa-pencil-alt'></i></td>
                <td><i class='fas fa-trash-alt'></i></td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

            <tr id = "row-11" class='un-collapsed'>
                <td colspan='3' class='leftUnCollapsed'>
                    <h2>Body</h2>
                    <span>Lorem ipsum dolor sit amet, consecteur adiposing elit. Sed autor ligula quis ante pretium lacreet.Nuno semper erat dignissim placerate feugiat.
                    Aenean commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan. commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan</span>
                </td>
                <td colspan='2' class='rightUnCollapsed'>
                    <h2>Attachment</h2>
                    <!-- Make this 'fas fa-file-upload' with blue color, if no file exists and text saying 'No attachment' Create functionality for upload -->
                    <i class="fas fa-times-circle"></i><span>document.pdf</span>

                    <h2>User Ack. Report</h2>
                    <i class="fas fa-download"></i><span>user_report.csv</span>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Notification -->
            <tr class='collapsed'>
                <td><i class='fas fa-plus-circle'></i></td>
                <td colspan='4'>Add New Notification</td>
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
    <section id='employees' >
    
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
                <th>Phone</th>
                <th>Action</th>
            </tr>

            <!-- Begin PHP Iterative process to dynamically create employees -->

            <!-- Row that is collapsed -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
                <td>Amanda</td>
                <td>Ho</td>
                <td>555-555-5555</td>
                <td><i class='fas fa-pencil-alt'></i></td>
                <td><i class='fas fa-trash-alt'></i></td>
            </tr>

            <!-- Spacer puts padding in-between table rows -->

            <tr class='spacer'><td></td></tr>

            <tr id = "row-12"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                <td>Mason</td>
                <td>Santora</td>
                 <td>555-555-5555</td>
                <td><i class='fas fa-pencil-alt'></i></td>
                <td><i class='fas fa-trash-alt'></i></td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

            <tr id = "row-13" class='un-collapsed'>
                <td colspan='3' class='leftUnCollapsed'>
                    <h2>Active</h2>
                    <span>Yes</span>

                    <h2>Department</h2>
                    <span>Finance</span>

                    <h2>Email</h2>
                    <span>masonsantora@gmail.com</span>
                </td>
                <td colspan='3' class='rightUnCollapsed'>
                    <h2>Authorization</h2>
                    <span>Employee</span>

                    <h2>Password</h2>
                    <span>Shetland</span>
                </td>
            </tr>

            <tr class='spacer'><td></td></tr>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Notification -->
            <tr class='collapsed'>
                <td><i class='fas fa-plus-circle'></i></td>
                <td colspan='6'>Add New Employee</td>
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
                <th>Phone</th>
                <th>Action</th>
            </tr>

            <!-- Begin PHP Iterative process to dynamically create employees -->

            <!-- Row that is collapsed -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                <td>Amanda</td>
                <td>Ho</td>
                <td>555-555-5555</td>
                <td><i class='fas fa-check-circle'></i></td>
                <td><i class='fas fa-minus-circle'></i></td>
            </tr>

            <!-- Spacer puts padding in-between table rows -->

            <tr class='spacer'><td></td></tr>

            <tr id = "row-12"class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td> 
                <td>Mason</td>
                <td>Santora</td>
                 <td>555-555-5555</td>
                <td><i class='fas fa-check-circle'></i></td>
                <td><i class='fas fa-minus-circle'></i></td>
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
