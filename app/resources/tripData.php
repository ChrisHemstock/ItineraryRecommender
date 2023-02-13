<?php
require_once "../includes/dbconnect.php";
session_start();

//condition based on if trip data has been set
if (isset($_POST['tripData'])) {
  $data = $_POST['tripData'];
  //var_dump($data);
  $data = json_decode($data, true);
  $userID = $_SESSION["id"];
  $tripID = $data['tripId'];

  //Get all the trips under the user and loop through the trips
  $trips = $link->query('SELECT id FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
  foreach ($trips as $trip) {
    //if the trip is the desired trip then do stuff
    if ($trip[0] == $tripID) {
      //var_dump($data);

      //delete all the pois under that trip id
      $deleteSQL = "DELETE FROM tripPOIs WHERE tripID = '$tripID'";
      if ($link->query($deleteSQL) === TRUE) {
        echo "New record deleted successfully";
      } else {
        echo "Error: " . $deleteSQL . "<br>" . $link->error;
      }

      //add all the pois in the json back to the database
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