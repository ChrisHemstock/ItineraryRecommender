<?php

require_once "dbconnect.php";


$results= $link->query('SELECT * FROM POIs;')->fetch_all();

$data = array();
foreach ($results as $row) {
    $Lat = $row[0];
    $Lng = $row[1];
    $Category = $row[2];
    $id = $row[3];
    $address = $row[4];
    $phone = $row;
    $name = $row;
    $rating = $row[7];
    $num_ratings = $row[8];
    $data[] = array($Lat, $Lng, $Category, $id, $address, $phone, $name, $rating, $num_ratings);
}
 $json = json_encode(array("data" => $data));

return $json;

?>