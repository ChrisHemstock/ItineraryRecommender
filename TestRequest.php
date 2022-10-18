<?php
    //connect to mysql db
    require_once "dbconnect.php";

    //read the json file contents


    $someJSON = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';

  // Convert JSON string to Array
  //$someArray = json_decode($someJSON, true);
  //print_r($someArray);        // Dump all data of the Array
  //echo $someArray[0]["name"]; // Access Array data

    $jsondata = file_get_contents('poiData.json');
    
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
    //$price = $row['price'];
    //insert into mysql table
    $sql = "INSERT INTO POIs(name, Category, rating, num_ratings, address, Lng, Lat, phone)
    VALUES('$name', '$Category', '$rating', '$num_ratings', '$address', '$Lng', '$Lat', '$phone')";
    if ($link->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
 
    
      
?>