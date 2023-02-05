<?php
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include_once 'includes/functions.php';

require_once 'vendor/autoload.php';

require_once "includes/dbconnect.php";
set_time_limit(360);
//$userID = $_SESSION["id"];

//Good resource on TFIDF
//https://janav.wordpress.com/2013/10/27/tf-idf-and-cosine-similarity/

function getRecommendations($link, $userID) {

  //
  // Get all the tokens for the likes in one document
  //
  //
  $poiReviews = $link->query('SELECT reviews FROM likes, pois WHERE userID = ' . $userID . ' AND pois.API_ID = likes.POI_ID ;')->fetch_all();
  if(count($poiReviews) > 0) {
    $likes = array();
    foreach($poiReviews as $poiReview) {      
      $review = explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $poiReview[0])));
      $likes = array_filter(array_merge($likes, $review));
    }
    // var_dump($likes);

    $docs = [];
    $tokens = new TokensDocument($likes);
    $docs['likes'] = $tokens;
    


    //
    // Get all review tokens in one document
    // Build all review documents
    //
    $POI_reviews = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
    $allReviews = array();
    foreach($POI_reviews as $poiReview) {
      if($poiReview[0] != Null) {     
        $review = array_filter(explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $poiReview[0]))));

        //Adds the new review onto the end of the array for one big array with all the words
        $allReviews = array_merge($allReviews, $review);

        //Adds a new vector for the current poi
        $tokens = new TokensDocument($review);
        $docs[$poiReview[1]] = $tokens;
      }
    }
    //Makes a big array with one of every word from the reviews. - 2081 total words
    $allReviews = array_unique($allReviews);
    //var_dump($allReviews);
    //var_dump($docs);



    //
    // TFIDF
    //
    //
    $docsCollection = new DocumentArrayCollection($docs);
    $userTfidf = new TfIdf($docsCollection);


    //
    // Creates all the vectors for the POIs and one for the user
    //
    $vectorCollection = [];
    $vector = [];
    foreach($docs as $key => $values) {
      foreach($allReviews as $word) {
        $vector[$word] = $userTfidf->getTfIdf($values, $word, 3);
      }
      $vectorCollection[$key] = $vector;
    }
    //var_dump($vectorCollection['likes']);


    $poisLiked = [];
    foreach($vectorCollection as $key => $vector) {
      $similarity = cosineSimilarity($vectorCollection['likes'], $vector);
      $poisLiked[$key] = $similarity;
    }
    unset($poisLiked['likes']);
    asort($poisLiked);
    $poisLiked = array_reverse($poisLiked, true);

    //var_dump($poisLiked);

    //returns an ordered list of POIs to recommend
    return json_encode(array_slice($poisLiked, 0, 5, true));
  }
}
?>