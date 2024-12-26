<?php
 

  $serverName = "LAPTOP-LSFR3CIB\SQLEXPRESS01";
  $connectionOptions = [
      "Database" => "webapp",
      "Uid" => "",
      "PWD" => ""
  ];

    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch latest Title_ID
    $get_last_id_sql = "SELECT TOP 1 TITLE_ID FROM TITLE ORDER BY TITLE_ID DESC";
    $last_id_result = sqlsrv_query($conn, $get_last_id_sql);

    if ($last_id_result) {
        $row = sqlsrv_fetch_array($last_id_result, SQLSRV_FETCH_ASSOC);
        $last_id = $row['TITLE_ID'];

        //Fetch Title related data From Title
        $titleinfo_sql = "SELECT * FROM TITLE WHERE TITLE_ID = ?";
        $info_result = sqlsrv_query($conn, $titleinfo_sql, array($last_id));
        $info = sqlsrv_fetch_array($info_result, SQLSRV_FETCH_ASSOC);

        // Fetch related data from AUTHOR
        $author_sql = "SELECT * FROM AUTHOR WHERE TITLE_ID = ?";
        $author_result = sqlsrv_query($conn, $author_sql, array($last_id));
        $authors = [];
        while ($author = sqlsrv_fetch_array($author_result, SQLSRV_FETCH_ASSOC)) {
            $authors[] = $author;
        }

        // Fetch related data from ADVISER
        $adviser_sql = "SELECT * FROM ADVISER WHERE TITLE_ID = ?";
        $adviser_result = sqlsrv_query($conn, $adviser_sql, array($last_id));
        $adviser = sqlsrv_fetch_array($adviser_result, SQLSRV_FETCH_ASSOC);

        // Fetch related data from CO_ADVISER
        $coadviser_sql = "SELECT * FROM CO_ADVISER WHERE TITLE_ID = ?";
        $coadviser_result = sqlsrv_query($conn, $coadviser_sql, array($last_id));
        $coadviser = sqlsrv_fetch_array($coadviser_result, SQLSRV_FETCH_ASSOC);

        // Fetch related data from CONTACT
        $contact_sql = "SELECT * FROM CONTACT WHERE TITLE_ID = ?";
        $contact_result = sqlsrv_query($conn, $contact_sql, array($last_id));
        $contact = sqlsrv_fetch_array($contact_result, SQLSRV_FETCH_ASSOC);

            // Check if 'School_Year' exists in the $info array
    $schoolYear = isset($info['School_Year']) ? $info['School_Year'] : 'N/A';

    // If the 'School_Year' contains a "-", split it into two parts
    if (strpos($schoolYear, '-') !== false) {
        $schoolYearParts = explode('-', $schoolYear);
        $schoolYearPart1 = htmlspecialchars($schoolYearParts[0]);
        $schoolYearPart2 = isset($schoolYearParts[1]) ? htmlspecialchars($schoolYearParts[1]) : '';
    } else {
        $schoolYearPart1 = htmlspecialchars($schoolYear);
        $schoolYearPart2 = ''; // Empty if no "-" is found
    }

    }
?>


<?php

                $titleErr="";

                if(empty($_POST['title'])) {
                    $titleErr = "Thesis Title is Required";
                }
                $author1Err=""; $author2Err="";

                if(empty($_POST['a1l']) || empty($_POST['a1f']) || empty($_POST['a1m'])) {
                    $author1Err = "Author 1 is Required";
                }

                if(empty($_POST['a2l']) || empty($_POST['a2f']) || empty($_POST['a2m'])) {
                    $author2Err = "Author 2 is Required";
                }
                $author3Err="";
                $author4Err="";
                $adviserErr="";
                
                if(empty($_POST['ad_l']) || empty($_POST['ad_f']) || empty($_POST['ad_m'])) {
                    $adviserErr = "Adviser is Required";
                }
                $programErr="";

                if(empty($_POST['Program'])) {
                    $programErr = "Program is Required";
                }
                $yearErr="";

                if (empty($_POST['s_year'])) {
                    $yearErr = "School Year is Required";
                }
                $submissionErr="";

                if(empty($_POST['submission'])) {
                    $submissionErr = "Date of Submission is Required";
                }
                $studyErr="";

                if(empty($_POST['subject'])) {
                    $studyErr = "Subject of the Study is Required";
                }
                $phoneErr="";

                if(empty($_POST['phone'])) {
                    $phoneErr = "Phone number is Required";
                }
                $phonelenErr="";
    
                if(isset($_POST['phone'])){
                    $phone = $_POST['phone'];
                }else{
                    $phone = "999999999999";
                } 

                if(strlen($_POST['phone'] ?? null)!=12){
                    $phonelenErr="Phone Number should be 12 digits";
                }
                
                $emailErr="";

                if(empty($_POST['email'])){
                    $emailErr = "Email is Required";
                } 

