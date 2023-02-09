<?php

$servername = "localhost:80";
$username = "root";
$password = "";
$database = "ItineraryRecommender";

// Create a connection 
$link = mysqli_connect(
    $servername,
    $username,
    $password,
    $database
);

if ($link == false) {
    die("Error" . mysqli_connect_error());
}
?>