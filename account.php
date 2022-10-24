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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <ul style = "list-style-type: none; margin: 0; padding-bottom: 2%;">
      <li style = "display: inline;"><a href="TestFetch.php">Current Trip</a></li>
      <li style = "display: inline;"><a href="account.php">Account</a></li>
    </ul>
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Here is where you will find account information and settings.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <div id='userInfo'>
        <h2>General Info</h2>
        <form action="">
            <label for="fName">First Name</label>
            <input type="text" name="firstName" id="fName">
            <label for="lName">Last Name</label>
            <input type="text" name="lastName" id="lName">
            <br>
            <label for="gender">Gender</label>
            <select name="gender" id="gender">
                <option value="blank"></option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="age">Age</label>
            <input type="number" name="age" id="age" min="0" max="120">
            <label for="race">Race</label>
            <select name="race" id="race">
                <option value="blank"></option>
                <option value="americanIndian/alaskaNative">American Indian or Alaska Native</option>
                <option value="asian">Asian</option>
                <option value="black">Black or African American</option>
                <option value="pacificIslander">Native Hawaiian or Other Pacific Islander</option>
                <option value="white">White</option>
                <option value="other">Other</option>
                <option value="noResponse">No Response</option>
            </select>
            <br>
            <div id="interests">
                <h2>Interests</h2>
                <input type="checkbox" name="artsEntertainment" id="artsEntertainment">
                <label for="artsEntertainment">Arts & Entertainment</label>
                <input type="checkbox" name="vehicles" id="vehicles">
                <label for="vehicles">Autos & Vehicles</label>
                <input type="checkbox" name="beautyFitness" id="beautyFitness">
                <label for="beautyFitness">Beauty & Fitness</label>
                <input type="checkbox" name="books" id="books">
                <label for="books">Books & Literature</label>
                <input type="checkbox" name="businessIndustrial" id="businessIndustrial">
                <label for="businessIndustrial">Business & Industrial</label>
                <input type="checkbox" name="electronics" id="electronics">
                <label for="electronics">Computers & Electronics</label>
                <input type="checkbox" name="finance" id="finance">
                <label for="finance">Finance</label>
                <input type="checkbox" name="food" id="food">
                <label for="food">Food & Drink</label>
                <input type="checkbox" name="games" id="games">
                <label for="games">Games</label>
                <input type="checkbox" name="leisure" id="leisure">
                <label for="leisure">Hobbies & Leisure</label>
                <input type="checkbox" name="homeGarden" id="homeGarden">
                <label for="homeGarden">Home & Garden</label>
                <input type="checkbox" name="internetTelecom" id="internetTelecom">
                <label for="internetTelecom">Internet & Telecom</label>
                <input type="checkbox" name="jobsEducation" id="jobsEducation">
                <label for="jobsEducation">Jobs & Education</label>
            </div>
        </form>
    </div>
</body>
</html>