<?php
    $serverName="LAPTOP-LSFR3CIB\SQLEXPRESS01";
    $connectionOptions=[
        "Database"=>"webapp",
        "Uid"=>"",
        "PWD"=>""
    ];

    $conn=sqlsrv_connect($serverName, $connectionOptions);
    if($conn==false){
        die(print_r(sqlsrv_errors(),true));
    }
    $sql = "SELECT Title_ID FROM TITLE WHERE Title_ID=(SELECT IDENT_CURRENT('TITLE'))";
    $results=sqlsrv_query($conn, $sql);

    $titleid = sqlsrv_fetch_array($results);
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="icon" href="image/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <title>my.DLSU-D Successful</title>
    <style>
       body {
            font-family: 'Arial', sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            text-align: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url('image/login.jpg'); 
    background-size: cover;
    background-position: center; /* Center the background image */
    background-color: rgba(3, 45, 27, 0.53); /*greenish overlay */
    background-blend-mode: overlay; /* Blends image with the background color */
    color: #333; /*for contrast */
            padding: 15px;

     }

        .form-container {
            padding-top: 150px; 
            margin-left: auto;
            margin-right: auto; 
        }


        .section {
            text-align: center;
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

    .button {
            padding: 8px 16px;
            font-size: 14px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            color: black;
        }


        /* Form layout */
        .form-container {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between elements */
        }
        /* File input styling */
        .file-input {
            font-size: 14px;
        }
    </style>
</head>



<body>
<div class="form-container">
    <form>
            <div class="section">        
                <h1 style="font-size: 35px; color: black;">Registration Successful</h1>
        <h2 style="font-size: 30px; color: black;">Your Title ID is: <span style="color: #00bd65;"><?php echo $titleid['Title_ID']; ?></h2> </form>
<!-- New Form for File Upload -->
<form action="uploadsample.php" method="post" enctype="multipart/form-data">
            <h3>Thesis Chapter 1: (PDF Format)</h3>
            <input type="file" name="Chap1_pdf" id="Chap1_pdf">
            <br>
            <h3>Upload Thesis Manuscript: (PDF Format)</h3>
            <input type="file" name="Manu_pdf" id="Manu_pdf">
            <br>
            <button type="submit" class="upload-button" name="submit">Upload File</button>
        </form>
        <br>
        <!-- Edit Button Added -->
        <a href="viewedit.php" class="button">Edit Registration</a>
        <a href="dashboard3.php" class="button">Go to Dashboard</a>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</p>
    </div>
</body>

</html>


