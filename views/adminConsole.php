<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/login.css'>
    <link href='assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='adminPageNews'>
    <!-- Header -->
    <h1 id='title' class='centered'>Administrator Console</h1>
    
    <ul class='block'>
        <!-- Add Total amount of entries to these li elements -->
        <li class='inline'>News()</li>
        <li class='inline'>Employees()</li>
        <li class='inline'>Compare Employee Lists</li>
    </ul>
    
    <div class='searchBar'>
        <i></i>
        <input type='text' placeholder='Search'>
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
        <tr>
            <td><i></i><i display='none'></i></td> <!-- Onclick this icon needs to be updated -->
            <td>Heavy Rain to delay bla bla bla bla bla</td>
            <td>Yes</td>
            <td><i></i>Modify</td>
            <td><i></i>Delete</td>
        </tr>
        
    </table>
    
    <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->

    <tr>
        <h2>Body</h2>
        <p>Lorem ipsum dolor sit amet, consecteur adiposing elit. Sed autor ligula quis ante pretium lacreet.Nuno semper erat dignissim placerate feugiat.
        Aenean commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan.</p>

        <h2>Attachment</h2>
        <p>document.pdf</p><i></i>

        <h2>User Acknowledgements Report</h2>
        <p>user_report.csv</p><i></i>

    </tr>
    
    <!-- Begin next dynamically added rows here -->

    <!-- Add New Notification -->
    <tr>
        <td><i></i></td>
        <td colspan='4'>Add New Notification</td>
    </tr>

    <!-- Pagination
    1. Counts Entries on screen (max 5, but could be less)
    2. Count Total Number of Entries
    3. Shows previous page of entries, disable when none to show
    4. Shows next page of entries, disable when none to show
    -->
    <p>1-5 of 13</p>
    <i><p>Back</p></i>
    <i><p>Next</p></i>
        
</body>
</html>