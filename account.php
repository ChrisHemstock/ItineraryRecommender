<?php
// Initialize the session
session_start();
$userID = $_SESSION["id"];
$description = "";
$value = 0; 
$getInterestInfo = 
    "SELECT * FROM interests WHERE userID = '$userID';";

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

        // update user info
        $sql = 
        "UPDATE users 
        SET gender = '$gender', age = '$age', race = '$race'
        WHERE id = '$userID';";
         $stmt = $sql;
         if(mysqli_query($link, $sql)){
            $userDataUpdated = true;
        }else{
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($link);
                echo $userID;
        }
        // if current values are set, clear data
        if(mysqli_query($link, $getInterestInfo)){
            $clearData = "DELETE FROM interests WHERE userID = '$userID'";
            if(mysqli_query($link, $clearData)){
                    $userDataCleared = true;
            }else{
                echo "ERROR: Hush! Sorry $clearData. "
                    . mysqli_error($clearData);
                    echo $userID;
            }
            
        }else{
            echo "ERROR: Hush! Sorry $getInterestInfo. "
                . mysqli_error($link);
        }
    
        // insert or update interests
    if(isset($_POST['interests'])){
        foreach($_POST['interests'] as $key => $value){
            $description = $value;
            $value= 1;
            $sql2 = "INSERT INTO interests(userID, description, value)
            VALUES ('$userID','$description', '$value') 
            ON DUPLICATE KEY UPDATE 
            description = '$description', value = '$value'";
             $stmt = $sql2;
             if(mysqli_query($link, $sql2)){
                 $interestDataUpdated = true;  
            }else{
                echo "ERROR: Hush! Sorry $sql2. "
                    . mysqli_error($link);
            }
        }
        
    }
}
if($userDataUpdated == true && $interestDataUpdated == true){
    echo "<h3> Your Profile has been updated! ";
}else if($userDataUpdated == true && $interestDataUpdated == false){
    echo "Your General Info been updated!";
}else if($userDataUpdated == false && $interestDataUpdated == true){
    echo "Your Interests have been updated!";
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

            <? 
            $description;
            if(mysqli_query($link, $getInterestInfo)){
                $response = @mysqli_query($link, $getInterestInfo);
                while($row = mysqli_fetch_array($response)){
                    $description .= $row['Description'];
                }
            }else{
                echo "ERROR: Hush! Sorry $getInterestInfo. "
                    . mysqli_error($link);
            }
            

            ?>
            <div id="interests">
                <h2>Interests</h2>
                <input <? if(strpos($description, 'artsEntertainment') !== false) { ?> checked = "checked" <? } ?> type="checkbox" name="interests[]" value = "artsEntertainment" id="artsEntertainment">
                <label for="artsEntertainment">Arts & Entertainment</label>
                <input <? if(strpos($description, 'vehicles') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="vehicles" value = "vehicles">
                <label for="vehicles">Autos & Vehicles</label>
                <input <? if(strpos($description, 'beautyFitness') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="beautyFitness" value = "beautyFitness">
                <label for="beautyFitness">Beauty & Fitness</label>
                <input <? if(strpos($description, 'books') !== false) { ?> checked = "books" <? } ?>type="checkbox" name="interests[]" id="books" value = "books">
                <label for="books">Books & Literature</label>
                <input <? if(strpos($description, 'businessIndustrial') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="businessIndustrial" value="businessIndustrial">
                <label for="businessIndustrial">Business & Industrial</label>
                <input <? if(strpos($description, 'electronics') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="electronics" value="electronics">
                <label for="electronics">Computers & Electronics</label>
                <input <? if(strpos($description, 'finance') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="finance" value="finance">
                <label for="finance">Finance</label>
                <input <? if(strpos($description, 'food') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="food" value="food" >
                <label for="food">Food & Drink</label>
                <input <? if(strpos($description, 'games') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="games" value="games">
                <label for="games">Games</label>
                <input <? if(strpos($description, 'leisure') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="leisure" value="leisure">
                <label for="leisure">Hobbies & Leisure</label>
                <input <? if(strpos($description, 'homeGarden') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="homeGarden" value="homeGarden">
                <label for="homeGarden">Home & Garden</label>
                <input <? if(strpos($description, 'internetTelecom') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="internetTelecom" value="internetTelecom">
                <label for="internetTelecom">Internet & Telecom</label>
                <input <? if(strpos($description, 'jobsEducation') !== false) { ?> checked = "checked" <? } ?>type="checkbox" name="interests[]" id="jobsEducation" value="jobsEducation">
                <label for="jobsEducation">Jobs & Education</label>

            </div>
            <input type="submit" value="Submit">

        </form>


    </div>
</body>
</html>
