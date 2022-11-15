<?php
  require_once "dbconnect.php";
  session_start();
  $userID = $_SESSION["id"];

  $results= $link->query('SELECT * FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
  $data = array();
  foreach ($results as $row) {
    $id = $row[0];
    $userID = $row[1];
    $name = $row[2];
    $data[] = array($id, $userID, $name);
    echo 'HERE';
  }
  echo 'I ran';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Trips</title>
  </head>
  <body>
    <ul>
      <li class="homeBar"><a href="trips.html">Trips</a></li>
      <li class="homeBar"><a href="TestFetch.php">Current Trip</a></li>
      <li class="homeBar"><a href="account.php">Account</a></li>
    </ul>
    <div id="trips">
      <h1>Saved Trips</h1>
      <ul>
        <?php
          foreach ($data as $row) {
            echo '<li>
                    <a href="TestFetch.php?trip=' . $row[0] . '">' . $row[2] . '</a>
                    <span class="close">X</span>
                  </li>';
          }
        ?>
      </ul>

      <?php
        if(isset($_POST['createTrip'])) {
          if(isset($_POST['tripName'])) {
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
  
      <form method="post">
        <input type="text" name="tripName" placeholder="Trip Name">
        <input type="submit" name="createTrip" class="button" value="Create Trip" />
      </form>
    </div>

  </body>
</html>
