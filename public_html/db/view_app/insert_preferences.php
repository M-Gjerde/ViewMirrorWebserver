<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require '../connection.php';
  insertMessage($host, $database, $username, $password);
}

function insertMessage($host, $database, $username, $password)
{

  $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $data_json = file_get_contents("php://input");
  $data = json_decode($data_json, true);

  $checkForViewID = "SELECT mirror_id FROM view_control";
  $statement = $pdo->prepare($checkForViewID);
  $statement->execute();
  $array = array();
  while ($row = $statement->fetch(PDO::FETCH_NUM)) {
    $array[] = $row[0];
  }

  try{
    $data = [
      'mirror_id' => $_POST["mirror_id"],
      'news_url' => $_POST["news_url"],
      'power_mode' => $_POST["power_mode"],
      'spotify_playback' => $_POST["spotify_playback"],
      'calendar' => $_POST["calendar"],
      'news_channel' => $_POST["news_channel"],
      'finance' => $_POST["finance"],
      'slideshow' => $_POST["slideshow"]
    ];
  } catch (Exception $e){
    echo $e . "Not all params were passed";
  }

  $insertNewRow = true;

  //Insert SQL | Else Update SQL
  for ($i = 0; $i < sizeof($array); $i++){
    if ($array[$i] == $_POST["mirror_id"]){
      try {
        $sql = "UPDATE view_control 
    SET news_channel = :news_channel,
        news_url = :news_url,
        power_mode = :power_mode,
        spotify_playback = :spotify_playback,
        calendar = :calendar,
        finance = :finance,
        slideshow = :slideshow
  WHERE mirror_id = :mirror_id";
        $statement = $pdo->prepare($sql);
        $statement->execute($data);
      }
      catch (Exception $e){
        echo $e;
      }
      $insertNewRow = false;
      echo "update";
      break;
    }
  }

if ($insertNewRow) {
  $sql = "INSERT INTO view_control 
(mirror_id, news_url, power_mode, spotify_playback, calendar, news_channel, finance, slideshow) 
VALUES (:mirror_id, :news_url, :power_mode, :spotify_playback, :calendar, :news_channel, :finance, :slideshow)";
  $statement = $pdo->prepare($sql);
  $statement->execute($data);
  echo "insert";
}

}


