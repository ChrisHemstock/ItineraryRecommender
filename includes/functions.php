<?php
function createMapPoisJson($link)
{
    //require_once "includes/dbconnect.php";

    $userID = $_SESSION["id"];

    $results = $link->query('SELECT * FROM POIs;')->fetch_all();

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
    return json_encode(array("data" => $data));
}

function populateSavedPois($link)
{
    //require_once "includes/dbconnect.php";
    $userID = $_SESSION["id"];
    //Verifies that the url tripID matches an ID in trips and under the users ID
    $trips = $link->query('SELECT id FROM trips WHERE userID = ' . $userID . ';')->fetch_all();
    $jsonPoiList = json_encode(array());
    foreach ($trips as $trip) {
        if ($trip[0] == $_GET['trip']) {
            //If it is a valid URL trip id then get all the pois under that trip and populate the poi list
            $poiList = $link->query('SELECT POI_ID, startTime, endTime, name FROM trippois, pois WHERE trippois.POI_ID = pois.id and trippois.tripID = ' . $trip[0] . ';')->fetch_all();
            $jsonPoiList = json_encode($poiList);
            break;
        }
    }
    return $jsonPoiList;
}
?>