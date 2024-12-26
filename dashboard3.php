<?php
// Database connection
$serverName = "LAPTOP-LSFR3CIB\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "webapp",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn == false) {
    die(print_r(sqlsrv_errors(), true));
}

$searchKeyword = ''; 
$searchResults = []; // To hold search results

// Search logic if the form is submitted
if (isset($_POST['search'])) {
    $searchKeyword = $_POST['title_id']; // Get the submitted search keyword
    $sql = "
        WITH RankedResults AS (
            SELECT 
                t.TITLE_ID, 
                t.TITLE_NAME, 
                t.PROGRAM, 
                a.LAST_NAME AS AUTHOR_LAST_NAME, 
                a.FIRST_NAME AS AUTHOR_FIRST_NAME,
                adv.LAST_NAME AS ADVISER_LAST_NAME, 
                adv.FIRST_NAME AS ADVISER_FIRST_NAME,
                ROW_NUMBER() OVER (PARTITION BY t.TITLE_ID ORDER BY t.TITLE_ID) AS RowNum
            FROM TITLE t 
            JOIN AUTHOR a ON t.TITLE_ID = a.TITLE_ID 
            LEFT JOIN ADVISER adv ON t.TITLE_ID = adv.TITLE_ID
            WHERE t.TITLE_ID LIKE ? 
               OR t.TITLE_NAME LIKE ? 
               OR t.PROGRAM LIKE ? 
               OR a.LAST_NAME LIKE ? 
               OR a.FIRST_NAME LIKE ? 
               OR adv.LAST_NAME LIKE ? 
               OR adv.FIRST_NAME LIKE ?
        )
        SELECT * FROM RankedResults WHERE RowNum = 1"; // Select only the first record per TITLE_ID
    
    $searchParam = '%' . $searchKeyword . '%'; // Add wildcards for partial match
    $params = array_fill(0, 7, $searchParam); // Prepare parameters for each LIKE condition
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch the data
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $searchResults[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="image/favicon.png" type="image/png">
    <title>my.DLSU-D Thesis</title>
    <style>


    /* General Body Styles */
    body {
        font-family: 'Roboto', sans-serif;
    font-weight: normal;
    background-image: url('image/login.jpg'); 
    background-size: cover;
    background-position: center; /* Center the background image */
    background-color: rgba(3, 45, 27, 0.53); /*greenish overlay */
    background-blend-mode: overlay; /* Blends image with the background color */
    color: #333; /*for contrast */

        margin: 0;
        padding: 0;
        text-align: center;
    background-repeat: no-repeat; /* Prevents the image from repeating */

    height: 100vh; /* Ensures the body takes up the full viewport height */

    }

 /* Navbar Styles */
 nav {
        background-color: white; /* De La Salle green */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1px 20px;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    nav ul li {
        margin-right: 20px;
    }

    nav ul li a {
        display: block;
        color: #888;
        padding: 14px 20px;
        text-align: center;
        text-decoration: none;
    }

    nav ul li a:hover {
    color: #4aaf51; /* Change text color to gray on hover */
    background-color: transparent; /* Ensure the background remains transparent */
}


/* Style for the link */
.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
}

/* Style for the text inside the link */
.logo-text {
    font-family: 'Robotolightnew', sans-serif;
    line-height: 1.2;
    text-align: left;
    font-size: 20px;
    color: black;
}

/* Change text color when hovering over the link */
.logo-link:hover .logo-text {
    color: #4aaf51; /* Change text color to gray on hover */
}




    /* Search Bar Styles */
    .search-bar {
    display: flex;
    align-items: center;
}


    .search-bar input[type="text"] {
        padding: 10px;
        font-size: 1em;
        border: 2px solid #ddd;
        border-radius: 5px;
        width: 225px;
    }

    .search-bar input[type="submit"] {
        background-color: #093A1D;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 40px;
        cursor: pointer;
        font-size: 15px;
        margin-left: 10px;
    }

    .search-bar input[type="submit"]:hover {
        background-color: #003a2a;
    }

     /* Container Style */
     .container {
        padding: 20px;
        max-width: 1200px;
        margin: 30px auto;
    }

    /* Section Header Styles */
    h1 {
        color: #2f7a2f; /*for headings */
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    font-size: 40px; /*heading font size */
    }

    h2 {
        color: #2f7a2f; /*for headings */
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    font-size: 30px; /*heading font size */
    }

    /* Table Styles */
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        background-color: #ffffff;
    }

    table, th, td {
        border: 2px solid #ddd;
        
    }

    th, td {
        padding: 18px;
        text-align: left;
        font-size: 1.1em;
        
        
    }

    th {
        background-color: #CECFCF;
        color: black;
        font-family: 'Roboto', sans-serif;
         font-weight: normal;
         
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;

    }

    tr:hover {
        background-color: #f1f1f1;
        
    }

    td {
        font-family: 'Roboto', sans-serif;
        font-weight: normal;
        font-size: 1em;
        color: black;
    }

    /* Footer Style */
    .footer {
        background-color: transparent; /* De La Salle green */
        color: white;
        padding: 20px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
    }

    
    .footer p {
        margin: 0;
    }
    .view-chapter-btn {
    background-color: #4aaf51; /* De La Salle green */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

.view-chapter-btn:hover {
    background-color: #003a2a; /* Darker green on hover */
}

</style>

</head>
<body>

    <!-- Navigation Bar -->
    <nav>
    <ul style="list-style-type: none; padding: 0;">
    <!-- Logo with clickable link to another webpage -->
    <li style="display: flex; align-items: center; margin-top: 10px;">
    <a href="dashboard3.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
        DLSU-D<br>CEAT Thesis
    </span>
</a>
</a>
    </li>
    <!-- Other links -->
    <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
        <a href="RegistrationForm.php" style="text-decoration: none;">Research Registration Form</a>
    </li>
    <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
        <a href="LoginPage.php" style="text-decoration: none;">Admin Login</a>
    </li>
        </ul>

        <div class="search-bar">
            <form method="POST">
                <input type="text" name="title_id" placeholder="Search Title or Keyword" value="<?php echo htmlspecialchars($searchKeyword); ?>" required>
                <input type="submit" name="search" value="Search">
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1 style=" color: white;">Welcome! My.DLSU-D <br>
        <span style="font-size: 35px;">CEAT Research Registration System</span></h1>

        <?php if (!empty($searchResults)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Chapter 1</th>
                        <th>Title ID</th>
                        <th>Title Name</th>
                        <th>Program</th>
                        <th>Author Last Name</th>
                        <th>Author First Name</th>
                        <th>Adviser Last Name</th>
                        <th>Adviser First Name</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($searchResults as $row): ?>
        <tr>
        <td>
    <a href="viewfile.php?title_id=<?php echo urlencode($row['TITLE_ID']); ?>">
        <button class="view-chapter-btn">View Chapter 1</button>
    </a>
</td>

            <td><?php echo htmlspecialchars($row['TITLE_ID']); ?></td>
            <td><?php echo $row['TITLE_NAME']; ?></td>
            <td><?php echo $row['PROGRAM']; ?></td>
            <td><?php echo $row['AUTHOR_LAST_NAME']; ?></td>
            <td><?php echo $row['AUTHOR_FIRST_NAME']; ?></td>
            <td><?php echo $row['ADVISER_LAST_NAME']; ?></td>
            <td><?php echo $row['ADVISER_FIRST_NAME']; ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        <?php else: ?>
            <p style= "    color: red"></p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</p>
    </div>

</body>
</html>