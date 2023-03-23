<?php
/**
 * generates a JSON that holds contains all the pois
 * used to populate the map with markers
 */
function createMapPoisJson($link) {
    $results = $link->query('SELECT * FROM POIs;')->fetch_all();

    $data = array();
    foreach ($results as $row) {
        $lat = $row[0];
        $lng = $row[1];
        $category = $row[2];
        $api_id = $row[9];
        $address = $row[4];
        $phone = $row[5];
        $name = $row[6];
        $rating = $row[7];
        $num_ratings = $row[8];
        $url = $row[12];
        $data[] = array($lat, $lng, $category, $api_id, $address, $phone, $name, $rating, $num_ratings, $url);
    }
    return json_encode(array("data" => $data));
}

/**
 * Returns a JSON of all the POIs that are saved
 */
function populateSavedPois($link) {
    $user_id = $_SESSION["id"];
    //Verifies that the url tripID matches an ID in trips and under the users ID
    $trips = $link->query('SELECT id FROM trips WHERE userID = ' . $user_id . ';')->fetch_all();
    $json_poi_list = json_encode(array());
    foreach ($trips as $trip) {
        if ($trip[0] == $_GET['trip']) {
            //If it is a valid URL trip id then get all the pois under that trip and populate the poi list
            $poi_list = $link->query('SELECT pois.API_ID, startTime, endTime, name, pois.url FROM trippois, pois WHERE trippois.API_ID = pois.API_ID and trippois.tripID = ' . $trip[0] . ';')->fetch_all();
            $json_poi_list = json_encode($poi_list);
            break;
        }
    }
    return $json_poi_list;
}

/**
 * Deletes all entries from the likes table that are under the users id
 */
function deleteLikes($link, $user_id) {
    $sql_clear = "DELETE FROM likes WHERE userID = $user_id";
    $stmt = $sql_clear;
    if (mysqli_query($link, $sql_clear)) {
        echo 'Success!';
    } else {
        echo "ERROR: Hush! Sorry $sql_clear. "
            . mysqli_error($link);
    }
}

/**
 * Insert a new entry into the likes table
 */
function addLikes($link, $user_id, $api_id) {
    //

    $insert = "INSERT INTO likes(userID, API_ID) VALUES ('" . $user_id . "','" . $api_id . "')";
    $stmt = $insert;
    if (!(mysqli_query($link, $insert))) {
        echo "ERROR: Hush! Sorry $insert. "
            . mysqli_error($link);
    }
}

/**
 * Returns the top $amount of the highest rated pois (used to recommend when there are no recommendations)
 */
function topPoiJson($link, $amount) {
    $top = $link->query('SELECT API_ID FROM pois ORDER BY num_ratings DESC LIMIT ' . $amount);
    foreach ($top as $values) {
      $top_array[$values['API_ID']] = 1;
    }
    return json_encode($top_array);
}
?>