<?php
    use \PHPUnit\Framework\TestCase as TestCase;
    
    

    class SampleTest extends TestCase { 
        public function test_recommendations_empty_likes() {
            include_once __DIR__.'../../../app/includes/dbconnect.php';
            include_once __DIR__.'../../../app/includes/functions.php';
            include_once __DIR__.'../../../app/resources/reviewRequest.php';
            require_once(__DIR__ . '\..\..\vendor\autoload.php');

            define('USER_ID', 20);

            deleteLikes($link, USER_ID);

            $recommender = new Recommender($link);
            $recommender->update_recommendations(5, USER_ID);
            
            // Tests no data in likes - should return a json string with top 5 most rated pois
            $this->assertEquals($recommender->get_recommendations(), topPoiJson($link, 5));
        }

        public function test_recommendations_one_likes() {
            include_once __DIR__.'../../../app/includes/dbconnect.php';
            include_once __DIR__.'../../../app/includes/functions.php';
            include_once __DIR__.'../../../app/resources/reviewRequest.php';

            addLikes($link, USER_ID, 'kzxpl9HidQVMEuUoRVB7nA'); // Victory Field

            $recommender = new Recommender($link);
            $recommender->update_recommendations(1, USER_ID);

            // Tests 1 entry in likes - should return a json with 1 entry with a value of 1
            $this->assertEquals($recommender->get_recommendations(), '{"1248":0.9999999999999999}');
        }

        public function test_recommendations_length() {
            include_once __DIR__.'../../../app/includes/dbconnect.php';
            include_once __DIR__.'../../../app/includes/functions.php';
            include_once __DIR__.'../../../app/resources/reviewRequest.php';

            addLikes($link, USER_ID, 'UFCN0bYdHroPKu6KV5CJqg'); // The Eagle

            $recommender = new Recommender($link);
            $recommender->update_recommendations(10, USER_ID);
            $recommendation_array = json_decode($recommender->get_recommendations(), true);

            //Tests that the proper amount of items are returned in a normal case
            $this->assertEquals(count($recommendation_array), 10);
        }

        public function test_recommendations_zero_amount() {
            include_once __DIR__.'../../../app/includes/dbconnect.php';
            include_once __DIR__.'../../../app/includes/functions.php';
            include_once __DIR__.'../../../app/resources/reviewRequest.php';

            $recommender = new Recommender($link);
            $recommender->update_recommendations(0, USER_ID);

            //Tests that an empty array string is returned when asked for 0 entries
            $this->assertEquals($recommender->get_recommendations(), '[]');
        }
    }
?>