<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="icon" href="image/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <title>my.DLSU-D Thesis</title>


</head>

<?php
function displayError($error) {
    if (!empty($error)) {
        echo "<span class='error' style='color: red;'>$error</span>";
    }
}

ob_start();
$errors = [];

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Validate each field
    if (empty($_POST['title'])) {
        $errors['title'] = "Research Title is required";
    }
    if (empty($_POST['A1Firstname']) || empty($_POST['A1Lastname'])) {
        $errors['authors'] = "Author's Name is required";
    }
    if (empty($_POST['AdviserLastname']) || empty($_POST['AdviserFirstname'])) {
        $errors['adviser'] = "Adviser's Name is required";
    }
    if (empty($_POST['Program'])) {
        $errors['program'] = "Program is required";
    }
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    }
    if (empty($_POST['sy'])) {
        $errors['sy'] = "School Year is required";
    }
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Contact number is required";
    } else {
        $contact_number = $_POST['phone'];
        if (strlen($contact_number) != 11) { 
            $errors['phone_length'] = "Valid PH number required";
        }
    }
    if (empty($_POST['Subject'])) {
        $errors['subject'] = "Subject is required";
    }
    if (empty($_POST['date'])) {
        $errors['date'] = "Date of submission is required";
    }
}
?>

<h1 style="text-align: center; color: white;">Welcome! my.DLSU-D Form</h1>

<form id="registration" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 28px; color: rgb(0, 0, 0); margin: 0; flex-grow: 1; text-align: left;">
            Registration Form to my.DLSU-D
            <div style="margin-top: 10px; font-size: 22px; color: #888;">Enter your Research Title and Information to Register:</div>
        </h2>
        <img src="image/icon-72.png" alt="Logo" style="width: 100px; height: auto; margin-right: 30px; margin-top: 10px;">
    </div>

    <br>

    <h4>Research Title</h4>
    <input type="text" name="title" id="title" style="width: 880px; margin-right: 30px;" placeholder="PaddyScan: A Mobile Application for Offline Image-Based Detection of Rice Plant Disease">
    <div><?php displayError($errors['title'] ?? ''); ?></div>




    <h4>Author 1</h4>
    <input type="text" name="A1Firstname" id="A1Firstname" placeholder="First Name" style="margin-right: 10px;"pattern="[A-Za-z\s.-]+"
    title="Please enter letters only">
    <input type="text" name="A1Middlename" id="A1Middlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="A1Lastname" id="A1Lastname" placeholder="Last Name" style="margin-right: 30px;" pattern="[A-Za-z\s.-]+"
    title="Please enter letters only">
    
    <h4 style="display: inline-block;">School Year:</h4>
    <input type="text" name="sy" id="sy" placeholder="YYYY-YYYY" style="width: 190px; text-align: center;" pattern="^20\d{2}-20\d{2}$" title="Please enter a valid format: YYYY-YYYY">
    <div style="display: flex; gap: 500px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors['authors'] ?? ''); ?></div>
    <div><?php displayError($errors['sy'] ?? ''); ?></div>
</div>




    <h4>Author 2 <span style="font-size: 12px; color: gray;">(optional)</span></h4>
    <input type="text" name="A2Firstname" id="A2Firstname" placeholder="First Name" style="margin-right: 10px;">
    <input type="text" name="A2Middlename" id="A2Middlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="A2Lastname" id="A2Lastname" placeholder="Last Name" style="margin-right: 30px;">

    <h4 style="display: inline-block;">Date of Submission:</h4>
    <input type="date" name="date" id="date" style="width: 135px;" min="2000-01-01" max="2024-12-31">
    <div style="display: flex; gap: 625px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors[''] ?? ''); ?></div>
    <div><?php displayError($errors['date'] ?? ''); ?></div>
</div>
    


    <h4>Author 3 <span style="font-size: 12px; color: gray;">(optional)</span></h4>
    <input type="text" name="A3Firstname" id="A3Firstname" placeholder="First Name" style="margin-right: 10px;">
    <input type="text" name="A3Middlename" id="A3Middlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="A3Lastname" id="A3Lastname" placeholder="Last Name" style="margin-right: 30px;">

    <h4 style="display: inline-block;">Subject of Study:</h4>
    <select name="Subject" id="Subject" style="width: 140px;">
        <option value="">Select a subject</option>
        <option value="N/A">N/A</option>
        <option value="Engineering">Engineering</option>
        <option value="Health">Health</option>
        <option value="Agriculture">Agriculture</option>
        <option value="Innovation">Innovation</option>
        <option value="Infrastracture">Infrastracture</option>

    </select> 

    <div style="display: flex; gap: 650px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors[''] ?? ''); ?></div>
    <div><?php displayError($errors['subject'] ?? ''); ?></div>
