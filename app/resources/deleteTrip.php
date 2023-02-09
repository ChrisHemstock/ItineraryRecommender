<?php
require_once "../includes/dbconnect.php";
session_start();

if (isset($_POST['tripID'])) {
    $tripID = $_POST['tripID'];

    //delete all the pois under that trip id
    $deleteSQL = "DELETE FROM trips WHERE id = '$tripID'";
    if ($link->query($deleteSQL) === TRUE) {
        echo "New record deleted successfully";
    } else {
        echo "Error: " . $deleteSQL . "<br>" . $link->error;
    }
 
} else {
  echo "Noooooooob";
}
?>