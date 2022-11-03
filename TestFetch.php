<?php

require_once "dbconnect.php";
$results= $link->query('SELECT * FROM POIs;')->fetch_all();

$data = array();
foreach ($results as $row) {
    $Lat = $row[0];
    $Lng = $row[1];
    $Category = $row[2];
    $id = $row[3];
    $address = $row[4];
    $phone = $row[5];
    $name = $row[6];
    $rating = $row[7];
    $num_ratings = $row[8];
    $data[] = array($Lat, $Lng, $Category, $id, $address, $phone, $name, $rating, $num_ratings);
}
 $json = json_encode(array("data" => $data));
echo "<script>
 function createItineraryJson() {

  let dayString = `{ "userId": ${1}, "tripId": ${1}, "tripName": "${document.getElementById('name').value.replace(/[^a-zA-Z0-9 ]/g, "")}", "pois": [`
  let pois = [...document.getElementsByClassName('draggable')];
  pois.forEach(poi => {
      dayString += `{"poiId": ${poi.className.split(' ')[1]},"poiName": "${poi.textContent.slice(0, -1)}","startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
  });
  dayString = dayString.slice(0, -1)
  dayString += ']}'
  console.log(dayString)
</script>";

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
      integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14="
      crossorigin=""
    />
    <link rel="stylesheet" href="style.css" />
    <script
      src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
      integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s="
      crossorigin=""
    ></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <title>Trip Recommender</title>
    <script>
        var data ='<?php echo $json; ?>';
    </script>
  </head>
  <body>
    <ul>
      <li class="homeBar"><a href="trips.html">Trips</a></li>
      <li class="homeBar"><a href="TestFetch.php">Current Trip</a></li>
      <li class="homeBar"><a href="account.php">Account</a></li>
      <li class="homeBar"><a onclick="loadItinerary()">TEMPERARY LOAD</a></li>
    </ul>
    <label id="itineraryName"><input type="text" title="name" placeholder="Trip Name" id="name"></label>
    <div id="itinerary">
      <ul id="poi" data-starttime='00:00'></ul>
      <input type="submit" value="Save" onclick="createItineraryJson()" />
    </div>
    <div id="map"></div>
    <script src="script.js" defer>
    </script>
  </body>
</html> 
<?php
if(array_key_exists('createItinerary', $_POST)){
    uploadData();
}
function uploadData(){
  $tripData = $_GET['tripData'];
  $data = json_decode($tripData, true);
  var_dump($data);
 
    foreach ($data as $row) {
    //get the POI details
    $userID = $row['userId'];
    $tripID = $row['tripId'];
    $tripName = $row['tripName'];
    $POI_ID = $row['pois']['poiId'];
    $POI_startTime = $row['pois']['startTime'];
    $POI_endTime = $row['pois']['endTime'];
 
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
}
?>
