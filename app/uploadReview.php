<?php 
    require_once "includes/dbconnect.php";
require_once('vendor/autoload.php');
    $client = new \GuzzleHttp\Client();

    $api_id = $link->query('SELECT id, API_ID FROM POIs')->fetch_all();
    foreach ($api_id as $id) {
      sleep(1);
      $response = $client->request('GET', 'https://api.yelp.com/v3/businesses/' . $id[1] . '/reviews?limit=20&sort_by=yelp_sort', [
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
       $sql = "UPDATE POIs SET reviews = '$review' WHERE id = '$id[0]'";
        if ($link->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $link->error;
        }
      }
      //push review to database here ********** this could be up to three reviews concat. together


?>