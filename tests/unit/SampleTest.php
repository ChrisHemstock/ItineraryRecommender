<?php
    use \PHPUnit\Framework\TestCase as TestCase;
    
    class SampleTest extends TestCase {      

        public function test_vector_calc_vs_database() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            $recommender = new Recommender($link);
            $recommender->set_all_words();
            $recommender->set_all_docs();
            $recommender->calc_poi_vectors();
            $calc_vectors = $recommender->get_poi_vectors();

            foreach ($calc_vectors as $api => $vector) {
                $calc_vectors[$api] = array_map(function($a){return round($a, 4);}, $vector);
            }

            $recommender_database = new Recommender($link);
            $recommender_database->set_all_words_database();
            $recommender_database->set_poi_vectors();
            $database_vectors = $recommender_database->get_poi_vectors();

            foreach ($database_vectors as $api => $vector) {
                $database_vectors[$api] = array_map(function($a){return round($a, 4);}, $vector);
            }

            $this->assertEquals($calc_vectors, $database_vectors);
        }

        public function test_recommendations_empty_likes() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';
            require_once(__DIR__ . '/../../vendor/autoload.php');

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            define('USER_ID', 1);

            deleteLikes($link, USER_ID);

            $recommender = new Recommender($link);
            $recommender->update_recommendations(5, USER_ID);
            
            // Tests no data in likes - should return a json string with top 5 most rated pois
            $this->assertEquals(topPoiJson($link, 5), $recommender->get_recommendations(USER_ID));
        }

        public function test_recommendations_one_likes() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            addLikes($link, USER_ID, 'kzxpl9HidQVMEuUoRVB7nA'); // Victory Field

            $recommender = new Recommender($link);
            $recommender->update_recommendations(1, USER_ID);

            // Tests 1 entry in likes - should return a json with 1 entry with a value of 1
            $this->assertEquals('{"kzxpl9HidQVMEuUoRVB7nA":"1.00000000000000"}', $recommender->get_recommendations(USER_ID));
        }

        public function test_recommendations_length() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            $recommender = new Recommender($link);
            $recommender->update_recommendations(10, USER_ID);
            $recommendation_array = json_decode($recommender->get_recommendations(USER_ID), true);

            //Tests that the proper amount of items are returned in a normal case
            $this->assertEquals(10, count($recommendation_array));
        }


        public function test_recommendations_zero_amount() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            $recommender = new Recommender($link);
            $recommender->update_recommendations(0, USER_ID);

            //Tests that an empty array string is returned when asked for 0 entries
            $this->assertEquals(topPoiJson($link, 5), $recommender->get_recommendations(USER_ID));
        }

        public function test_all_words_database() {
            include_once __DIR__.'/../../app/includes/functions.php';
            include_once __DIR__.'/../../app/resources/reviewRequest.php';

            // Establish a database connection
            $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
            if ($link->connect_error) {
                die("Connection failed: " . $link->connect_error);
            }

            $recommender_database = new Recommender($link);
            $recommender_database->set_all_words_database();

            $recommender = new Recommender($link);
            $recommender->set_all_words();

            //Tests that an empty array string is returned when asked for 0 entries
            $this->assertEquals($recommender->get_all_words(), $recommender_database->get_all_words());
        }

     public function testInsertTripPOIs() {
        include_once __DIR__.'/../../app/resources/tripData.php';
        // Initialize the variables needed for the query
        $API_ID = "TEST";
        $POI_startTime = "10:00:00";
        $POI_endTime = "11:00:00";
        $tripID = "456";

        // Establish a database connection
        $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }

        // Execute the SQL query and check for success
        $sql2 = "INSERT INTO tripPOIs(API_ID, startTime, endTime, tripID)
                VALUES('$API_ID', '$POI_startTime', '$POI_endTime', '$tripID')";
        if ($link->query($sql2) === TRUE) {
            $result = true;
        } else {
            $result = false;
        }

        // Clear test data
        $clear = "DELETE FROM tripPOIs WHERE API_ID = 'TEST'";
        if ($link->query($clear) === TRUE) {
            echo "cleared";
        } else {
            echo "not cleared";
        }

        // Close the database connection
        $link->close();

        // Assert that the query was successful
        $this->assertTrue($result);
    }

    public function testInsertLikes() {
        // Initialize the variables needed for the query
        $API_ID = "TEST";
        $userID = "20";

        // Establish a database connection
        $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }

        $sql3 = "INSERT INTO likes(userID, API_ID)
        VALUES('$userID', '$API_ID') ON DUPLICATE KEY UPDATE API_ID = '$API_ID'";
        echo $sql3;
        if ($link->query($sql3) === TRUE) {
            $result = true;
        } else {
            $result = false;
        }

        $clear = "DELETE FROM likes WHERE API_ID = 'TEST'";
        if ($link->query($clear) === TRUE) {
            echo "cleared";
        } else {
            echo "not cleared";
        }
        // Close the database connection
        $link->close();

        // Assert that the query was successful
        $this->assertTrue($result);
    }

    public function testInsertLikesDuplicate() {
        // Initialize the variables needed for the query
        $API_ID = "0BgrDm4tfQBtlEV-ZqtAtg";
        $userID = "20";

        // Establish a database connection
        $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }

        $sql3 = "INSERT INTO likes(userID, API_ID)
        VALUES('$userID', '$API_ID') ON DUPLICATE KEY UPDATE API_ID = '$API_ID'";
        echo $sql3;
        if ($link->query($sql3) === TRUE) {
            $result = true;
        } else {
            $result = false;
        }
        // Close the database connection
        $link->close();

        // Assert that the query was successful
        $this->assertTrue($result);
    }

    public function testClearPOIs() {
        // Initialize the variables needed for the query
        $API_ID = "TEST";
        $POI_startTime = "10:00:00";
        $POI_endTime = "11:00:00";
        $tripID = "316116908";

        // Establish a database connection
        $link = new mysqli("localhost", "root", "", "TripRecommenderTest");
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }

    $createTestPOI = "INSERT INTO tripPOIs(API_ID, startTime, endTime, tripID)
    VALUES('$API_ID', '$POI_startTime', '$POI_endTime', '$tripID')";
            if ($link->query($createTestPOI) === TRUE) {
                //delete all the pois under that trip id
                $deleteSQL = "DELETE FROM tripPOIs WHERE tripID = '$tripID'";
                if ($link->query($deleteSQL) === TRUE) {
                  $result = true;
                } else {
                  $result = false;
                }
            } else {
                echo "not cleared";
            }

        // Close the database connection
        $link->close();

        // Assert that the query was successful
        $this->assertTrue($result);
    }

}
?>