<?php
// Initialize the session
session_start();
$userID = $_SESSION["id"];
$description = "";
$value = 0;
$getInterestInfo =
    "SELECT * FROM interests WHERE userID = '$userID';";

// Include config file
require_once "includes/dbconnect.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}




$row = "false";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {

    if (isset($_POST)) {
        // Taking all 5 values from the form data(input)
        $gender = $_POST['gender'];
        $race = $_POST['race'];
        $birthday = $_POST['birthDay'];
        $age = floor((time() - strtotime($birthday)) / 31556926);
        $interestDataUpdated = NULL;

        // update user info
        $sql =
            "UPDATE users 
        SET gender = '$gender', age = '$age', race = '$race', birthday = '$birthday'
        WHERE id = '$userID';";
        $stmt = $sql;
        if (mysqli_query($link, $sql)) {
            $userDataUpdated = true;
        } else {
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($link);
            echo $userID;
        }
        // if current values are set, clear data
        if (mysqli_query($link, $getInterestInfo)) {
            $clearData = "DELETE FROM interests WHERE userID = '$userID'";
            if (mysqli_query($link, $clearData)) {
                $userDataCleared = true;
            } else {
                echo "ERROR: Hush! Sorry $clearData. "
                    . mysqli_error($clearData);
                echo $userID;
            }

        } else {
            echo "ERROR: Hush! Sorry $getInterestInfo. "
                . mysqli_error($link);
        }

        // insert or update interests
        if (isset($_POST['interests'])) {
            foreach ($_POST['interests'] as $key => $value) {
                $description = $value;
                $value = 1;
                $sql2 = "INSERT INTO interests(userID, description, value)
            VALUES ('$userID','$description', '$value') 
            ON DUPLICATE KEY UPDATE 
            description = '$description', value = '$value'";
                $stmt = $sql2;
                if (mysqli_query($link, $sql2)) {
                    $interestDataUpdated = true;
                } else {
                    echo "ERROR: Hush! Sorry $sql2. "
                        . mysqli_error($link);
                }
            }

        }
    }
    if ($userDataUpdated == true && $interestDataUpdated == true) {
        echo "<h3> Your Profile has been updated! </h3>";
    } else if ($userDataUpdated == true && $interestDataUpdated == false) {
        echo "<h3> Your General Info been updated! </h3>";
    } else if ($userDataUpdated == false && $interestDataUpdated == true) {
        echo "<h3> Your Interests have been updated! </h3>";
    }

}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles/style.css?v=<?php echo time(); ?>">
</head>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<body>
    <?php include 'includes/homebar.php' ?>
    <h1>Hi, <b>
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </b>. Here is where you will find account information and settings.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>

    <?
    //Get current user data
    $getUserInfo = 
    "SELECT * FROM users WHERE id = '$userID';";
     if(mysqli_query($link, $getUserInfo)){
        $response = @mysqli_query($link, $getUserInfo);
        while($row = mysqli_fetch_array($response)){
            $ageCurrent = $row['age'];
            $raceCurrent = $row['race'];
            $genderCurrent = $row['gender'];
            $birthdayCurrent = $row['birthday'];

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
            <label for="age">Birth Day:</label>
            <input type="date" value = <? echo $birthdayCurrent; ?> required="true" id="birthDay" name="birthDay">
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
            <br>
            <a href="poiChoice.html">Choose Interested Points of Interest</a>
            <input type="submit" value="Submit">

        </form>


    </div>
</body>

</html>