<?php
require_once "includes/dbconnect.php";
session_start();
$userID = $_SESSION["id"];

$results = $link->query('SELECT * FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
$data = array();
foreach ($results as $row) {
  $id = $row[0];
  $userID = $row[1];
  $name = $row[2];
  $data[] = array($id, $userID, $name);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="styles/style.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="scripts/tripScript.js" defer></script>
  <title>Trips</title>
</head>

<body>
  <?php include 'includes/homebar.php' ?>
  <div id="trips">
    <h1>Saved Trips</h1>
    <ul>
      <?php
      foreach ($data as $row) {
        echo '<li>
                    <a href="map.php?trip=' . $row[0] . '&name=' . $row[2] . '">' . $row[2] . '</a>
                    <span class="close ' . $row[0] . '">X</span>
                  </li>';
      }
      ?>
    </ul>

    <?php
    if (isset($_POST['createTrip'])) {
      if (isset($_POST['tripName'])) {
        $tripName = $_POST['tripName'];
        $sql = "INSERT INTO trips(userID, name) VALUES('$userID', '$tripName')";
        if ($link->query($sql) === TRUE) {
          header("Refresh:0");
        } else {
          echo "Error: " . $sql . "<br>" . $link->error;
        }
      }
    }
    ?>

    <form  method="post">
      <input required type="text" name="tripName" placeholder="Trip Name">
      <input type="submit" name="createTrip" class="button" value="Create Trip"/>
    </form>
  </div>

</body>

</html>