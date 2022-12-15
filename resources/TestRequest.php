<?php
//connect to mysql db
require_once "../includes/dbconnect.php";
session_start();
$userID = $_SESSION["id"];

//read the json file contents

$jsondata = file_get_contents('poiData.json');

//convert json object to php associative array
$data = json_decode($jsondata, true);

foreach ($data["businesses"] as $row) {
  //get the POI details
  $name = $row['name'];
  $Category = $row['categories'][0]['alias'] . " " . $row['categories'][1]['alias'] . " " . $row['categories'][2]['alias'];
  $rating = $row['rating'];
  $num_ratings = $row['review_count'];
  $address = $row['location']['display_address'][0];
  $Lng = $row['coordinates']['longitude'];
  $Lat = $row['coordinates']['latitude'];
  $phone = $row['phone'];
  $API_KEY = $row['API_KEY'];
  //insert into mysql table
 $sql = "INSERT INTO POIs(name, Category, rating, num_ratings, address, Lng, Lat, phone)
    VALUES('$name', '$Category', '$rating', '$num_ratings', '$address', '$Lng', '$Lat', '$phone', '$API_KEY')";
  if ($link->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}



?>