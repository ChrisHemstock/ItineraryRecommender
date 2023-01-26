<?php
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Indexes\TfIdf;
include_once 'includes/functions.php';

require_once 'vendor/autoload.php';

require_once "includes/dbconnect.php";
set_time_limit(360);
//$userID = $_SESSION["id"];


// //get user interests
// function userInterests($userID) {
//   global $link;
//   $interests = $link->query('SELECT Description FROM interests WHERE userID = ' . $userID . ' ;')->fetch_all();
//   $likes = array();
//   foreach($interests as $interest) {
//     $txt = '';
//     if($interest[0] == 'artsEntertainment') {
//       $txt = 'art';
//     } else if($interest[0] == 'beautyFitness') {
//       $txt = 'fitness';
//     } else if($interest[0] == 'books') {
//       $txt = 'book';
//     } else if($interest[0] == 'businessIndustrial') {
//       $txt = 'industrial';
//     } else if($interest[0] == 'electronics') {
//       $txt = 'electronic';
//     } else if($interest[0] == 'finance') {
//       $txt = 'finance';
//     } else if($interest[0] == 'food') {
//       $txt = 'food';
//     } else if($interest[0] == 'games') {
//       $txt = 'game';
//     } else if($interest[0] == 'homeGarden') {
//       $txt = 'garden';
//     } else if($interest[0] == 'internetTelecom') {
//       $txt = 'communicate';
//     } else if($interest[0] == 'jobsEducation') {
//       $txt = 'educate';
//     } else if($interest[0] == 'leisure') {
//       $txt = 'leisure';
//     } else if($interest[0] == 'vehicles') {
//       $txt = 'vehicle';
//     }
//     array_push($likes, $txt);
//   }
//   return $likes;
// }

function getReviewsArray() {
  global $link;
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

    //push review to database here ********** this could be up to three reviews concat. together

    $tokens = new TokensDocument(tokenize($review));
    $docs[$id[0]] = $tokens;
  }

  return $docs;
}

//  $likes = the array returned from userInterests
//  $tfidf = a tfidf object
//  $docs = the array from getReviewsArray
function createUserVector($likes, $tfidf, $docs) {
  $userProfile = [];
  foreach($likes as $like) {
    array_push($userProfile, $tfidf->getTfIdf($docs['interests'], $like, 3));
  }

  return $userProfile;
}

//  $docs = the array from getReviewsArray
//  $likes = the array returned from userInterests
//  $tfidf = a tfidf object
//  $userProfile = What is returned from create UserVector
function poiRecommendArray($docs, $likes, $tfidf, $userProfile) {
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
}




//Functions above are for use later




function getRecommendations($link, $userID) {
  $liked_POIs = $link->query('SELECT POI_ID FROM likes WHERE userID = ' . $userID . ' ;')->fetch_all();
  if(count($liked_POIs) > 0) {
    foreach ($liked_POIs as $POIs) {
      foreach ($POIs as $POI_ID) {
        //var_dump($POI_ID);
        $liked_reviews = $link->query('SELECT reviews FROM POIs WHERE API_ID = "' . $POI_ID . '" ;')->fetch_all();
        foreach ($liked_reviews as $review) {
          $likes = $review;
         // var_dump($reviewText);
        }
      }
    }

    //Get reviews      returns an array of reviews
    //$docs = [];
    //$tokens = new TokensDocument(tokenize('art fitness book industrial electronic finance food game garden communicate educate leisure vehicle'));
    //$docs['interests'] = $tokens;


    // $POI_reviews = $link->query('SELECT reviews, id FROM POIs')->fetch_all();
    // foreach ($POI_reviews as $review) {
    //   $tokens = new TokensDocument(tokenize($review[0]));
    //   $docs[$review[1]] = $tokens;
    //   //var_dump($review);
    // }




    // $docsCollection = new DocumentArrayCollection($docs);
    // $tfidf = new TfIdf($docsCollection);


    // //creates the user profile vector
    // $userProfile = [];
    // foreach($likes as $like) {
    //   array_push($userProfile, $tfidf->getTfIdf($docs['interests'], $like, 3));
    // }


    // //returns an ordered list of POIs to recommend
    // $poisLiked = [];
    // foreach($docs as $key=>$poi) {
    //   $vector = [];
    //   foreach($likes as $like) {
    //     array_push($vector, $tfidf->getTfIdf($poi, $like, 3));
    //   }
    //   $similarity = cosineSimilarity($userProfile, $vector);
    //   if($similarity != -2) {
    //     $poisLiked[$key] = $similarity;
    //   }
    // }
    // asort($poisLiked);
    // array_pop($poisLiked);
    // return json_encode(array_slice(array_reverse($poisLiked, true), 0, 10, true));



    // //Prints the recommended POIs
    // foreach($poisLiked as $key=>$value) {
    //   echo $key . '=' . $value . '<br>';
    // }
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