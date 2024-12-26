<?php
session_start();

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

$error = '';

if (isset($_POST['login'])) {
    $loginId = $_POST['username']; // Get the LOGIN_ID from input
    $password = $_POST['password']; // Get the USER_PASSWORD from input

    // Query to validate user credentials
    $sql = "SELECT * FROM LOGIN WHERE LOGIN_ID = ? AND USER_PASSWORD = ?";
    $params = array($loginId, $password); // Use parameterized queries to prevent SQL injection
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Check if any row matches the credentials
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_id'] = $row['LOGIN_ID']; // Store LOGIN_ID in session for further use
        header("Location: admindashboard.php"); // Redirect to dbsearch.php
        exit;
    } else {
        $error = "Incorrect User/Password";
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <title>my.DLSU-D Admin</title>
    <style>
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body{
    display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-image: url('image/login.jpg'); 
    background-size: cover;
    background-position: center; /* Center the background image */
    background-color: rgba(3, 45, 27, 0.53); /*greenish overlay */
    background-blend-mode: overlay; /* Blends image with the background color */
    color: #333; /*for contrast */
  background-size: cover;
  background-position: center;
}
.wrapper{
  width: 420px;
  background: transparent;
  border: 2px solid rgba(255, 255, 255, .2);
  backdrop-filter: blur(3px);
  box-shadow: 0 0 30px rgba(0, 0, 0, .5);
  color: #fff;
 
  justify-content: center;
  align-items: center;
  border-radius: 20px;
  padding: 30px 40px;
  overflow: hidden;

}
.wrapper .icon-close{
position: absolute;
top: 0;
right: 0;
width: 45px;
height: 45px;
background: #4aaf51;
font-size: 2em;
color: white;
display: flex;
justify-content: center;
align-items: center;
border-bottom-left-radius: 20px;
cursor: pointer;
z-index: 1;
}    

.wrapper h1{
  font-size: 36px;
  text-align: center;
}
.wrapper .input-box{
  position: relative;
  width: 100%;
  height: 50px;
  
  margin: 30px 0;
}
.input-box input{
  width: 100%;
  height: 100%;
  background: transparent;
  border: none;
  outline: none;
  border: 2px solid rgba(255, 255, 255, .2);
  border-radius: 40px;
  font-size: 16px;
  color: #fff;
  padding: 20px 45px 20px 20px;
}
.input-box input::placeholder{
  color: #fff;
}
.input-box i{
  position: absolute;
  right: 20px;
  top: 30%;
  transform: translate(-50%);
  font-size: 20px;

}
.wrapper .btn{
  width: 100%;
  height: 45px;
  background: #4aaf51;
  border: none;
  outline: none;
  border-radius: 40px;
  box-shadow: 0 0 10px rgba(0, 0, 0, .1);
  cursor: pointer;
  font-size: 18px;
  color: white;
  font-weight: 600;
  
}


       

        /* Error Message */
        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 15px;
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
    </style>
</head>
<body>

    <div class="wrapper">
    <span class="icon-close" onclick="window.location.href='dashboard3.php';">
    <ion-icon name="return-down-back-outline"></ion-icon>
</span>
    <h1>Admin Login</h1>

        <form method="POST">
      <div class="input-box">
      <input type="text" name="username" placeholder="Admin ID" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
      <input type="password" name="password" placeholder="Password" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
      <input type="submit" class="btn" name="login" value="Login">
      </div>
    </form>
  </div>

      <!-- Footer -->
      <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> De La Salle University - Dasmariñas</p>
    </div>
</body>
</html>
