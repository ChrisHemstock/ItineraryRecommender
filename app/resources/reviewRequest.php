<?php
  class Recommender {
    private $user_doc;
    private $poi_docs;
    private $tfidf;
    private $all_words;
    private $user_vector;
    private $poi_vectors;
    private $link;

    function __construct($link) {
      $this->set_database_link($link);
    }

    private function make_word_array($reviewString) {
      $review = array_filter(explode(' ', strtolower(preg_replace('/[^A-Za-z\ ]/', '', $reviewString))));
      return $review;
    }

    private function add_document($document, $key, $wordList) {
      $tokens = new TextAnalysis\Documents\TokensDocument($wordList);
      $document[$key] = $tokens;
      return $document;
    }

    private function make_vector($tfidf, $allWordsList, $document) {
      $vector = [];
      $log_mode = 3;
      foreach($allWordsList as $word) {
          $vector[$word] = $tfidf->getTfIdf($document, $word, $log_mode);
      }
      return $vector;
    }

    private function cosine_similarity($list1, $list2) {
      $dotSum = 0;
      $mag1 = 0;
      $mag2 = 0;
      foreach($list1 as $word => $value) {
          $dotSum += $list1[$word] * $list2[$word];
          $mag1 += $list1[$word] ** 2;
          $mag2 += $list2[$word] ** 2;
      }
      return $dotSum/(sqrt($mag1) * sqrt($mag2));
    }

    function top_poi_json($amount) {
      //USING API_ID CHANGE TO API_ID
      $top_pois = $this->link->query('SELECT API_ID FROM pois ORDER BY num_ratings DESC LIMIT ' . $amount);
      foreach ($top_pois as $values) {
        $top_array[$values['API_ID']] = 1;
      }
      return json_encode($top_array);
    }

    private function set_database_link($link) {
      $this->link = $link;
    }

    private function set_user_doc($user_id) {
      //
      // Sets the user_doc which is a TokenDocument(array) of all of the tokens(words) that show up in the reviews of the liked POIs
      //
      $query = $this->link->query('SELECT reviews FROM likes, pois WHERE userID = ' . $user_id . ' AND pois.API_ID = likes.API_ID ;')->fetch_all();
      $likes = [];
      if (count($query) > 0) {
        foreach ($query as $poi_review) {
          $poi_review = $poi_review[0];
          $review = $this->make_word_array($poi_review);
          $likes = array_merge($likes, $review);
        }
      }

      $tokens = new TextAnalysis\Documents\TokensDocument($likes);
      $this->user_doc = $tokens;
    }

    private function get_user_doc() {
      return $this->user_doc;
    }

    public function set_all_words() {
      $reviews_query = $this->link->query('SELECT reviews FROM POIs')->fetch_all();
      $all_words = [];
      foreach($reviews_query as $poi_review) {
        $review = $poi_review[0];
        if($review != Null) {
          $review_array = $this->make_word_array($review);
          $all_words = array_merge($all_words, $review_array);
        }
      }
      //a big array with one of every word from the reviews.
      $all_words = array_unique($all_words);
      sort($all_words);
      $this->all_words = $all_words;
    }

    public function set_all_words_database() {
      $words_query = $this->link->query('SELECT DISTINCT word FROM tfidfs ORDER BY word ASC')->fetch_all();
      $all_words = [];
      foreach ($words_query as $word) {
        $all_words[] = $word[0]; //push the word to the end of the array
      }
      $this->all_words = $all_words;
    }

    public function get_all_words() {
      return $this->all_words;
    }

    public function set_all_docs() {
      $reviews_query = $this->link->query('SELECT reviews, API_ID FROM POIs')->fetch_all();
      $all_docs = [];
      foreach($reviews_query as $poi_review) {
        $review = $poi_review[0];
        $api_id = $poi_review[1];
        if($review != Null) {
          $review_array = $this->make_word_array($review);
          //Adds a new vector for the current poi
          $all_docs = $this->add_document($all_docs, $api_id, $review_array);
        }
      }
      $this->poi_docs = $all_docs;
      $doc_collection = new TextAnalysis\Collections\DocumentArrayCollection($all_docs);
      $this->tfidf = new TextAnalysis\Indexes\TfIdf($doc_collection);
    }

    private function get_poi_docs() {
      return $this->poi_docs;
    }

    private function get_tfidif() {
      return $this->tfidf;
    }

    private function set_user_vector() {
      $vector = $this->make_vector($this->get_tfidif(), $this->get_all_words(), $this->get_user_doc());
      $this->user_vector = $vector;
    }

    private function get_user_vector() {
      return $this->user_vector;
    }

    

    public function calc_poi_vectors() {
      //Good resource on TFIDF
      //https://janav.wordpress.com/2013/10/27/tf-idf-and-cosine-similarity/
      $vector_collection = [];
      foreach($this->poi_docs as $API_ID => $words_doc) {
        $vector = $this->make_vector($this->get_tfidif(), $this->get_all_words(), $words_doc);
        $vector_collection[$API_ID] = $vector;
      }
      $this->poi_vectors = $vector_collection;
      $this->add_poi_vector_database($vector_collection);
    }

    

    private function add_poi_vector_database($vector_collection) {
      //Clear the tfidfs in the database
      $delete_sql = "DELETE FROM tfidfs";
      if ($this->link->query($delete_sql) === TRUE) {
        echo "New record deleted successfully";
      } else {
        echo "Error: " . $delete_sql . "<br>" . $this->link->error;
      }

      foreach ($vector_collection as $api_id => $vector) {
        foreach ($vector as $word => $tfidf_value) {
          if($tfidf_value != 0) { //Was originaly $word instead of  $tfidf_value
            $insert_tfidf = "INSERT INTO `tfidfs`(`API_ID`, `word`, `value`) VALUES ('$api_id','$word','$tfidf_value')";
            if (mysqli_query($this->link, $insert_tfidf)) {
              //echo "New record inserted successfully";
            } else {
              echo "ERROR: Hush! Sorry $insert_tfidf. " . mysqli_error($this->link);
            }
          }
        }
      }
    }

    public function set_poi_vectors() {
      $vector_collection = [];
      $all_api_ids = $this->link->query('SELECT API_ID FROM POIs')->fetch_all();
      foreach ($all_api_ids as $api_id) {
        $api_id = $api_id[0];
        $all_tfidfs = $this->link->query('SELECT word, value FROM tfidfs WHERE API_ID = "' . $api_id . '"');
        $vector = [];
        foreach ($this->get_all_words() as $word) {
          $vector[$word] = 0.0;
        }
        foreach ($all_tfidfs as $tfidf) {
          $vector[$tfidf['word']] = floatval($tfidf['value']);
        }
        $vector_collection[$api_id] = $vector;
      }
      //[api_id -> [word -> tfidf, word - > tfidf, ...], api_id - > [...], ...]
      $this->poi_vectors = $vector_collection;
    }

    public function get_poi_vectors() {
      return $this->poi_vectors;
    }

    function update_user($user_id) {
      $this->set_user_doc($user_id);
      $this->set_user_vector();
    }

    function calc_recommendations($amount) {
      $poi_similarities = [];
      foreach($this->get_poi_vectors() as $API_ID => $vector) {
        $similarity = $this->cosine_similarity($this->get_user_vector(), $vector);
        $poi_similarities[$API_ID] = $similarity;
      }
      
      asort($poi_similarities);
      $poi_similarities = array_reverse($poi_similarities, true);
      
      //returns an ordered list of POIs to recommend
      return array_slice($poi_similarities, 0, $amount, true);
    }
    
    function update_recommendations($amount, $user_id) {
      //Delete all current recommendations for the user
      $delete_recommend = "DELETE FROM `recommendations` WHERE userID = $user_id";
      $stmt = $delete_recommend;
      if (!(mysqli_query($this->link, $delete_recommend))) {
        echo "ERROR: Hush! Sorry $delete_recommend. " . mysqli_error($this->link);
      }

      $this->set_user_doc($user_id);
      if(count($this->get_user_doc()->toArray()) == 0) {
        return;
      }
      //$this->set_all_words(); //Uncomment me to fill out the tfidf table, If you do uncomment me comment out $this->set_all_words_database();
      $this->set_all_words_database();
      $this->set_all_docs();
      $this->set_user_vector();
      //$this->calc_poi_vectors(); //Uncomment me to fill out the tfidf table, If you do uncomment me comment out $this->set_poi_vectors();
      $this->set_poi_vectors();

      $recommendations = $this->calc_recommendations($amount);

      foreach ($recommendations as $API_ID => $value) {
        //Insert in the new recommendations
        $insert_recommend = "INSERT INTO `recommendations`(`API_ID`, `userID`, `value`) VALUES ('$API_ID','$user_id','$value')";
        $stmt = $insert_recommend;
        if (mysqli_query($this->link, $insert_recommend)) {
            $interestDataUpdated = true;
        } else {
            echo "ERROR: Hush! Sorry $insert_recommend. "
                . mysqli_error($this->link);
        }
      }
    }

    function get_recommendations($user_id, $amount) {
      $recommend_query = $this->link->query("SELECT API_ID, value FROM recommendations WHERE userID = $user_id")->fetch_all();
      $poi_similarities = [];

      foreach ($recommend_query as $values) {
        $api_id = $values[0];
        $tfidf_value = $values[1];
        $poi_similarities[$api_id] = $tfidf_value;
      }

      if(count($poi_similarities) == 0) {
        $top_json = $this->top_poi_json($amount);
        return $top_json;
      }
      
      //returns a list of POIs to recommend
      return json_encode($poi_similarities);
    }
  } 
?>