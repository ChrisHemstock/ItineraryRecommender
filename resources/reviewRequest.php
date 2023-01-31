<?php
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include_once 'includes/functions.php';

require_once 'vendor/autoload.php';

require_once "includes/dbconnect.php";
set_time_limit(360);



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
    // Get all review tokens (One of every word from the reviews)in one document
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
    
    
    //
    // Creates a new document for every review
    // Every document has every token from that review
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


    //
    // TFIDF
    //
    //
    $docsCollection = new DocumentArrayCollection($docs);
    $userTfidf = new TfIdf($docsCollection);


    //creates the user profile vector
    //
    $userProfile = [];
    foreach($allReviews as $word) {
      if($word != '') {
        array_push($userProfile, $userTfidf->getTfIdf($docs['likes'], $word, 3));
      }
    }
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
        
        // var_dump(count($vector));
        // var_dump(count($userProfile));
        
        $similarity = cosineSimilarity($userProfile, $vector);
        //var_dump($similarity);
        if($similarity != -2) {
          $poisLiked[$poiReview[1]] = $similarity;
        }
        
      } 
    }
    asort($poisLiked);
    array_pop($poisLiked);
    //var_dump($docs['all']);
    // //Prints the recommended POIs
    // foreach($poisLiked as $key=>$value) {
    //   echo $key . '=' . $value . '<br>';
    // }

    //returns an ordered list of POIs to recommend
    
    
    return json_encode(array_slice(array_reverse($poisLiked, true), 0, 5, true));
   
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
