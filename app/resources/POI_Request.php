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
    require_once "../includes/dbconnect.php";
    session_start();
require_once('../vendor/autoload.php');
$client = new \GuzzleHttp\Client();


$searchTerm = $_POST["searchTerm"];

$response = $client->request('GET', 'https://api.yelp.com/v3/businesses/search?location=Indianapolis&term=' . $searchTerm, [
    'headers' => [
      'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
      'accept' => 'application/json',
    ],
  ]);
  
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
  $image_url = $row['image_url'];
  $url = $row['url'];
  $price = $row['price'];

  //insert into mysql table
 $sql = "INSERT INTO POIs(name, Category, rating, num_ratings, address, Lng, Lat, phone, API_ID, image_url, url, price)
    VALUES('$name', '$Category', '$rating', '$num_ratings', '$address', '$Lng', '$Lat', '$phone', '$API_KEY', '$image_url', '$url', '$price')";
  if ($link->query($sql) === TRUE) {
    $created = true;
    $response = $client->request('GET', 'https://api.yelp.com/v3/businesses/' . $API_KEY . '/reviews?limit=20&sort_by=yelp_sort', [
      'headers' => [
        'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
        'accept' => 'application/json',
      ],
    ]);
    $data = json_decode($response->getBody(), true);

    $review = '';

    foreach($data["reviews"] as $row) {
      $text = strtolower(preg_replace('/[^A-Za-z0-9\- ]/', '', $row['text']));
      $review .= ' ' . $text;
    }
    
      //insert into mysql table
     $sql = "UPDATE POIs SET reviews = '$review' WHERE API_ID = '$API_KEY'";
      if ($link->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $link->error;
      }
  } else {
      $created = false;
  }
}

}

if($created = true){
    echo "<h2> POIs successfully added! </h2>";
}

?>