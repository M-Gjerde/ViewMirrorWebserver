<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require '../connection.php';
  request_setup_information($host, $database, $username, $password);
}

function request_setup_information($host, $database, $username, $password)
{
  $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $mirror_id = $_POST["mirror_id"];
  $request = $_POST["request"];

  $stmt = $pdo->prepare("SELECT * FROM setup WHERE mirror_id=?");
  $stmt->execute([$mirror_id]);
  $row = $stmt->fetch(PDO::FETCH_NUM);

  if (empty($row) && $request == "setup") {
    setupNewUser($pdo, $mirror_id);
  }
  else if (!empty($row) && $request == "setup") {
    updateNewUser($pdo, $mirror_id);
  }
  else if (!empty($row) && $request == "getUserData") getUserData($pdo, $mirror_id);
  else if (!empty($row) && $request == "getSetupData") getSetupData($pdo, $mirror_id);
  else if($request == "outlookSetupURL") setupOutlookURL($pdo, $mirror_id);

}

function getUserData($pdo, $mirror_id)
{
  $stmt = $pdo->prepare("SELECT * FROM view_control WHERE mirror_id=:mirror_id");
  $stmt->execute(['mirror_id' => $mirror_id]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  echo json_encode($data);
}


function getSetupData($pdo, $mirror_id)
{
  $stmt = $pdo->prepare("SELECT * FROM setup WHERE mirror_id=:mirror_id");
  $stmt->execute(['mirror_id' => $mirror_id]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  echo json_encode($data);
}

function setupNewUser($pdo, $mirror_id)
{
  $service_provider = $_POST["service_provider"];
  $sql = "INSERT INTO setup (mirror_id, service_provider, calendar_setup) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$mirror_id, $service_provider, 1]);
}

function updateNewUser($pdo, $mirror_id)
{
  $service_provider = $_POST["service_provider"];
  $sql = "UPDATE setup SET service_provider =:service_provider, calendar_setup=:setup WHERE mirror_id=:mirror_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'mirror_id' => $mirror_id,
    'service_provider' => $service_provider,
    'setup' => 1
  ]);
  echo "updated user " . $service_provider;
}

function setupOutlookURL($pdo, $mirror_id){
  $statement = $pdo->prepare("SELECT calendar_setup FROM setup WHERE mirror_id=:mirror_id");
  $statement->execute(['mirror_id' => $mirror_id]);

  while ($row = $statement->fetch()) {
    if ($row[0] == "1"){
      $stmt = $pdo->prepare("SELECT outlook_setupURL FROM setup WHERE mirror_id=:mirror_id");
      $stmt->execute(['mirror_id' => $mirror_id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      echo urlencode($data["outlook_setupURL"]);
    }
    else {
      echo "db not ready";
    }
  }

}

