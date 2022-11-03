<?php 
session_start();

// foreach ($_POST as $key => $value){
//     echo $key;
//     foreach($value as $k => $v){
//         echo $k . ' ' . $v;
//     }

// }

//var_dump($_POST);


//echo json_decode($_POST["data"]);
//$postbody = $request->json()->all();
//echo "{ \"userId\": " . $postbody["randomAnswer"] . " }"

 //    $username1 = isset($_POST["username"]) ? $_POST["username"] : '';
    
//      $password1 = isset($_POST["password"]) ? $_POST["password"] : '';

// echo $username1;
// echo $password1;

// if (isset($_GET['var_PHP_data'])) {
//     echo $_GET['var_PHP_data'];
//   }else{
//       echo "nothing";
//   }
  
$uid = $_POST['userID'];
echo $uid;
var_dump($_POST);




?>