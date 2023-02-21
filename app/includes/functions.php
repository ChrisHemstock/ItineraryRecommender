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
        $api_id = $row[9];
        $address = $row[4];
        $phone = $row[5];
        $name = $row[6];
        $rating = $row[7];
        $num_ratings = $row[8];
        $data[] = array($Lat, $Lng, $Category, $api_id, $address, $phone, $name, $rating, $num_ratings);
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
        //var_dump($trip[0]);
        if ($trip[0] == $_GET['trip']) {
            
            //If it is a valid URL trip id then get all the pois under that trip and populate the poi list
            $poiList = $link->query('SELECT pois.API_ID, startTime, endTime, name FROM trippois, pois WHERE trippois.API_ID = pois.API_ID and trippois.tripID = ' . $trip[0] . ';')->fetch_all();
            var_dump('here');
            $jsonPoiList = json_encode($poiList);
            break;
        }
    }
    return $jsonPoiList;
}

function cosineSimilarity($list1, $list2) {
    $dotSum = 0;
    $mag1 = 0;
    $mag2 = 0;
    // for($i = 0; $i < count($list1); $i++) {
    //         $dotSum += $list1[$i] * $list2[$i];
    //         $mag1 += $list1[$i] ** 2;
    //         $mag2 += $list2[$i] ** 2;
    // }

    foreach($list1 as $word => $value) {
        $dotSum += $list1[$word] * $list2[$word];
        $mag1 += $list1[$word] ** 2;
        $mag2 += $list2[$word] ** 2;
    }



    if($mag1 == 0 || $mag2 == 0) {
        return -2;
    }

    return $dotSum/(sqrt($mag1) * sqrt($mag2));
}

function deleteLikes($link, $userID) {
    $sqlClear = "DELETE FROM likes WHERE userID = $userID";
    $stmt = $sqlClear;
    if (mysqli_query($link, $sqlClear)) {
        $interestDataUpdated = true;
    } else {
        echo "ERROR: Hush! Sorry $sqlClear. "
            . mysqli_error($link);
    }
}

function addLikes($link, $userID, $poiID) {
    $insert = "INSERT INTO likes(userID, POI_ID) VALUES ('" . $userID . "','" . $poiID . "')";
    $stmt = $insert;
    if (mysqli_query($link, $insert)) {

        $interestDataUpdated = true;
    } else {
        echo "ERROR: Hush! Sorry $insert. "
            . mysqli_error($link);
    }
}

function topPoiJson($link, $amount) {
    $top = $link->query('SELECT id FROM pois ORDER BY num_ratings DESC LIMIT ' . $amount);
    foreach ($top as $values) {
      $topArray[$values['id']] = 1;
    }
    return json_encode($topArray);
}
?>