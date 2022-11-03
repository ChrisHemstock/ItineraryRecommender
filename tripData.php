<?php if(isset($_POST['tripData'])) {
    $data = $_POST['tripData'];
    //var_dump($json);

    $data = json_decode($data, true);

      //$tripData = $_GET['tripData'];
      //$data = json_decode($tripData, true);
      var_dump($data);
     
        foreach ($data as $row) {
        //get the POI details

        $userID = $_SESSION["id"];
        $tripName = $row['tripName'];
        $POI_ID = $row['pois']['poiId'];
        $POI_startTime = $row['pois']['startTime'];
        $POI_endTime = $row['pois']['endTime']
     
        //insert into mysql table
        $sql = "INSERT INTO trips(userID, name)
        VALUES($userID, '$name')";
        if ($link->query($sql) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
          $sql2 = "INSERT INTO tripPOIs(POI_ID, startTime, endTime, tripID)
          VALUES($POI_ID, '$POI_startTime', '$POI_endTime', '$tripID')";
          if ($link->query($sql2) === TRUE) {
              echo "New record created successfully";
            } else {
              echo "Error: " . $sql2 . "<br>" . $conn->error;
            }
        }
  } else {
    echo "Noooooooob";
  }
?>