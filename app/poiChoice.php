<?php
    require_once "includes/dbconnect.php";
    include_once 'resources/reviewRequest.php';
    require_once(__DIR__ . '/../vendor/autoload.php');
    session_start();
    set_time_limit(480);
    $userID = $_SESSION["id"];
    

    //Takes an array of POI IDs and a position and echos the html for that id
    //When the button is clicked the next poi is echoed
    //Also removes all markup within the div #choice
    function getPoiInfo($link, $ids, $pos, $likes) {
        $id = $ids[$pos];
        //US 11: Add picture to POI choice
        $results = $link->query('SELECT name, image_url, rating, url, Lat, Lng  FROM pois WHERE API_ID = "' . $id . '";')->fetch_all();
        foreach ($results as $row) {
            $name = $row[0];
            $image_url = $row[1];
            $rating = $row[2];
            $url = $row[3];
            $Lat = $row[4];
            $Lng = $row[5];
            echo '<div id="' . $id . '">
                    <h1 class=' . $id . '>' . $name .': ' . $rating . '&#9734; </h1>
                    <form method="post">
                        <button type="submit" name="Like' . $pos . '" value="' . $likes . ' ' . $id . '">&#128077;</button>
                        <button type="submit" name="Dislike' . $pos . '" value="' . $likes . '">&#128078;</button>
                        <br> <br>
                        <p id = "poi_choice_map"><a href= ' . $url .' target="_blank" ><img src='. $image_url . '></a></p><!--
                        --><div id="choice_map"></div>
                        <script>                  
                        var map = L.map("choice_map",{
                            center: ['. $Lat . ', '. $Lng . '],
                            zoom: 15
                           });
                        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            maxZoom: 25,
                            attribution: "&copy; <a href= http://www.openstreetmap.org/copyright>OpenStreetMap</a>"
                        }).addTo(map);
                        setInterval(function () {
                            map.invalidateSize();
                         }, 100);
                        var marker = L.marker([' . $Lat . ', ' . $Lng . ']).addTo(map);
                        </script>
                        
                    </form>
                  </div>';
        }
    }
?>

   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <link rel="stylesheet" href="styles/nav-style.css">
     <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <title>POI Survey</title>
</head>
<body>
    <br>
    <br>
    <div id="choice">
    <?php
        $ids = ['F5n57w6RaOCAB4bivNSs8A', 'E5cecsuxC11xDO9E3c93lA', '6x6rR-SErwOo3xF2AzXVHA', 'VEGvvazGmbukHqZyToVvYw'];
        $likes = '';
        $start = true;
        
        for ($i = 0; $i < (sizeof($ids) - 1); $i++) {
            if(isset($_POST['Like' . $i]) or isset($_POST['Dislike' . $i])) {
                $start = false;
                if(isset($_POST['Like' . $i])) {
                    $likes = $_POST['Like' . $i];
                } else {
                    $likes = $_POST['Dislike' . $i];
                }
                getPoiInfo($link, $ids, $i + 1, $likes);
                break;
            }
            
        }

        //adds the liked poi keys to the database
        if(isset($_POST['Like' . (sizeof($ids) - 1)]) or isset($_POST['Dislike' . (sizeof($ids) - 1)])) {
            $start = false;
            if(isset($_POST['Like' . $i])) {
                $likes = $_POST['Like' . $i];
            } else {
                $likes = $_POST['Dislike' . $i];
            }

            $api_ids = explode(" ", $likes);
            //var_dump($likes);

            $sqlClear = "DELETE FROM likes WHERE userID = $userID AND 
            (API_ID = '$ids[0]'
            OR API_ID = '$ids[1]'
            OR API_ID = '$ids[2]' 
            OR API_ID = '$ids[3]')";
            //var_dump($sqlClear);
            $stmt = $sqlClear;
            if (mysqli_query($link, $sqlClear)) {
                $interestDataUpdated = true;
            } else {
                echo "ERROR: Hush! Sorry $sqlClear. "
                    . mysqli_error($link);
            }
            
            foreach ($api_ids as $api_id) {
                
                
                if ($api_id != "") {
                    $sql2 = "INSERT INTO likes(userID, API_ID)
                    VALUES ('$userID','$api_id') 
                    ON DUPLICATE KEY UPDATE 
                    API_ID = '$api_id'";
                    $stmt = $sql2;
                    if (mysqli_query($link, $sql2)) {
                        $interestDataUpdated = true;
                    } else {
                        echo "ERROR: Hush! Sorry $sql2. "
                            . mysqli_error($link);
                    }
                }
            }
             $recommender = new Recommender($link);
             $recommender->update_recommendations(15, $userID);
            header("Location: account.php");
            exit();
        }

        //starts the survey
        if($start) {
            getPoiInfo($link, $ids, 0, $likes); 
        }
    ?>
    
    </div>
    <br>
    <br>
</body>
</html>