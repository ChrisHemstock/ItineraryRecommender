<?php
// Initialize the session
session_start();

// Include config file
require_once "dbconnect.php";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$row = "false";

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
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
        <form action="account.php" method="post">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
            <option selected disabled value=""></option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="age">Age</label>
            <input type="number" name="age" id="age" min="0" max="120" required = "true">
            <label for="race">Race</label>
            <select name="race" id="race" required>
            <option selected disabled value=""></option>
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
                <input type="checkbox" name="artsEntertainment" id="artsEntertainment" >
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
            <input type="submit" value="Submit">

        </form>
    </div>
</body>
</html>
<?php
$userID = $_SESSION["id"];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {

    if(isset($_POST)){ // Taking all 5 values from the form data(input)
        $gender =   $_POST['gender'];
        $race =  $_POST['race'];
        $age =  $_POST['age'];
        $artsEntertainment=  (isset($_POST['artsEntertainment'])) ? 1:0;
        $vehicles=  (isset($_POST['vehicles'])) ? 1:0;
        $beautyFitness=  (isset($_POST['beautyFitness'])) ? 1:0;
        $businessIndustrial=  (isset($_POST['businessIndustrial'])) ? 1:0;
        $electronics=  (isset($_POST['electronics'])) ? 1:0;
        $finance=  (isset($_POST['finance'])) ? 1:0;
        $food=  (isset($_POST['food'])) ? 1:0;
        $games=  (isset($_POST['games'])) ? 1:0;
        $leisure=  (isset($_POST['leisure'])) ? 1:0;
        $homeGarden= (isset($_POST['homeGarden'])) ? 1:0;
        $internetTelecom=  (isset($_POST['internetTelecom'])) ? 1:0;
        $jobsEducation=  (isset($_POST['jobsEducation'])) ? 1:0;
        $books=  (isset($_POST['books'])) ? 1:0;;

        
         
        // Performing insert query execution
        // here our table name is college
   
        $sql = 
        "UPDATE users 
        SET gender = '$gender', age = '$age', race = '$race'
        WHERE id = '$userID';";
         $stmt = $sql;
         if(mysqli_query($link, $sql)){
            echo "<h3>Your user information has been updated</h3>";

        }else{
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($link);
                echo $userID;
        }
    
        $sql2 = "INSERT INTO interests(userID, artsEntertainment, vehicles, beautyFitness,
        businessIndustrial, electronics, finance, food,
        games, leisure, homeGarden, internetTelecom,
        jobsEducation, books)
        VALUES ('$userID','$artsEntertainment','$vehicles', '$beautyFitness', 
        '$businessIndustrial', '$electronics','$finance', '$food', 
        '$games', '$leisure', '$homeGarden', '$internetTelecom', 
        '$jobsEducation', '$books') 
        ON DUPLICATE KEY UPDATE 
        artsEntertainment = '$artsEntertainment', vehicles = '$vehicles', beautyFitness = '$beautyFitness',
        businessIndustrial = '$businessIndustrial', electronics = '$electronics', finance = '$finance', food = '$food',
        games = '$games', leisure = '$leisure', homeGarden = '$homeGarden', internetTelecom = '$internetTelecom',
        jobsEducation = '$jobsEducation', books = '$books'";
         $stmt = $sql2;
         if(mysqli_query($link, $sql2)){
            echo "<h3>Your interest data has been updated.</h3>";

        }else{
            echo "ERROR: Hush! Sorry $sql2. "
                . mysqli_error($link);
        }
        }
    }

         
?>
