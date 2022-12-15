<?php
<<<<<<< HEAD:reviewRequest.php
require_once('vendor/autoload.php');
=======
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include '../includes/functions.php';

require_once('../vendor/autoload.php');
>>>>>>> b5d8db73b033780523610898f9ca37e6fcc07318:resources/reviewRequest.php

require_once "../includes/dbconnect.php";
session_start();
$userID = $_SESSION["id"];

set_time_limit(360);

<<<<<<< HEAD:reviewRequest.php
//echo $response->getBody();
$data = $response->getBody();
$data = json_decode($response->getBody(), true);
=======
>>>>>>> b5d8db73b033780523610898f9ca37e6fcc07318:resources/reviewRequest.php

$interests = $link->query('SELECT Description FROM interests WHERE userID = ' . $userID . ' ;')->fetch_all();
if(count($interests) > 0) {
  $likes = array();
  foreach($interests as $interest) {
    $txt = '';
    if($interest[0] == 'artsEntertainment') {
      $txt = 'art';
    } else if($interest[0] == 'beautyFitness') {
      $txt = 'fitness';
    } else if($interest[0] == 'books') {
      $txt = 'book';
    } else if($interest[0] == 'businessIndustrial') {
      $txt = 'industrial';
    } else if($interest[0] == 'electronics') {
      $txt = 'electronic';
    } else if($interest[0] == 'finance') {
      $txt = 'finance';
    } else if($interest[0] == 'food') {
      $txt = 'food';
    } else if($interest[0] == 'games') {
      $txt = 'game';
    } else if($interest[0] == 'homeGarden') {
      $txt = 'garden';
    } else if($interest[0] == 'internetTelecom') {
      $txt = 'communicate';
    } else if($interest[0] == 'jobsEducation') {
      $txt = 'educate';
    } else if($interest[0] == 'leisure') {
      $txt = 'leisure';
    } else if($interest[0] == 'vehicles') {
      $txt = 'vehicle';
    }
    array_push($likes, $txt);
  }

  //'interests'=>'art fitness book industrial electronic finance food game garden communicate educate leisure vehicle'
  $docs = [];
  $tokens = new TokensDocument(tokenize('art fitness book industrial electronic finance food game garden communicate educate leisure vehicle'));
  $docs['interests'] = $tokens;

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
    //$tokens->applyStemmer(new DictionaryStemmer(new EnchantAdapter(), new SnowballStemmer()));
    $tokens = new TokensDocument(tokenize($review));
    $docs[$id[0]] = $tokens;
  }

  $docsCollection = new DocumentArrayCollection($docs);
  $tfidf = new TfIdf($docsCollection);


  $userProfile = [];
  foreach($likes as $like) {
    array_push($userProfile, $tfidf->getTfIdf($docs['interests'], $like, 3));
  }

  $poisLiked = [];
  foreach($docs as $key=>$poi) {
    $vector = [];
    foreach($likes as $like) {
      array_push($vector, $tfidf->getTfIdf($poi, $like, 3));
    }
    $similarity = cosineSimilarity($userProfile, $vector);
    if($similarity != -2) {
      $poisLiked[$key] = $similarity;
    }
  }

  asort($poisLiked);
  array_pop($poisLiked);
  $poisLiked = array_reverse($poisLiked, true);
  foreach($poisLiked as $key=>$value) {
    echo $key . '=' . $value . '<br>';
  }
}















// $response = $client->request('GET', 'https://api.yelp.com/v3/businesses/UFCN0bYdHroPKu6KV5CJqg/reviews?limit=20&sort_by=yelp_sort', [
//   'headers' => [
//     'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
//     'accept' => 'application/json',
//   ],
// ]);

// //echo $response->getBody();
// //$data = $response->getBody();
// $data = json_decode($response->getBody(), true);


// $docs = [];
// foreach($data["reviews"] as $row) {
//   $text = strtolower(preg_replace('/[^A-Za-z0-9\- ]/', '', $row['text']));
//   $tokens = new TokensDocument(tokenize($text));
//   //$tokens->applyStemmer(new DictionaryStemmer(new EnchantAdapter(), new SnowballStemmer()));
//   array_push($docs, $tokens);
//   foreach($tokens->getDocumentData() as $token) {
//     echo $token . '<br>';
//   }
// }
// $docsCollection = new DocumentArrayCollection($docs);
// $tfidf = new TfIdf($docsCollection);

// //$chicken = $tfidf->getTfIdf($docs[1], 'Eagle', 3);
// $chicken = $tfidf->getIdf();
// foreach($chicken as $chick) {
//   echo '<br>' . $chick;
// }


// foreach ($data["reviews"] as $row) {
//   //get the POI details
//   $review = $row['text'];
//   echo '<br>' . $review;
// }

?>