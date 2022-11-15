

<?php
  require_once "dbconnect.php";
  session_start();
  $userID = $_SESSION["id"];

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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Trip Recommender</title>
    <script>
        var data ='<?php echo $json; ?>';
    </script>
    <?php
      //Verifies that the url tripID matches an ID in trips and under the users ID
      $trips = $link->query('SELECT id FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
      $jsonPoiList = json_encode(array());
      foreach($trips as $trip) {
        if($trip[0] == $_GET['trip']) {
          //If it is a valid URL trip id then get all the pois under that trip and populate the poi list
          $poiList = $link->query('SELECT POI_ID, startTime, endTime, name FROM trippois, pois WHERE trippois.POI_ID = pois.id and trippois.tripID = ' . $trip[0] . ';')->fetch_all();
          $jsonPoiList = json_encode($poiList);
          break;
        }
      }
    ?>
    <script>var phpPoi = '<?php echo $jsonPoiList?>';</script>
  </head>
  <body>
    <ul>
      <li class="homeBar"><a href="trips.php">Trips</a></li>
      <li class="homeBar"><a href="TestFetch.php">Current Trip</a></li>
      <li class="homeBar"><a href="account.php">Account</a></li>
      <li class="homeBar"><a onclick="loadItinerary()">TEMPERARY LOAD</a></li>
    </ul>
    <label id="itineraryName"><input type="text" title="name" placeholder="Trip Name" id="name"></label>
    <div id="itinerary">
      <ul id="poi" data-starttime='00:00'></ul>
      <input type="submit" value="Save" id="save" onclick="createItineraryJson()" />
    </div>
    <div id="map"></div>
    <script src="script.js" defer>
    </script>
  </body>
</html> 
