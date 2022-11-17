<?php
require_once "../includes/dbconnect.php";
session_start();


if (isset($_POST['tripData'])) {
  $data = $_POST['tripData'];
  //var_dump($data);
  $data = json_decode($data, true);
  $userID = $_SESSION["id"];
  $tripID = $data['tripId'];

  $trips = $link->query('SELECT id FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
  foreach ($trips as $trip) {
    if ($trip[0] == $tripID) {
      var_dump($data);

      $deleteSQL = "DELETE FROM tripPOIs WHERE tripID = '$tripID'";
      if ($link->query($deleteSQL) === TRUE) {
        echo "New record deleted successfully";
      } else {
        echo "Error: " . $deleteSQL . "<br>" . $link->error;
      }

      foreach ($data['pois'] as $row) {
        //get the POI details
        $POI_ID = $row['poiId'];
        $POI_startTime = $row['startTime'];
        $POI_endTime = $row['endTime'];

        $sql2 = "INSERT INTO tripPOIs(POI_ID, startTime, endTime, tripID)
          VALUES('$POI_ID', '$POI_startTime', '$POI_endTime', '$tripID')";
        if ($link->query($sql2) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql2 . "<br>" . $link->error;
        }
      }
      break;
    }
  }
} else {
  echo "Noooooooob";
}
?>