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
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE);

function curlRequest($url){
  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "Authorization: Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx",
      "accept: application/json"
    ],
  ]);
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  }else{
  return $response;
}
}

if(isset($_POST["Submit"])){
  makeRequest();
}
function makeRequest(){
  require_once "../includes/dbconnect.php";
  session_start();
  require_once('../../vendor/autoload.php');
  
$searchTerm = $_POST["searchTerm"];
echo "<br>";


$response = curlRequest('https://api.yelp.com/v3/businesses/search?location=Indianapolis&term=' . $searchTerm);

//convert json object to php associative array
 $data = json_decode($response, true);

foreach ($data["businesses"] as $row) {
  //get the POI details
  $name = $row['name'];
  $nameSub = str_replace("'","",$name);
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
  $urlSub = substr($url, 0, strpos($url, "?"));
  $price = $row['price'];

  //insert into mysql table
 $sql = "INSERT INTO POIs(name, Category, rating, num_ratings, address, Lng, Lat, phone, API_ID, image_url, url, price)
    VALUES('$nameSub', '$Category', '$rating', '$num_ratings', '$address', '$Lng', '$Lat', '$phone', '$API_KEY', '$image_url', '$urlSub', '$price')
    ON DUPLICATE KEY UPDATE rating = '$rating', image_url = '$image_url'";

  $response2 = curlRequest('https://api.yelp.com/v3/businesses/' . $API_KEY . '/reviews?limit=20&sort_by=yelp_sort');
    $data2 = json_decode($response2, true);

    $review = '';

    foreach($data2["reviews"] as $row) {
      $text = strtolower(preg_replace('/[^A-Za-z0-9\- ]/', '', $row['text']));
      $review .= ' ' . $text;
    }
    
     $sql = "UPDATE POIs SET reviews = '$review' WHERE API_ID = '$API_KEY'";
      if ($link->query($sql) === TRUE) {
        echo "<b>" . $nameSub . "</b> successfully added or updated";
        echo "<br>";
      } else {
        echo "Error: " . $sql . "<br>" . $link->error;
}

}
}

?>