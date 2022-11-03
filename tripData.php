<?php 
    require_once "dbconnect.php";
    session_start();
    $userID = $_SESSION["id"];

if(isset($_POST['tripData'])) {
    $data = $_POST['tripData'];
    //var_dump($json);

    $data = json_decode($data, true);

      //$tripData = $_GET['tripData'];
      //$data = json_decode($tripData, true);
      var_dump($data);
     
      $userID = $_SESSION["id"];
      $tripName = $data['tripName'];

      //insert into mysql table
      $sql = "INSERT INTO trips(userID, name) 
      VALUES('$userID', '$tripName')";
      if ($link->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $link->error;
        }

      $tripIdQuery = "SELECT MAX(id) FROM trips";
        if(mysqli_query($link, $tripIdQuery)){
          $response = @mysqli_query($link, $tripIdQuery);
          while($row = mysqli_fetch_array($response)){
            $tripID = $row['MAX(id)'];   
          }
        } else {
          echo "Error: " . $tripIdQuery . "<br>" . $link->error;
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
  } else {
    echo "Noooooooob";
  }
?>