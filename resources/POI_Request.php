<!DOCTYPE html>
<html>
<body>

<h2>POI Request</h2>

<form method= "post">
  <label for="searchTermLabel">Enter Search Term to Get POIs:</label><br>
  <input type="text" id="searchTerm" name="searchTerm"><br>
  <input type="submit" name="Submit" value="Submit">
</form> 


</body>
</html>


<?php

if(isset($_POST["Submit"])){
    makeRequest();
}
function makeRequest(){
    require_once "includes/dbconnect.php";
    session_start();
require_once('vendor/autoload.php');
$client = new \GuzzleHttp\Client();


$searchTerm = $_POST["searchTerm"];

$response = $client->request('GET', 'https://api.yelp.com/v3/businesses/search?location=Indianapolis&term=' . $searchTerm, [
    'headers' => [
      'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
      'accept' => 'application/json',
    ],
  ]);
  
  //echo $response->getBody();

//read the json file contents

$jsondata = $response->getBody();

//convert json object to php associative array
$data = json_decode($jsondata, true);

foreach ($data["businesses"] as $row) {
  //get the POI details
  $name = $row['name'];
  $Category = $row['categories'][0]['alias'];
  $rating = $row['rating'];
  $num_ratings = $row['review_count'];
  $address = $row['location']['display_address'][0];
  $Lng = $row['coordinates']['longitude'];
  $Lat = $row['coordinates']['latitude'];
  $phone = $row['phone'];
  $API_KEY = $row['id'];
  //insert into mysql table
 $sql = "INSERT INTO POIs(name, Category, rating, num_ratings, address, Lng, Lat, phone, API_ID)
    VALUES('$name', '$Category', '$rating', '$num_ratings', '$address', '$Lng', '$Lat', '$phone', '$API_KEY')";
  if ($link->query($sql) === TRUE) {
    $created = true;
  } else {
      $created = false;
    //echo "Error: " . $sql . "<br>" . $link->error;
  }
}

}

if($created = true){
    echo "<h2> POIs successfully added! </h2>";
}

?>