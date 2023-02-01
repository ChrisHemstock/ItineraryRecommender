<?php
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include_once 'includes/functions.php';

require_once 'vendor/autoload.php';

require_once "includes/dbconnect.php";
set_time_limit(360);
<<<<<<< HEAD
=======
//$userID = $_SESSION["id"];


// //get user interests
// function userInterests($userID) {
//   global $link;
//   $poiReviews = $link->query('SELECT reviews FROM likes, pois WHERE userID = ' . $userID . ' AND pois.API_ID = likes.POI_ID ;')->fetch_all();
//   $likes = array();
//   foreach($poiReviews as $poiReview) {
//     array_push($likes, $poiReview);
//   }
//   return $likes;
// }


// function getReviewsArray() {
//   global $link;
//   $docs = [];
//   $tokens = new TokensDocument(tokenize('art fitness book industrial electronic finance food game garden communicate educate leisure vehicle'));
//   $docs['interests'] = $tokens;

//   $client = new \GuzzleHttp\Client();

//   $api_id = $link->query('SELECT id, API_ID FROM POIs')->fetch_all();
//   foreach ($api_id as $id) {
//     sleep(1);
//     $response = $client->request('GET', 'https://api.yelp.com/v3/businesses/' . $id[1] . '/reviews?limit=20&sort_by=yelp_sort', [
//       'headers' => [
//         'Authorization' => 'Bearer FPHBQC5fbtVpUqt4lQtAmTPXNWzDKblHryRIRIfoL5PYHgLmW109muvBkAqYyscdeNerih_ZQrxs4WGnp-xf4pgyBDbEmO36NlUS8MB6GvgJp52qoqW_nUdvG9uOY3Yx',
//         'accept' => 'application/json',
//       ],
//     ]);
//     $data = json_decode($response->getBody(), true);

//     $review = '';

//     foreach($data["reviews"] as $row) {
//       $text = strtolower(preg_replace('/[^A-Za-z\ ]/', '', $row['text']));
//       $review .= ' ' . $text;
//     }


//     //push review to database here ********** this could be up to three reviews concat. together

//     $tokens = new TokensDocument(tokenize($review));
//     $docs[$id[0]] = $tokens;
//   }

//   return $docs;
// }

// //  $likes = the array returned from userInterests
// //  $tfidf = a tfidf object
// //  $docs = the array from getReviewsArray
// function createUserVector($likes, $tfidf, $docs) {
//   $userProfile = [];
//   foreach($likes as $like) {
//     array_push($userProfile, $tfidf->getTfIdf($docs['interests'], $like, 3));
//   }

//   return $userProfile;
// }

// //  $docs = the array from getReviewsArray
// //  $likes = the array returned from userInterests
// //  $tfidf = a tfidf object
// //  $userProfile = What is returned from create UserVector
// function poiRecommendArray($docs, $likes, $tfidf, $userProfile) {
//   $poisLiked = [];
//   foreach($docs as $key=>$poi) {
//     $vector = [];
//     foreach($likes as $like) {
//       array_push($vector, $tfidf->getTfIdf($poi, $like, 3));
//     }
//     $similarity = cosineSimilarity($userProfile, $vector);
//     if($similarity != -2) {
//       $poisLiked[$key] = $similarity;
//     }
//   }
//   asort($poisLiked);
//   array_pop($poisLiked);
//   $poisLiked = array_reverse($poisLiked, true);
// }




//Functions above are for use later



>>>>>>> 5299122a4cd46d51e54b7a6f9f9258cfdde29956

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
      array_shift($review);


      $likes = array_merge($likes, $review);
    }
    //var_dump($likes);

    $docs = [];
    $tokens = new TokensDocument($likes);
    $docs['likes'] = $tokens;
    //var_dump($docs['likes']);
    


    //
    // Get all review tokens in one document
    //
    //
    $POI_reviews = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
    $allReviews = array();
    foreach($POI_reviews as $poiReview) {      
      $review = explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $poiReview[0])));
      array_shift($review);
      $allReviews = array_merge($allReviews, $review);
    }
    
    $allReviews = array_unique($allReviews);
    $allReviewTokens = new TokensDocument($allReviews);
    $docs['all'] = $allReviewTokens;
    //var_dump($allReviews);
    //
    // Get all review tokens in one document per poi
    //
    //



    $POI_reviews = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
    //$poiDocs = array();
    foreach ($POI_reviews as $review) {
      //var_dump($review);
      if($review[0] != Null) {
        $tokens = new TokensDocument(tokenize($review[0]));
        $docs[$review[1]] = $tokens;
      }
    }
    //var_dump($docs);



    //var_dump($likes);
    //var_dump($poiDocs);
    
    //var_dump($docs['likes']);



    //
    // TFIDF
    //
    //
    $docsCollection = new DocumentArrayCollection($docs);
    $userTfidf = new TfIdf($docsCollection);


    //creates the user profile vector

    $userProfile = [];
    foreach($allReviews as $word) {
      if($word != '') {
        //echo $word . ': ' . $userTfidf->getTfIdf($docs['likes'], $word, 3) . '<br>';
        array_push($userProfile, $userTfidf->getTfIdf($docs['likes'], $word, 3));
      }
    }
    //var_dump($allReviews);
    //var_dump($userProfile);


    $POI_reviews = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
    $poisLiked = [];
    foreach($POI_reviews as $poiReview) { 
      if($poiReview[0] != '') {
        // $review = explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $poiReview[0])));
        // //array_shift($review);
        // $review = array_filter($review);
        // $review
        $vector = [];
          //var_dump($token);
        foreach($allReviews as $word) {
          if($word != '') {
            array_push($vector, $userTfidf->getTfIdf($docs[$poiReview[1]], $word, 3));
          }
        }
                                              //review, each word in all
        

        
        $similarity = cosineSimilarity($userProfile, $vector);
        //var_dump($similarity);
        if($similarity != -2) {
          $poisLiked[$poiReview[1]] = $similarity;
        }
        
      } 
    }
    asort($poisLiked);
    array_pop($poisLiked);

   // var_dump($poisLiked);

    //returns an ordered list of POIs to recommend
    
    
    return json_encode(array_slice(array_reverse($poisLiked, true), 0, 5, true));
    



    
  }
}


?>