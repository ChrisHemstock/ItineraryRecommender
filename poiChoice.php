<?php
    require_once "includes/dbconnect.php";
    session_start();
    $userID = $_SESSION["id"];

    
    //Get trips for current user
    $results = $link->query('SELECT * FROM trips WHERE userID = ' . $userID . ';')->fetch_all();

    //id, name
    $data = array();
    foreach ($results as $row) {

    }

    //Takes an array of POI IDs and a position and echos the html for that id
    //When the button is clicked the next poi is echoed
    //Also removes all markup within the div #choice
    function getPoiInfo($link, $ids, $pos) {
        $id = $ids[$pos];
        $results = $link->query('SELECT name FROM pois WHERE id = ' . $id . ';')->fetch_all();

        foreach ($results as $row) {
            $name = $row[0];
            // echo '<script>
            //         document.getElementById("choice").innerHTML = "";
            //         </script>';
            echo '<div id="' . $id . '">
                    <h1 class=' . $id . '>' . $name . '</h1>
                    <form method="post">
                        <button type="submit" name="Like" value="' . $pos . '">Like</button>
                        <button type="submit" name="Dislike" value="' . $pos . '">Dislike</button>
                        <img src="" alt="">
                    </form>
                  </div>';
        }
        if(isset($_POST['Like' . $id]) or isset($_POST['Dislike' . $id])) {
            echo $id . '<br>';
            
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
    <title>Select Interests</title>
</head>
<body>
    <div id="choice">
    <?php
        $ids = [1119, 1198, 1028, 1062];
        $start = true;
        for ($i = 0; $i < sizeof($ids); $i++) {
            echo 'here:' . $i . '<br>';
            if(isset($_POST['Like' . $i]) or isset($_POST['Dislike' . $i])) {
                $start = false;
                getPoiInfo($link, $ids, $i + 1);
                break;
            }
        }
        if($start) {
            echo 'here:-1<br>';
            getPoiInfo($link, $ids, 0); 
        }
    ?>
    
    </div>
</body>
</html>