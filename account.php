<?php
// Initialize the session
session_start();
$userID = $_SESSION["id"];
$artCurrent = 0;
$vehicleCurrent = 0; 
$beautyCurrent = 0; 
$financeCurrent = 0;  
$gamesCurrent = 0; 
$leisureCurrent = 0;
$homeGardenCurrent = 0; 
$internetCurrent = 0; 
$jobsEducationCurrent = 0; 
$electronicsCurrent = 0; 
$booksCurrent = 0;  
$businessCurrent = 0;
$foodCurrent = 0;

// Include config file
require_once "dbconnect.php";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$row = "false";


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

    <?
    $getUserInfo = 
    "SELECT * FROM users WHERE id = '$userID';";
     if(mysqli_query($link, $getUserInfo)){
        $response = @mysqli_query($link, $getUserInfo);
        while($row = mysqli_fetch_array($response)){
            $ageCurrent = $row['age'];
            $raceCurrent = $row['race'];
            $genderCurrent = $row['gender'];

        }
    }else{
        echo "ERROR: Hush! Sorry $getUserInfo. "
            . mysqli_error($getUserInfo);
    }

    ?>

    <?
    $getInterestInfo = 
    "SELECT * FROM interests WHERE userID = '$userID';";
     if(mysqli_query($link, $getInterestInfo)){
        $response = @mysqli_query($link, $getInterestInfo);
        while($row = mysqli_fetch_array($response)){
            $artCurrent = $row['artsEntertainment'];  
            $vehicleCurrent = $row['vehicles'];  
            $beautyCurrent = $row['beautyFitness'];  
            $financeCurrent = $row['finance'];  
            $gamesCurrent = $row['games'];  
            $leisureCurrent = $row['leisure'];  
            $homeGardenCurrent = $row['homeGarden'];  
            $internetCurrent = $row['internetTelecom'];  
            $jobsEducationCurrent = $row['jobsEducation'];  
            $electronicsCurrent = $row['electronics'];  
            $booksCurrent = $row['books'];  
            $businessCurrent = $row['businessIndustrial'];  
            $foodCurrent = $row['food'];  

        }
    }else{
        echo "ERROR: Hush! Sorry $getInterestInfo. "
            . mysqli_error($link);
    }
    ?>
    <div id='userInfo'>
        <h2>General Info</h2>
        <form action="account.php" method="post">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
            <option selected disabled value=""></option>
            <?
                        if($genderCurrent == 'male'){
                            echo "<option selected value='male'>Male</option>";
                        }else{
                            echo "<option value='male'>Male</option>";
                        }
                        if($genderCurrent == 'female'){
                            echo "<option selected value='female'>Female</option>";
                        }else{
                            echo "<option value='female'>Female</option>";
                        }
                        if($genderCurrent == 'noResponse'){
                            echo "<option selected value='noResponse'>Prefer Not To Answer</option>";
                        }else{
                            echo "<option value='noResponse'>Prefer Not To Answer</option>";
                        }
            ?>
            </select>
            <label for="age">Age</label>
            <input type="number" name="age" id="age" min="0" max="120" required = "true" value = <?echo $ageCurrent;?>>
            <label for="race">Race</label>
            <select name="race" id="race" required>
            <option selected disabled value></option>
            <?
            if($raceCurrent == 'americanIndian/alaskaNative'){
                echo "<option selected value='americanIndian/alaskaNative'>American Indian or Alaska Native</option>";
            }else{
                echo "<option value='americanIndian/alaskaNative'>American Indian or Alaska Native</option>";
            }
            if($raceCurrent == 'asian'){
                echo "<option selected value='asian'>Asian</option>";
            }else{
                echo "<option value='asian'>Asian</option>";
            }
            if($raceCurrent == 'black'){
                echo "<option selected value='black'>Black or African American</option>";
            }else{
                echo "<option value='black'>Black or African American</option>";
            }
            if($raceCurrent == 'pacificIslander'){
                echo "<option selected value='pacificIslander'>Native Hawaiian or Other Pacific Islander</option>";
            }else{
                echo "<option value='pacificIslander'>Native Hawaiian or Other Pacific Islander</option>";
            }
            if($raceCurrent == 'white'){
                echo "<option selected value='white'>White</option>";
            }else{
                echo "<option value='white'>White</option>";
            }
            if($raceCurrent == 'other'){
                echo "<option selected value='other'>Other</option>";
            }else{
                echo "<option value='other'>Other</option>";
            }
            
            ?>
            </select>
            <br>
            <div id="interests">
                <h2>Interests</h2>

    <?
        if($artCurrent == 1){
            echo "<input checked type='checkbox' name='artsEntertainment' id='artsEntertainment' >";
            echo "<label for='artsEntertainment'>Arts & Entertainment</label>";

        }else{
            echo "<input type='checkbox' name='artsEntertainment' id='artsEntertainment' >";
            echo "<label for='artsEntertainment'>Arts & Entertainment</label>";
        }
        if($vehicleCurrent == 1){
           echo "<input checked type='checkbox' name='vehicles' id='vehicles'>";
           echo "<label for='vehicles'>Auto & Vehicles</label>";
        }else{
            echo "<input type='checkbox' name='vehicles' id='vehicles'>";
            echo "<label for='vehicles'>Auto & Vehicles</label>";
        }
        if($beautyCurrent == 1){
            echo "<input checked type='checkbox' name='beautyFitness' id='beautyFitness'>";
            echo "<label for='beautyFitness'>Beauty & Fitness</label>";
         }else{
            echo "<input type='checkbox' name='beautyFitness' id='beautyFitness'>";
            echo "<label for='beautyFitness'>Beauty & Fitness</label>";
         }
         if($booksCurrent == 1){
           echo "<input checked type='checkbox' name='books' id='books'>";
           echo "<label for='books'>Books & Literature</label>";
         }else{
            echo "<input type='checkbox' name='books' id='books'>";
            echo "<label for='books'>Books & Literature</label>";
         }
         if($businessCurrent == 1){
            echo "<input checked type='checkbox' name='businessIndustrial' id='businessIndustrial'>";
            echo "<label for='businessIndustrial'>Business & Industrial</label>";
          }else{
            echo "<input type='checkbox' name='businessIndustrial' id='businessIndustrial'>";
            echo "<label for='businessIndustrial'>Business & Industrial</label>";
          }
          if($electronicsCurrent == 1){
            echo "<input checked type='checkbox' name='electronics' id='electronics'>";
            echo "<label for='electronics'>Computers & Electronics</label>";
          }else{
            echo "<input type='checkbox' name='electronics' id='electronics'>";
            echo "<label for='electronics'>Computers & Electronics</label>";
          }
          if($financeCurrent == 1){
           echo "<input checked type='checkbox' name='finance' id='finance'>";
           echo "<label for='finance'>Finance</label>";
          }else{
            echo "<input type='checkbox' name='finance' id='finance'>";
            echo "<label for='finance'>Finance</label>";
          }
          if($foodCurrent == 1){
           echo "<input checked type='checkbox' name='food' id='food'>";
           echo "<label for='food'>Food & Drink</label>";
           }else{
            echo "<input type='checkbox' name='food' id='food'>";
            echo "<label for='food'>Food & Drink</label>";
           }
           if($gamesCurrent == 1){
            echo "<input checked type='checkbox' name='games' id='games'>";
            echo "<label for='games'>Games</label>";
            }else{
                echo "<input type='checkbox' name='games' id='games'>";
                echo "<label for='games'>Games</label>";
            }
            if($leisureCurrent == 1){
                echo "<input checked type='checkbox' name='leisure' id='leisure'>";
                echo "<label for='leisure'>Hobbies & Leisure</label>";
                }else{
                    echo "<input type='checkbox' name='games' id='games'>";
                    echo "<label for='games'>Games</label>";
                }
                if($homeGardenCurrent == 1){
                    echo "<input checked type='checkbox' name='homeGarden' id='homeGarden'>";
                    echo "<label for='homeGarden'>Home & Garden</label>";
                    }else{
                        echo "<input type='checkbox' name='homeGarden' id='homeGarden'>";
                        echo "<label for='homeGarden'>Home & Garden</label>";
                    }
                    if($internetCurrent == 1){
                        echo "<input checked type='checkbox' name='internetTelecom' id='internetTelecom'>";
                        echo "<label for='internetTelecom'>Internet & Telecom</label>";
                        }else{
                            echo "<input type='checkbox' name='internetTelecom' id='internetTelecom'>";
                            echo "<label for='internetTelecom'>Internet & Telecom</label>";
                        }
                        if($jobsEducationCurrent == 1){
                            echo "<input checked type='checkbox' name='jobsEducation' id='jobsEducation'>";
                            echo "<label for='jobsEducation'>Jobs & Education</label>";
                            }else{
                                echo "<input type='checkbox' name='jobsEducation' id='jobsEducation'>";
                                echo "<label for='jobsEducation'>Jobs & Education</label>";
                            }
        ?>
            </div>
            <input type="submit" value="Submit">

        </form>
    </div>
</body>
</html>
