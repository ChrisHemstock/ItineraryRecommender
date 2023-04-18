<?php
include_once 'includes/functions.php';
include_once 'includes/dbconnect.php';
include_once 'resources/reviewRequest.php';
require_once(__DIR__ . '/../vendor/autoload.php');

session_start();
set_time_limit(360);

$user_id = $_SESSION["id"];
$all_pois_json = createMapPoisJson($link);
$saved_poi_json = populateSavedPois($link);
$recommender = new Recommender($link);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

  <link rel="stylesheet" href="styles/nav-style.css">
  <script src="scripts/nav-script.js" defer></script>

  <title>Trip Recommender</title>
  
  <script>
    //creates javascript variables from php variables
    var allPoisJson = '<?php echo $all_pois_json; ?>';
    var savedPoiJson = '<?php echo $saved_poi_json ?>';
    var recommendations = '<?php echo $recommender->get_recommendations($user_id, 5)?>';
  </script>
</head>

<body>
  <?php include 'includes/homebar.php' ?>
  <div id="map-page">

  
  <h1><?php echo $_GET['name'];?></h1>
  <div id="itinerary">
    
    <input type="submit" value="Save" id="save" onclick="return feedback('Trip data Entered!');" /><!-- 
    --><input type="button" value = "Recommendations" onclick="return displayRecommendations(getRecommendationArray(recommendations, allPoisJson, getItineraryApis(), 5));"/><!-- 
    --><label for="showTripOnly">Show Trip Only<input type="checkbox" name = "Show Trip Only" id = "showTripOnly"/></label><!-- 
    --><ul id="poi" data-starttime='00:00'></ul>

  </div> <!--
--><div id="map"></div>
  <div id="recommendations">
      <ul id="poiRecommendations"></ul>
  </div>
  <br>
  <br>
  </div>
  </div><!-- This div ends the nav bar  -->
</body>

</html>