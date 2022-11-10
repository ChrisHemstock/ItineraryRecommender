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
  }
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
    </div>
  </body>
</html>
