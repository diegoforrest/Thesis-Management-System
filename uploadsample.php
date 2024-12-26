<?php

// Database connection
    $serverName="LAPTOP-LSFR3CIB\SQLEXPRESS01";
    $connectionOptions=[
        "Database"=>"webapp",
        "Uid"=>"",
        "PWD"=>""
    ];
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Latest Title ID
$get_last_id_sql = "SELECT TOP 1 TITLE_ID FROM TITLE ORDER BY TITLE_ID DESC";
$last_id_result = sqlsrv_query($conn, $get_last_id_sql);

if ($last_id_result === false) {
    die(print_r(sqlsrv_errors(), true));
}

$last_id_row = sqlsrv_fetch_array($last_id_result, SQLSRV_FETCH_ASSOC);
$last_id = $last_id_row ? $last_id_row['TITLE_ID'] : null;

// Declare the file to be uploaded
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type_chap1 = $finfo->file($_FILES["Chap1_pdf"]["tmp_name"]);
$mime_type_manu = $finfo->file($_FILES["Manu_pdf"]["tmp_name"]);

// Declare the accepted file type (PDF)
$mime_types = ["application/pdf"];

// Validate the file
if (!in_array($mime_type_chap1, $mime_types) && !in_array($mime_type_manu, $mime_types)) {
    $upload_status = "File type is not supported. Only PDF files are allowed.";
    exit($upload_status);
}

// If no error, upload the file to the destination folder
$filename_chap1 = $_FILES["Chap1_pdf"]["name"];
$filename_manu = $_FILES["Manu_pdf"]["name"];

$filesize_chap1 = $_FILES["Chap1_pdf"]["size"];
$filesize_manu = $_FILES["Manu_pdf"]["size"];

$destination_chap1 = __DIR__ . "/uploads/" . $filename_chap1;
$destination_manu = __DIR__. "/uploads_manu/" . $filename_manu;

// Move the file to the destination folder
if (!move_uploaded_file($_FILES["Chap1_pdf"]["tmp_name"], $destination_chap1) || !move_uploaded_file($_FILES["Manu_pdf"]["tmp_name"], $destination_manu)) {
    $upload_status = "Failed to upload the file.";
    exit($upload_status);
}

// Check for errors for Chap1_pdf and Manu_pdf
if ($_FILES["Chap1_pdf"]["error"] == 0 && $_FILES["Manu_pdf"]["error"] == 0) {
    $upload_status = 'Upload Success';

    // Record the file path for Chapter 1
    $sql_chap1 = "INSERT INTO FILE_UPLOAD (FILE_NAME, FILE_SIZE, FILE_PATH, TITLE_ID) VALUES (?, ?, ?, ?)";
    $params_chap1 = [$filename_chap1, $filesize_chap1, $destination_chap1, $last_id];
    $results_chap1 = sqlsrv_query($conn, $sql_chap1, $params_chap1);

    // Record the file path for Manuscript
    $sql_manu = "INSERT INTO MANU (FILE_NAME, FILE_SIZE, FILE_PATH, TITLE_ID) VALUES (?, ?, ?, ?)";
    $params_manu = [$filename_manu, $filesize_manu, $destination_manu, $last_id];
    $results_manu = sqlsrv_query($conn, $sql_manu, $params_manu);

    if ($results_chap1 && $results_manu) {
        $upload_status .= ' - Upload to DB is successful';
    } else {
        $upload_status = 'Error: ' . print_r(sqlsrv_errors(), true);
    }
} else {
    $upload_status = 'An error occurred during the upload.';
}

// Get the most recent file upload data for Chapter 1
$get_file_data_sql_chap1 = "SELECT * FROM FILE_UPLOAD WHERE Title_ID = ? AND FILE_NAME = ?";
$file_data_result_chap1 = sqlsrv_query($conn, $get_file_data_sql_chap1, array($last_id, $filename_chap1));