</div>

    <h4>Author 4 <span style="font-size: 12px; color: gray;">(optional)</span></h4>
    <input type="text" name="A4Firstname" id="A4Firstname" placeholder="First Name" style="margin-right: 10px;">
    <input type="text" name="A4Middlename" id="A4Middlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="A4Lastname" id="A4Lastname" placeholder="Last Name" style="margin-right: 70px;">

    <h3 style="display: inline-block; font-size: larger;">Contact Information:</h3>

    <h4>Adviser</h4>
    <input type="text" name="AdviserFirstname" id="AdviserFirstname" placeholder="First Name" style="margin-right: 10px;" pattern="[A-Za-z\s.-]+"
    title="Please enter letters only">
    <input type="text" name="AdviserMiddlename" id="AdviserMiddlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="AdviserLastname" id="AdviserLastname" placeholder="Last Name" style="margin-right: 30px;"pattern="[A-Za-z\s.-]+"
    title="Please enter letters only">
    
    <h4 style="display: inline-block;">Email Address:</h4>
    <input type="email" name="email" id="email" placeholder="example@gmail.com" style="width: 170px;">
    <div style="display: flex; gap: 500px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors['adviser'] ?? ''); ?></div>
    <div><?php displayError($errors['email'] ?? ''); ?></div>
</div>

    <h4>Co-Adviser <span style="font-size: 12px; color: gray;">(optional)</span></h4>
    <input type="text" name="Co-AdviserFirstname" id="Co-AdviserFirstname" placeholder="First Name" style="margin-right: 10px;">
    <input type="text" name="Co-AdviserMiddlename" id="Co-AdviserMiddlename" placeholder="Middle Name(optional)" style="margin-right: 10px;">
    <input type="text" name="Co-AdviserLastname" id="Co-AdviserLastname" placeholder="Last Name" style="margin-right: 30px;">

    <h4 style="display: inline-block;">Contact Number:</h4>
    <input type="text" name="phone" id="phone" placeholder="09*********" style="width: 155px;" title="Please enter an 11-digit valid PH contact number">
    <div style="display: flex; gap: 650px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors[''] ?? ''); ?></div>
    <div>    <?php displayError($errors['phone'] ?? $errors['phone_length'] ?? ''); ?></div>
</div>


    <div class="program">
        <div class="inline-block">
            <h4>Program</h4>
            <select name="Program" id="Program">
                <option value="">Select a program</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Electronics Engineering">Electronics Engineering</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Industrial Engineering">Industrial Engineering</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Multimedia Arts">Multimedia Arts</option>
                <option value="Architecture">Architecture</option>
                <option value="Sanitary Engineering">Sanitary Engineering</option>
            </select>
            

        </div>
        

        <div class="inline-block">
            <h4>Co-Program <span style="font-size: 12px; color: gray;">(optional)</span></h4>
            <select name="CoProgram" id="CoProgram">
                <option value="">Select a co-program</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Electronics Engineering">Electronics Engineering</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Industrial Engineering">Industrial Engineering</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Multimedia Arts">Multimedia Arts</option>
                <option value="Architecture">Architecture</option>
                <option value="Sanitary Engineering">Sanitary Engineering</option>
            </select>
            
        </div>
    </div>
    <div style="display: flex; gap: 220px;"> <!-- Adjust gap for spacing -->
    <div><?php displayError($errors[''] ?? ''); ?></div>
    <div><?php displayError($errors['program'] ?? ''); ?></div>
    <div><?php displayError($errors[''] ?? ''); ?></div>
</div>
    

    <button type="submit" name="submit" style="margin-top: 20px;">Submit</button>
    <button type="button" onclick="window.location.href='dashboard3.php';" style="margin-top: 20px; max-width: 425px;">Return to Dashboard</button>
