<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connection.php';
    get_city_id($host, $database, $username, $password);
}


function get_city_id($host, $database, $username, $password)
{

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

}