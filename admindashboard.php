<?php
$serverName="LAPTOP-LSFR3CIB\SQLEXPRESS01";
$connectionOptions=[
    "Database"=>"webapp",
    "Uid"=>"",
    "PWD"=>""
    ];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn == false) {
    die(print_r(sqlsrv_errors(), true));
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <title>my.DLSU-D Admin Management</title>
    <style>
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




.wrapper .icon-close {
    position: auto;
    width: 80px;
    height: 50px;
    background: #4aaf51;
    font-size: 1em;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 20px;
    cursor: pointer;
    z-index: 1;
    transition: background-color 0.3s ease; /* Smooth transition effect */
}

.wrapper .icon-close:hover {
    background: #18453b; /* Dark De La Salle green color */
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
    </style>
</head>
<body>
<nav>

    <ul style="list-style-type: none; padding: 0;">
    <!-- Logo with clickable link to another webpage -->
    <li style="display: flex; align-items: center; margin-top: 10px;">
    <a href="admindashboard.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
        DLSU-D<br>CEAT Thesis
    </span>
</a>
</a>
    </li>
    <!-- Other links -->
    <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
        <a href="search1.php" style="text-decoration: none;">Research Reports</a>
    </li>
    <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
        <a href="reportsearch.php" style="text-decoration: none;">Research Downloads</a>
    </li>
        </ul>

        <div class="wrapper">
        <span class="icon-close" onclick="window.location.href='dashboard3.php';">
            Logout
        <ion-icon name="log-out-outline"></ion-icon>
</span>
        
   
</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
    <h1 style=" color: white;">Welcome! My.DLSU-D <br>
    <span style="font-size: 35px;">CEAT Research Registration System</span></h1>

        

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</p>
    </div>

</body>
</html>