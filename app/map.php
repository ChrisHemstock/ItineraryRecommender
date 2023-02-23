<?php
include_once 'includes/functions.php';
include_once 'includes/dbconnect.php';
include_once 'resources/reviewRequest.php';
require_once(__DIR__ . '\..\vendor\autoload.php');
session_start();
set_time_limit(360);
$userID = $_SESSION["id"];
$json = createMapPoisJson($link);
$jsonPoiList = populateSavedPois($link);
$recommender = new Recommender($link);
// $recommender->update_recommendations(5, $userID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
  <link rel="stylesheet" href="styles/style.css" />

  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script src="scripts/mapFunctions.js"></script>
  <script src="scripts/mapScript.js" defer></script>

  <title>Trip Recommender</title>
  
  <script>
    //creates javascript variables from php variables
    var data = '<?php echo $json; ?>';
    var phpPoi = '<?php echo $jsonPoiList ?>';
    var recommendations = '<?php echo $recommender->get_recommendations($userID)?>';
  </script>
</head>

<body>
  <?php include 'includes/homebar.php' ?>
  <div id="itinerary">
    <?php echo "<h2>". $_GET['name'] ."</h2>";?>
    <ul id="poi" data-starttime='00:00'></ul>
    <input type="submit" value="Save" id="save" onclick="return feedback();" />
    <input type="button" value = "Make Recommendations" onclick="return displayRecommendations(recommendations, data);"/>
  </div>
  <div id="map"></div>
  <div id="recommendations">
      <ul id="poiRecommendations"></ul>
  </div>
</body>

</html>