?>


<?php
ob_start();

if (isset($_POST['submit'])) {
    
    if ($titleErr == "" && $author1Err == "" && $author2Err == "" && $author3Err == "" && $author4Err == "" && $adviserErr == "" && $programErr == "" && $yearErr == "" && $submissionErr == "" && $studyErr == "" && $phoneErr == "" && $phonelenErr == "" && $emailErr == "") {
        
        $serverName = "LAPTOP-9RL09P47\SQLEXPRESS";
        $connectionOptions = [
            "Database" => "Webapp1",
            "Uid" => "",
            "PWD" => ""
        ];

        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn == false)
            die(print_r(sqlsrv_errors(), true));
        else
            echo 'Connection Success';

        $title = $_POST['title'];
        $program = $_POST['Program'];
        $co_program = $_POST['CO_Program'] ?? null;
        $syear = $_POST['s_year'];
        $eyear = $_POST['e_year'];

        if (empty($_POST['e_year'])) {
            $year = htmlspecialchars($_POST['s_year']);
        } else {
            $year = (htmlspecialchars($_POST['s_year']) . '-' . htmlspecialchars($_POST['e_year']));
        } 
        
        $date = $_POST['submission'];
        $subject = $_POST['subject'];

        $a1l = $_POST['a1l'];
        $a1f = $_POST['a1f'];
        $a1m = $_POST['a1m'];
        $a2l = $_POST['a2l'];
        $a2f = $_POST['a2f'];
        $a2m = $_POST['a2m'];
        $a3l = $_POST['a3l'] ?? null;
        $a3f = $_POST['a3f'] ?? null;
        $a3m = $_POST['a3m'] ?? null;
        $a4l = $_POST['a4l'] ?? null;
        $a4f = $_POST['a4f'] ?? null;
        $a4m = $_POST['a4m'] ?? null;

        $ad_l = $_POST['ad_l'];
        $ad_f = $_POST['ad_f'];
        $ad_m = $_POST['ad_m'];
        $cad_l = $_POST['cad_l'] ?? null;
        $cad_f = $_POST['cad_f'] ?? null;
        $cad_m = $_POST['cad_m'] ?? null;

        $phone = $_POST['phone'];
        $email = $_POST['email'];

        
        // Fetch the actual last id
        $get_last_id_sql = "SELECT TOP 1 TITLE_ID FROM TITLE ORDER BY TITLE_ID DESC";
        $last_id_result = sqlsrv_query($conn, $get_last_id_sql);


        if ($last_id_result === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $row = sqlsrv_fetch_array($last_id_result, SQLSRV_FETCH_ASSOC);
            $titleid = $row['TITLE_ID']; // Get the actual TITLE_ID from the query result
        }

        // Update the Title record
        $sql_Title = "UPDATE Title SET Title_Name = '$title', Program = '$program', Co_Program = '$co_program', School_Year = '$year', Date_of_Submission = '$date', Subject = '$subject' WHERE Title_ID = '$titleid'";
        $results_Title = sqlsrv_query($conn, $sql_Title);

        if ($results_Title) {
            echo 'Title Update Successful'; 
        } else {
            echo 'Title Update Failed';
        }
        
        // Update Author Using Author ID
        $author1 = $authors[0]['Author_ID'];
        $author2 = $authors[1]['Author_ID'];
        $author3 = $authors[2]['Author_ID'];
        $author4 = $authors[3]['Author_ID'];

        $sql_Author = "
            UPDATE Author 
            SET 
                Last_Name = CASE WHEN Author_ID = '$author1' THEN '$a1l' WHEN Author_ID = '$author2' THEN '$a2l' WHEN Author_ID = '$author3' THEN '$a3l' WHEN Author_ID = '$author4' THEN '$a4l' END,
                First_Name = CASE WHEN Author_ID = '$author1' THEN '$a1f' WHEN Author_ID = '$author2' THEN '$a2f' WHEN Author_ID = '$author3' THEN '$a3f' WHEN Author_ID = '$author4' THEN '$a4f' END,
                Middle_Name = CASE WHEN Author_ID = '$author1' THEN '$a1m' WHEN Author_ID = '$author2' THEN '$a2m' WHEN Author_ID = '$author3' THEN '$a3m' WHEN Author_ID = '$author4' THEN '$a4m' END,
                Title_ID = CASE WHEN Author_ID = '$author1' THEN '$titleid' WHEN Author_ID = '$author2' THEN '$titleid' WHEN Author_ID = '$author3' THEN '$titleid' WHEN Author_ID = '$author4' THEN '$titleid' END
            WHERE Author_ID IN ('$author1', '$author2', '$author3', '$author4')
        ";
        $results_Author = sqlsrv_query($conn, $sql_Author);

        // Update Adviser Record
        $sql_Adviser = "UPDATE Adviser SET Last_Name = '$ad_l', First_Name = '$ad_f', Middle_Name = '$ad_m' WHERE Title_ID = '$titleid'";
        $results_Adviser = sqlsrv_query($conn, $sql_Adviser);


        // Update the Co-Adviser record
        $sql_CO_Adviser = "UPDATE CO_Adviser SET Last_Name = '$cad_l', First_Name = '$cad_f', Middle_Name = '$cad_m' WHERE Title_ID = '$titleid'";
        $results_COad = sqlsrv_query($conn, $sql_CO_Adviser);


        // Update the Contact record
        $sql_Contact = "UPDATE Contact SET Phone_Number = '$phone', Email = '$email' WHERE Title_ID = '$titleid'";
        $results_Contact = sqlsrv_query($conn, $sql_Contact);

        if ($results_Title && $results_Author && $results_Adviser && $results_COad && $results_Contact) {
            header("Location: thesis_success.php");
            exit();
        } else {
            die(print_r(sqlsrv_errors(), true));
        }

    } else {
        $errorMessages = '';
        $errorMessages .= !empty($titleErr) ? $titleErr . "\\n" : '';
        $errorMessages .= !empty($author1Err) ? $author1Err . "\\n" : '';
        $errorMessages .= !empty($author2Err) ? $author2Err . "\\n" : '';
        $errorMessages .= !empty($author3Err) ? $author3Err . "\\n" : '';
        $errorMessages .= !empty($author4Err) ? $author4Err . "\\n" : '';
        $errorMessages .= !empty($adviserErr) ? $adviserErr . "\\n" : '';
        $errorMessages .= !empty($programErr) ? $programErr . "\\n" : '';
        $errorMessages .= !empty($yearErr) ? $yearErr . "\\n" : '';
        $errorMessages .= !empty($submissionErr) ? $submissionErr . "\\n" : '';
        $errorMessages .= !empty($studyErr) ? $studyErr . "\\n" : '';
        $errorMessages .= !empty($phoneErr) ? $phoneErr . "\\n" : '';
        $errorMessages .= !empty($phonelenErr) ? $phonelenErr . "\\n" : '';
        $errorMessages .= !empty($emailErr) ? $emailErr . "\\n" : '';
    
        echo "<script type='text/javascript'>alert('Mistakes found in the form:\\n$errorMessages');</script>";
    }

    ob_end_flush();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Registration Form</title>

    </head>
 
    <body>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Registration Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #006747;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
        }

        h2 {
            color: #006747;
            font-size: 24px;
            margin-bottom: 15px;
            border-bottom: 2px solid #006747;
            padding-bottom: 5px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input,
        select,
        button {
            padding: 12px;
            margin-bottom: 20px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }

        .contact-fields {
            display: flex;
            gap: 20px;
        }

        .contact-fields .field {
            width: 48%;
        }

        .flex-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .flex-group input {
            flex: 1;
            min-width: 30%;
        }

        button {
            background-color: #006747;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 1000;
            font-size: 20px;
            padding: 15px;
            width: auto;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #004d33;
        }

        .form-submit {
            display: flex;
            justify-content: center;
            margin-top: 1px;
        }

        .info-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .info-flex div {
            flex: 1;
            min-width: 250px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .footer {
            background-color: #006747;
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

    <form id="registration" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="container">
            <div class="child">
                <h1>Research Update</h1>

                <h2>Thesis Title</h2> 
                    <input type="text" id="title" name="title" value="<?php echo isset($info['Title_Name']) ? htmlspecialchars($info['Title_Name']) : ''; ?>">
                
                <h2>Author Information</h2>

                    <label>Author 1</label>
                    <div class="flex-group">
                        <input type="text" id="a1l" name="a1l" placeholder="Last Name" value="<?php echo htmlspecialchars($authors[0]['Last_Name'] ?? ''); ?>">
                        <input type="text" id="a1f" name="a1f" placeholder="First Name" value="<?php echo htmlspecialchars($authors[0]['First_Name'] ?? ''); ?>">
                        <input type="text" id="a1m" name="a1m" placeholder="Middle Name" value="<?php echo htmlspecialchars($authors[0]['Middle_Name'] ?? ''); ?>">
                    </div>

                    <label>Author 2</label>
                    <div class="flex-group">
                        <input type="text" id="a2l" name="a2l" placeholder="Last Name" value="<?php echo htmlspecialchars($authors[1]['Last_Name'] ?? ''); ?>">
                        <input type="text" id="a2f" name="a2f" placeholder="First Name" value="<?php echo htmlspecialchars($authors[1]['First_Name'] ?? ''); ?>">
                        <input type="text" id="a2m" name="a2m" placeholder="Middle Name" value="<?php echo htmlspecialchars($authors[1]['Middle_Name'] ?? ''); ?>">
                    </div>
                
                    <label>Author 3</label>
                    <div class="flex-group">
                        <input type="text" id="a3l" name="a3l" placeholder="Last Name" value="<?php echo htmlspecialchars($authors[2]['Last_Name'] ?? ''); ?>">
                        <input type="text" id="a3f" name="a3f" placeholder="First Name" value="<?php echo htmlspecialchars($authors[2]['First_Name'] ?? ''); ?>">
                        <input type="text" id="a3m" name="a3m" placeholder="Middle Name" value="<?php echo htmlspecialchars($authors[2]['Middle_Name'] ?? ''); ?>">
                    </div>

                    <label>Author 4</label>
                    <div class="flex-group">
                        <input type="text" id="a4l" name="a4l" placeholder="Last Name" value="<?php echo htmlspecialchars($authors[3]['Last_Name'] ?? ''); ?>">
                        <input type="text" id="a4f" name="a4f" placeholder="First Name" value="<?php echo htmlspecialchars($authors[3]['First_Name'] ?? ''); ?>">
                        <input type="text" id="a4m" name="a4m" placeholder="Middle Name" value="<?php echo htmlspecialchars($authors[3]['Middle_Name'] ?? ''); ?>">
                    </div>

            
                <h2>Thesis Information</h2> 
                <div class="info-flex">
                    <div>
                        <label >School Year :</label>
                        <input type="number" id="s_year" name="s_year" min="1977" max="2024" placeholder="Start Year" value="<?php echo isset($schoolYearPart1) ? htmlspecialchars($schoolYearPart1) : ''; ?>">
                    </div>

                    
                    <div>
                    <label>Program</label>
                        <select name="Program" id="Program" >
                            <option value="CPE" <?php if (isset($row['Program']) && $row['Program'] == 'CPE') echo 'selected'; ?>>CPE</option>
                            <option value="ECE" <?php if (isset($row['Program']) && $row['Program'] == 'ECE') echo 'selected'; ?>>ECE</option>
                            <option value="CE" <?php if (isset($row['Program']) && $row['Program'] == 'CE') echo 'selected'; ?>>CE</option>
                            <option value="IE" <?php if (isset($row['Program']) && $row['Program'] == 'IE') echo 'selected'; ?>>IE</option>
                            <option value="ME" <?php if (isset($row['Program']) && $row['Program'] == 'ME') echo 'selected'; ?>>ME</option>
                            <option value="EE" <?php if (isset($row['Program']) && $row['Program'] == 'EE') echo 'selected'; ?>>EE</option>
                            <option value="AC" <?php if (isset($row['Program']) && $row['Program'] == 'AC') echo 'selected'; ?>>ARCHI</option>
                            <option value="MMA" <?php if (isset($row['Program']) && $row['Program'] == 'MMA') echo 'selected'; ?>>MA</option>
                        </select>
                    </div>
                </div>
            
                <div>
                <label>Co-Program</label>
                <div>
                    <select name="CO_Program" id="CO_Program" style="width: 430px;">
                        <option value="CPE" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'CPE') echo 'selected'; ?>>CPE</option>
                        <option value="ECE" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'ECE') echo 'selected'; ?>>ECE</option>
                        <option value="CE" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'CE') echo 'selected'; ?>>CE</option>
                        <option value="IE" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'IE') echo 'selected'; ?>>IE</option>
                        <option value="ME" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'ME') echo 'selected'; ?>>ME</option>
                        <option value="EE" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'EE') echo 'selected'; ?>>EE</option>
                        <option value="AC" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'AC') echo 'selected'; ?>>ARCHI</option>
                        <option value="MMA" <?php if (isset($row['CO_Program']) && $row['CO_Program'] == 'MMA') echo 'selected'; ?>>MA</option>
                    </select> 
                </div>
                </div>
        
                <div>
                <label>Date of Submission</label>
                    <div>
                    <input type="date" id="submission" name="submission" min="1977-01-01" max="2024-12-31" style="width: 430px;" value="<?php echo isset($info['Date_of_Submission']) ? htmlspecialchars($info['Date_of_Submission']->format('Y-m-d')) : ''; ?>"> 
                    </div>
                </div>

                <div>
                <label>Subject of Study</label>
                    <div>
                    <select id="subject" name="subject" style="width: 430px;">
                        <option value="Accounting" <?php if (isset($info['Subject']) && $info['Subject'] == 'Accounting') echo 'selected'; ?>>Accounting</option>
                        <option value="Art" <?php if (isset($info['Subject']) && $info['Subject'] == 'Art') echo 'selected'; ?>>Art</option>
                        <option value="Biology" <?php if (isset($info['Subject']) && $info['Subject'] == 'Biology') echo 'selected'; ?>>Biology</option>
                        <option value="Computer Science" <?php if (isset($info['Subject']) && $info['Subject'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                        <option value="Computer Engineer" <?php if (isset($info['Subject']) && $info['Subject'] == 'Computer Engineer') echo 'selected'; ?>>Computer Engineer</option>
                        <option value="Electronics and Communications Engineer" <?php if (isset($info['Subject']) && $info['Subject'] == 'Electronics and Communications Engineer') echo 'selected'; ?>>Electronics and Communications Engineer</option>
                        <option value="Economics" <?php if (isset($info['Subject']) && $info['Subject'] == 'Economics') echo 'selected'; ?>>Economics</option>
                        <option value="Education" <?php if (isset($info['Subject']) && $info['Subject'] == 'Education') echo 'selected'; ?>>Education</option>
                    </select>
                    </div>
                </div>
                 </div>

                <h2>Adviser Information</h2>
                <label>Adviser</label>
                <div class="flex">  
                <div class="flex-group">
                    
                    <input type="text" id="ad_l" name="ad_l" placeholder="Last Name" value="<?php echo isset($adviser['Last_Name']) ? htmlspecialchars($adviser['Last_Name']) : ''; ?>">
                    <input type="text" id="ad_f" name="ad_f" placeholder="First Name" value="<?php echo isset($adviser['First_Name']) ? htmlspecialchars($adviser['First_Name']) : ''; ?>">
                    <input type="text" id="ad_m" name="ad_m" placeholder="Middle Name" value="<?php echo isset($adviser['Middle_Name']) ? htmlspecialchars($adviser['Middle_Name']) : ''; ?>">
                </div>

                <label>Co-Adviser</label>
                <div class="flex-group">
                    
                    <input type="text" id="cad_l" name="cad_l" placeholder="Last Name" value="<?php echo isset($coadviser['Last_Name']) ? htmlspecialchars($coadviser['Last_Name']) : ''; ?>">
                    <input type="text" id="cad_f" name="cad_f" placeholder="First Name" value="<?php echo isset($coadviser['First_Name']) ? htmlspecialchars($coadviser['First_Name']) : ''; ?>">
                    <input type="text" id="cad_m" name="cad_m" placeholder="Middle Name" value="<?php echo isset($coadviser['Middle_Name']) ? htmlspecialchars($coadviser['Middle_Name']) : ''; ?>">
                </div>
                </div>


                <div>
                    <h2>Contact Information</h2>
                    <div class="contact-fields">
                        <div class="field">
                            <label>Phone Number</label>
                            <input type="tel" id="phone" name="phone" pattern="[0-9]{4}-[0-9]{7}" placeholder="09XX-XXXXXXX" style="width: 400px;" value="<?php echo isset($contact['Phone_Number']) ? htmlspecialchars($contact['Phone_Number']) : ''; ?>">
                        </div>

                        <div class="field">
                            <label>Email</label>
                            <input type="email" id="email" name="email" style="width: 400px;" value="<?php echo isset($contact['Email']) ? htmlspecialchars($contact['Email']) : ''; ?>"> 
                        </div>
                        

                    </div>
                </div>
                <div class="form-submit">
                            <button type="submit" name="submit" class="button">Update</button>
                        </div>
            </div>
        </div>
    </form>


                <div class="footer">
                    <p>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</p>
                </div>

            </body>

            </html>

