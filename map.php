<?php
include 'includes/functions.php';
include 'includes/dbconnect.php';
session_start();
$json = createMapPoisJson($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
    integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
  <link rel="stylesheet" href="styles/style.css" />
  <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
    integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <title>Trip Recommender</title>
  <script>
    var data = '<?php echo $json; ?>';
  </script>
  <?php
  $jsonPoiList = populateSavedPois($link);
  ?>
  <script>var phpPoi = '<?php echo $jsonPoiList ?>';</script>
</head>

<body>
  <?php include 'includes/homebar.php' ?>
  <div id="itinerary">
    <ul id="poi" data-starttime='00:00'></ul>
    <input type="submit" value="Save" id="save" onclick="return feedback();" />
    <script>
      function feedback() {
        alert("Trip data Entered!");
        return true;
      }
    </script>
  </div>
  <div id="map"></div>
  <script src="scripts/mapScript.js" defer>
  </script>
</body>

</html>