</form>

    <h3 style="text-align: center; color: white;">© Copyright 2024 De La Salle University - Dasmariñas</h3>

    
    <?php
        if (isset($_POST['submit'])) {
            if (empty($errors)) {
                $serverName="LAPTOP-LSFR3CIB\SQLEXPRESS01";
                $connectionOptions=[
                    "Database"=>"webapp",
                    "Uid"=>"",
                    "PWD"=>""
                    ];
                $conn = sqlsrv_connect($serverName, $connectionOptions);
                if ($conn==false)
                    die(print_r(sqlsrv_errors(),true));
                else echo 'Connection Success             '; 
                
                $title_name = $_POST['title'];
                $program = $_POST['Program'];
                $co_program = $_POST['CoProgram'];
                $school_year = $_POST['sy'];
                $date_of_submission = $_POST['date'];
                $subject = $_POST['Subject'];
                
                $author1_lname = $_POST['A1Lastname'];
                $author1_fname = $_POST['A1Firstname'];
                $author1_mname = $_POST['A1Middlename'];
                $author2_lname = $_POST['A2Lastname'];
                $author2_fname = $_POST['A2Firstname'];
                $author2_mname = $_POST['A2Middlename'];
                $author3_lname = $_POST['A3Lastname'];
                $author3_fname = $_POST['A3Firstname'];
                $author3_mname = $_POST['A3Middlename'];
                $author4_lname = $_POST['A4Lastname'];
                $author4_fname = $_POST['A4Firstname'];
                $author4_mname = $_POST['A4Middlename'];
                
                $adviser_lname = $_POST['AdviserLastname'];
                $adviser_fname = $_POST['AdviserFirstname'];
                $adviser_mname = $_POST['AdviserMiddlename'];
                
                $coadviser_lname = $_POST['Co-AdviserLastname'];
                $coadviser_fname = $_POST['Co-AdviserFirstname'];
                $coadviser_mname = $_POST['Co-AdviserMiddlename'];
                
                $phone_number = $_POST['phone'];
                $email = $_POST['email'];
                
                $title_sql = "INSERT INTO TITLE (TITLE_NAME, PROGRAM, CO_PROGRAM, SCHOOL_YEAR, DATE_OF_SUBMISSION, SUBJECT) VALUES ('$title_name', '$program', '$co_program', '$school_year', '$date_of_submission', '$subject')";
                
                $title_results = sqlsrv_query($conn, $title_sql);
                
                if ($title_results) {
                    $get_last_id_sql = "SELECT SCOPE_IDENTITY() AS TITLE_ID";
                    $last_id_result = sqlsrv_query($conn, $get_last_id_sql);
                    $row = sqlsrv_fetch_array($last_id_result, SQLSRV_FETCH_ASSOC);
                    $title_id = $row['TITLE_ID'];
                }
                
                $author1_sql = "INSERT INTO AUTHOR (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$author1_lname', '$author1_fname', '$author1_mname', '$title_id')";
                $author2_sql = "INSERT INTO AUTHOR (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$author2_lname', '$author2_fname', '$author2_mname', '$title_id')";
                $author3_sql = "INSERT INTO AUTHOR (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$author3_lname', '$author3_fname', '$author3_mname', '$title_id')";
                $author4_sql = "INSERT INTO AUTHOR (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$author4_lname', '$author4_fname', '$author4_mname', '$title_id')";
                $adviser_sql = "INSERT INTO ADVISER (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$adviser_lname', '$adviser_fname', '$adviser_mname', '$title_id')";
                $coadviser_sql = "INSERT INTO CO_ADVISER (LAST_NAME, FIRST_NAME, MIDDLE_NAME, TITLE_ID) VALUES ('$coadviser_lname', '$coadviser_fname', '$coadviser_mname', '$title_id')";
                $contact_sql = "INSERT INTO CONTACT (PHONE_NUMBER, EMAIL, TITLE_ID) VALUES ('$phone_number', '$email', '$title_id')";
                
                $author1_results = sqlsrv_query($conn, $author1_sql);
                $author2_results = sqlsrv_query($conn, $author2_sql);
                $author3_results = sqlsrv_query($conn, $author3_sql);
                $author4_results = sqlsrv_query($conn, $author4_sql);
                $adviser_results = sqlsrv_query($conn, $adviser_sql);
                $coadviser_results = sqlsrv_query($conn, $coadviser_sql);
                $contact_results = sqlsrv_query($conn, $contact_sql);
                    
                
                if ($title_results and $author1_results and $author2_results and $author3_results and $author4_results and $adviser_results and $coadviser_results and $contact_results) {
                    header("Location: titleID3.php");
                    exit();
                }
                else echo "Error";
            }
        }
                
                ?>

                
                
                </body>
                
                </html>

