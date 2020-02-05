<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require '../connection.php';
  getPreferences($host, $database, $username, $password);
}

function getPreferences($host, $database, $username, $password){
  try {
    $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data_json = file_get_contents("php://input");
    $data = json_decode($data_json,true);

    if ($data["getPreferences"] == "true") {                            //android app request

      $mirror_id = $data["mirror_id"];
      $result = $pdo->prepare("SELECT * FROM view_control WHERE mirror_id=:username");

      $result->execute(['username' => $mirror_id]);
      $rows = $result->fetchAll(PDO::FETCH_ASSOC);
      header('Content-Type: application/json;charset=utf-8');
      echo json_encode(['streamers' => $rows],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    }
    else if($_POST["getPreferences"] == "true"){                       //JavaScript request
      $mirror_id = $_POST["mirror_id"];
      $result = $pdo->prepare("SELECT * FROM view_control WHERE mirror_id=:username");
      $result->execute(['username' => $mirror_id]);

      $rows = $result->fetchAll(PDO::FETCH_ASSOC);
      header('Content-Type: application/json;charset=utf-8');
      echo json_encode(['streamers' => $rows],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    }

    else{
      echo "no data";
    }

  } catch (PDOException $error) {
    $message = $error->getMessage();
    echo "PDOException " . $message;

  }


}
