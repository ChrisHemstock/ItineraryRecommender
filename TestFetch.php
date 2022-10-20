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
    $phone = $row;
    $name = $row;
    $rating = $row[7];
    $num_ratings = $row[8];
    $data[] = array($Lat, $Lng, $Category, $id, $address, $phone, $name, $rating, $num_ratings);
}
 $json = json_encode(array("data" => $data));

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
      let data = <?php $json = json_encode(array("data" => $data)); ?>
    ></script>
    <title>Trip Recommender</title>

<script>
var data ='<?php echo $json; ?>';
</script>

</body>
</html>
  </head>
  <body>
    <ul>
      <li class="homeBar"><a href="trips.html">Trips</a></li>
      <li class="homeBar"><a href="map.html">Current Trip</a></li>
      <li class="homeBar"><a href="account.php">Account</a></li>
      <li class="homeBar"><a onclick="loadItinerary()">TEMPERARY LOAD</a></li>
    </ul>
    <div id="itinerary">
      <div id="poi"></div>
      <input type="button" value="Add Day" onclick="addDay()" />
      <input type="submit" value="Save" onclick="createItineraryJson()" />
    </div>
    <div id="map"></div>
    <script src="script.js" defer> 
    </script>
  </body>
</html>

