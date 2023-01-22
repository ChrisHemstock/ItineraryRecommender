<?php
    require_once "includes/dbconnect.php";
    session_start();
    $userID = $_SESSION["id"];

    //Takes an array of POI IDs and a position and echos the html for that id
    //When the button is clicked the next poi is echoed
    //Also removes all markup within the div #choice
    function getPoiInfo($link, $ids, $pos, $likes) {
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
                        <button type="submit" name="Like' . $pos . '" value="' . $likes . ' ' . $id . '">Like</button>
                        <button type="submit" name="Dislike' . $pos . '" value="' . $likes . '">Dislike</button>
                        <img src="" alt="">
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
    <title>Select Interests</title>
</head>
<body>
    <div id="choice">
    <?php
        $ids = [1119, 1198, 1028, 1062];
        $likes = '';
        $start = true;
        for ($i = 0; $i < sizeof($ids) - 1; $i++) {
            echo 'here:' . $i . '<br>';
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
        if(isset($_POST['Like' . sizeof($ids) - 1]) or isset($_POST['Dislike' . sizeof($ids) - 1])) {
            $start = false;
            if(isset($_POST['Like' . $i])) {
                $likes = $_POST['Like' . $i];
            } else {
                $likes = $_POST['Dislike' . $i];
            }
            //Insert the id's in $likes into the database here
            //Likes is a string of id's seperated by spaces




            header("Location: account.php");
            exit();
        }
        if($start) {
            echo 'here:-1<br>';
            getPoiInfo($link, $ids, 0, $likes); 
        }
    ?>
    
    </div>
</body>
</html>