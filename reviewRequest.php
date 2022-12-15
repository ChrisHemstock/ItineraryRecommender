<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.yelp.com/v3/businesses/UFCN0bYdHroPKu6KV5CJqg/reviews?limit=20&sort_by=yelp_sort', [
  'headers' => [
    'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
    'accept' => 'application/json',
  ],
]);

//echo $response->getBody();
$data = $response->getBody();
$data = json_decode($response->getBody(), true);

$rake = rake($data["reviews"], 3);
$phrases = $rake->getPhrases();
foreach ($phrases as $phrase) {
  echo $phrase . '\n';
}

foreach ($data["reviews"] as $row) {
  //get the POI details
  $review = $row['text'];
  echo '\n' . '\n' . '\n' . $review;
}

?>