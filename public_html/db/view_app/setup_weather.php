<?php

use JsonStreamingParser\Listener\GeoJsonListener;
use JsonStreamingParser\Listener\InMemoryListener;
use JsonStreamingParser\Parser;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connection.php';
    get_city_id($host, $database, $username, $password);
}


function get_city_id($host, $database, $username, $password)
{

    /*
// Read JSON file
    $json = file_get_contents('./current_city_list.json');
//Decode JSON
    $json_data = json_decode($json, true);
    $client_city_name = "";
    $client_city_name = $_POST["city_name"];
    $mirror_id = $_POST["mirror_id"];


    $match_flag = false;
    for ($i = 0; $i < sizeof($json_data); $i++) {
        if ($json_data[$i]["name"] == $client_city_name) {
            echo 1;
            $match_flag = true;
        }
    }

    if (!$match_flag){
        echo 20;
    }


    $client_city_name = $_POST["city_name"];

    $city_list = dirname(__FILE__) . '/current_city_list.json';
    $match_flag = false;


    $stream = fopen($city_list, 'r');

    $listener = new GeoJsonListener(function ($item) use ($client_city_name) {
        if($item["name"] == $client_city_name) {
            //We got a match, store this and post to user database
            $match_flag = true;
        }
    });

    try {
        $parser = new Parser($stream, $listener);
        $parser->parse();
        fclose($stream);
    } catch (Exception $e) {
        fclose($stream);
        throw $e;
    }

    //We didnt find a city match, let user know that the city is not supported
    if (!$match_flag){
        echo 1;
    } else {
        echo 20;
    }
*/

    $client_city_name = $_POST["city_name"];
    $mirror_id = $_POST["mirror_id"];


}