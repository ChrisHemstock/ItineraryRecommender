<?php

require_once "dbconnect.php";


$results= $link->query('SELECT * FROM POIs;')->fetch_all();

$data = array();
foreach ($results as $row) {
    $id = $row[0];
    $timestamp = $row[1];
    $name = $row[2];
    $description = $row[3];
    $description = $row[4];
    $description = $row[5];
    $description = $row[6];
    $description = $row[7];
    $description = $row[8];
    $data[] = array($id, $timestamp, $name, $description);
}
 $json = json_encode(array("data" => $data));

return $json;

?>