if ($file_data_result_chap1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

$file_data_row_chap1 = sqlsrv_fetch_array($file_data_result_chap1, SQLSRV_FETCH_ASSOC);

// Get the most recent file upload data for Manuscript
$get_file_data_sql_manu = "SELECT * FROM MANU WHERE Title_ID = ? AND FILE_NAME = ?";
$file_data_result_manu = sqlsrv_query($conn, $get_file_data_sql_manu, array($last_id, $filename_manu));

if ($file_data_result_manu === false) {
    die(print_r(sqlsrv_errors(), true));
}

$file_data_row_manu = sqlsrv_fetch_array($file_data_result_manu, SQLSRV_FETCH_ASSOC);

// Store the values in variables for Chapter 1
$file_id_chap1 = $file_data_row_chap1['FILE_ID'];
$file_name_chap1 = $file_data_row_chap1['FILE_NAME'];
$file_size_chap1 = $file_data_row_chap1['FILE_SIZE'];
$file_path_chap1 = $file_data_row_chap1['FILE_PATH'];
$title_id_chap1 = $file_data_row_chap1['TITLE_ID'];

// Store the values in variables for Manuscript
$file_id_manu = $file_data_row_manu['FILE_ID'];
$file_name_manu = $file_data_row_manu['FILE_NAME'];
$file_size_manu = $file_data_row_manu['FILE_SIZE'];
$file_path_manu = $file_data_row_manu['FILE_PATH'];
$title_id_manu = $file_data_row_manu['TITLE_ID'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="image/favicon.png" type="image/png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <title>my.DLSU-D Upload Success</title>
</head>
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
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-repeat: no-repeat; /* Prevents the image from repeating */
}



.container {
    width: 100%;
    max-width: 800px;
    padding: 20px;
    background: transparent;
  border: 2px solid rgba(255, 255, 255, .2);
  backdrop-filter: blur(3px);
  box-shadow: 0 0 30px rgba(0, 0, 0, .5);
  color: #fff;
    
    text-align: left;
    border-radius: 20px;
  padding: 30px 40px;
}

h1, h2 {
    color: white; /* DLSU Green for headings */
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    margin-bottom: 20px;
}

h1 {
    font-size: 36px;
}

h2 {
    font-size: 28px;
    margin-top: 30px;
}

p {
    margin: 10px 0;
    line-height: 1.5;
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    color: #6cf57e; /* Dark text for readability */
}

strong {
    color: white; /* Highlight key labels */
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
}

button.back-button {
    padding: 10px 20px;
    font-size: 16px;
    font-weight: normal;
    color: #ffffff;
    background-color: #4aaf51; /* DLSU Green for buttons */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    
}

button.back-button:hover {
    background-color: #005a24; /* Darker green for hover effect */
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: bold;
}

.alert-success {
    background-color: #007A33; /* DLSU Green */
    color: white;
}

.alert-info {
    background-color: #28a745; /* A lighter green for info */
    color: white;
}

.alert-danger {
    background-color: #d9534f; /* Red color for error */
    color: white;
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

    
    .footer .w {
        margin: 0;
    }

</style>

<body>
<div class="container">
    <form action="titleid3.php" method="get">
        <!-- Button to go back -->
        <button type="button" class="back-button" onclick="window.history.back();">
            <box-icon name="arrow-back"></box-icon> Return
        </button>
    </form>



        <h1>Submission Details:</h1>

        <!-- Display Chapter 1 details -->
        <h2>Chapter 1</h2>
        <p><strong>Status:</strong> <?php echo $upload_status; ?></p>
        <p><strong>File ID:</strong> <?php echo $file_id_chap1; ?></p>
        <p><strong>File Name:</strong> <?php echo $file_name_chap1; ?></p>
        <p><strong>File Size:</strong> <?php echo $file_size_chap1; ?> bytes</p>
        <p><strong>File Path:</strong> <?php echo $file_path_chap1; ?></p>
        <p><strong>Title ID:</strong> <?php echo $title_id_chap1; ?></p>

        <!-- Display Manuscript details -->
        <h2>Manuscript</h2>
        <p><strong>Status:</strong> <?php echo $upload_status; ?></p>
        <p><strong>File ID:</strong> <?php echo $file_id_manu; ?></p>
        <p><strong>File Name:</strong> <?php echo $file_name_manu; ?></p>
        <p><strong>File Size:</strong> <?php echo $file_size_manu; ?> bytes</p>
        <p><strong>File Path:</strong> <?php echo $file_path_manu; ?></p>
        <p><strong>Title ID:</strong> <?php echo $title_id_manu; ?></p>
    </div>



<div class="footer">
        <w>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</w>
    </div>


</body>


</html>