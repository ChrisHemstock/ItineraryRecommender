<?php

// overpass query

// collecting results in JSON format

// "true" to get PHP array instead of an object
$amenity = $_POST["amenity"];
$overpass = "http://overpass-api.de/api/interpreter?data=[out:json];area(3600046663)->.searchArea;(node['amenity'=$amenity](area.searchArea););out;";
$html = file_get_contents($overpass);
$result = json_decode($html, true); 

// elements key contains the array of all required elements
$data = $result['elements'];
echo $html;

foreach($data as $key => $row) {

    // latitude
    $lat = $row['lat'];

    // longitude
    $lng = $row['lon'];
}



?>

<!doctype html>
    
<html lang="en">

<form action="TestRequest.php" method="post">
    
    <div class="form-group"> 
        <label for="amenity">Amenity</label> 
    <input type="text" class="form-control" id="amenity"
        name="amenity" >    
    </div>

    <button type="submit" class="btn btn-primary">
        Submit
    </button> 

    
</form> 


<?php

echo $lat;
echo $lng;
?>

</html>

