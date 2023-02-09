<?php
    use \PHPUnit\Framework\TestCase as TestCase;
    
    

    class SampleTest extends TestCase {
        public function testGetRecommendations() {
            include_once __DIR__.'../../../app/includes/dbconnect.php';
            include_once __DIR__.'../../../app/includes/functions.php';
            include_once __DIR__.'../../../app/resources/reviewRequest.php';

            define('USER_ID', 30);

            deleteLikes($link, USER_ID);

            // Tests no data in likes - should return a json string with top 5 most rated pois
            $this->assertEquals(getRecommendations($link, USER_ID, 5), topPoiJson($link, 5));

            addLikes($link, USER_ID, 'kzxpl9HidQVMEuUoRVB7nA'); // Victory Field

            // Tests 1 entry in likes - should return a json with 1 entry with a value of 1
            $this->assertEquals(getRecommendations($link, USER_ID, 1), '{"1248":0.9999999999999999}');

            addLikes($link, USER_ID, 'UFCN0bYdHroPKu6KV5CJqg'); // The Eagle

            $recommendationsString = getRecommendations($link, USER_ID, 10);
            $recommendationArray = json_decode($recommendationsString, true);

            //Tests that the proper amount of items are returned in a normal case
            $this->assertEquals(count($recommendationArray), 10);

            //Tests that an empty array string is returned when asked for 0 entries
            $this->assertEquals(getRecommendations($link, USER_ID, 0), '[]');
        }
    }
?>