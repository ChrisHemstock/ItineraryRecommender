<?php
   
    $servername = "localhost"; 
    $username = "root"; 
    $password = "";
   
    $database = "TripRecommender";
   
     // Create a connection 
     $link = mysqli_connect($servername, 
         $username, $password, $database);
   
    if($link == false) {
        die("Error". mysqli_connect_error()); 
    } 
?>