<?php
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include_once __DIR__.'/../includes/functions.php';

require_once __DIR__.'/../../vendor/autoload.php';

//Good resource on TFIDF
//https://janav.wordpress.com/2013/10/27/tf-idf-and-cosine-similarity/

function makeWordArray($reviewString) {
  $review = array_filter(explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $reviewString))));
  return $review;
}

//
// Get all the tokens for the likes in one document
//
//
function getUserLikes($link, $userID) {

  $query = $link->query('SELECT reviews FROM likes, pois WHERE userID = ' . $userID . ' AND pois.API_ID = likes.POI_ID ;')->fetch_all();
  $likes = [];
  if (count($query) > 0) {
    foreach ($query as $poiReview) {
      $poiReview = $poiReview[0];
      $review = makeWordArray($poiReview);
      $likes = array_merge($likes, $review);
    }
    
  }
  // var_dump($likes);
  return $likes;
}

function addDocument($document, $key, $wordList) {
  $tokens = new TokensDocument($wordList);
  $document[$key] = $tokens;
  return $document;
}

function makeVector($tfidf, $allWordsList, $document) {
  $vector = [];
  foreach($allWordsList as $word) {
      $vector[$word] = $tfidf->getTfIdf($document, $word, 3);
  }
  return $vector;
}

function addVector($link, $apiId, $word, $tfidf) {
  $insert = "INSERT INTO tfidfs(API_ID, word, value) VALUES ('" . $apiId . "','" . $word . "', '" . $tfidf . "') ON DUPLICATE KEY UPDATE value = '" . $tfidf . "'";
    $stmt = $insert;
    if (mysqli_query($link, $insert)) {
        $interestDataUpdated = true;
    } else {
        echo "ERROR: Hush! Sorry $insert. "
            . mysqli_error($link);
    }
}


function getRecommendations($link, $userID, $amount) {

  $docs = [];
  $userDocs = [];

  //
  // gets all the reviews that the user likes into one document
  //
  $likes = getUserLikes($link, $userID);
  if(count($likes) == 0) {
    $topArray = topPoiJson($link, $amount);
    return $topArray;
  }
  $tokens = new TokensDocument($likes);
  $userDocs['likes'] = $tokens;
    
  //
  // Get all review tokens in one document
  // Build all review documents
  //
  $reviewsQuery = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
  $allReviews = [];
  foreach($reviewsQuery as $poiReview) {
    $review = $poiReview[0];
    $poiID = $poiReview[1];
    if($review != Null) {
      $reviewArray = makeWordArray($review);

      //Adds the new review onto the end of the array for one big array with all the words
      $allReviews = array_merge($allReviews, $reviewArray);

      //Adds a new vector for the current poi
      $docs = addDocument($docs, $poiID, $reviewArray);
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
  $tfidf = new TfIdf($docsCollection);


  //
  // Creates all the vectors for the POIs
  //
  $vectorCollection = [];
  foreach($docs as $key => $values) {
    $vector = makeVector($tfidf, $allReviews, $values);
    // if (true) {
    //   foreach($allReviews as $word) {
    //     addVector($link, $key, $word, $vector[$word]);
    //   }
    // }
    
    $vectorCollection[$key] = $vector;
  }
  //var_dump($vectorCollection['likes']);

  $vector = makeVector($tfidf, $allReviews, $userDocs['likes']);
  $vectorCollection['likes'] = $vector;


  $poisLiked = [];
  foreach($vectorCollection as $id => $vector) {
    $similarity = cosineSimilarity($vectorCollection['likes'], $vector);
    $poisLiked[$id] = $similarity;
  }
  unset($poisLiked['likes']);
  asort($poisLiked);
  $poisLiked = array_reverse($poisLiked, true);

  //var_dump($poisLiked);

  //returns an ordered list of POIs to recommend
  return json_encode(array_slice($poisLiked, 0, $amount, true));
}
//}
?>