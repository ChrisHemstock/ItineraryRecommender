<?php
    require_once "includes/dbconnect.php";
    session_start();
    $userID = $_SESSION["id"];

    //Takes an array of POI IDs and a position and echos the html for that id
    //When the button is clicked the next poi is echoed
    //Also removes all markup within the div #choice
    function getPoiInfo($link, $ids, $pos, $likes) {
        $id = $ids[$pos];
        $results = $link->query('SELECT name, image_url, Lat, Lng FROM pois WHERE API_ID = "' . $id . '";')->fetch_all();
        $leafletLink = "'http://www.openstreetmap.org/copyright'";
        foreach ($results as $row) {
            $name = $row[0];
            $image_url = $row[1];
            $Lat = $row[2];
            $Lng = $row[3];
            echo '<div id="' . $id . '">
                    <h1 class=' . $id . '>' . $name . '</h1>
                    <form method="post">
                        <button type="submit" name="Like' . $pos . '" value="' . $likes . ' ' . $id . '">Like</button>
                        <button type="submit" name="Dislike' . $pos . '" value="' . $likes . '">Dislike</button>
                        <div id="choice_map"></div>
                        <script>                  
                        var map = L.map("choice_map").setView([' . $Lat . ', ' . $Lng . '], 13);
                        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            maxZoom: 25,
                            attribution: "&copy; <a href= ' . $leafletLink . '>OpenStreetMap</a>"
                        }).addTo(map);
                        var marker = L.marker([' . $Lat . ', ' . $Lng . ']).addTo(map);
                        </script>
                        <style> 
                        #choice_map { height: 350px; width: 300px; }
                        </style>
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
     <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <title>POI Survey</title>
</head>
<body>
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
            $sqlClear = "DELETE FROM likes WHERE userID = $userID";
            $stmt = $sqlClear;
                if (mysqli_query($link, $sqlClear)) {
                    $interestDataUpdated = true;
                } else {
                    echo "ERROR: Hush! Sorry $sqlClear. "
                        . mysqli_error($link);
                }
            $start = false;
            if(isset($_POST['Like' . $i])) {
                $likes = $_POST['Like' . $i];
            } else {
                $likes = $_POST['Dislike' . $i];
            }

            $poi_ids = explode(" ", $likes);
            var_dump($poi_ids);
            var_dump($likes);


        //var_dump($sqlClear);
        foreach ($poi_ids as $poi_id) {
            if ($poi_id != "") {
                $sql2 = "INSERT INTO likes(userID, POI_ID)
                VALUES ('$userID','$poi_id') 
                ON DUPLICATE KEY UPDATE 
                POI_ID = '$poi_id'";
                $stmt = $sql2;
                if (mysqli_query($link, $sql2)) {
                    $interestDataUpdated = true;
                } else {
                    echo "ERROR: Hush! Sorry $sql2. "
                        . mysqli_error($link);
                }
            }
        }
            header("Location: account.php");
            exit();
        }

        //starts the survey
        if($start) {
            getPoiInfo($link, $ids, 0, $likes); 
        }
    ?>
    
    </div>
</body>
</html>