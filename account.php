<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<head>
    <ul style = "list-style-type: none; margin: 0; padding-bottom: 2%;">
      <li style = "display: inline;"><a href="map.html">Current Trip</a></li>
      <li style = "display: inline;"><a href="account.php">Account</a></li>
    </ul>
    <h3>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Here is where you will find account information and settings.</h3>
    <p>
    <li style = "display: inline;"><a href="reset-password.php" class="btn btn-warning">Reset Your Password</a></li>
    <li style = "display: inline;"><a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a></li>
    </p>
</body>
</html>