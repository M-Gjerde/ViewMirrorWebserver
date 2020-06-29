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

    $stmt = $pdo->prepare("SELECT * FROM view_setup WHERE mirror_id=?");
    $stmt->execute([$mirror_id]);
    $row = $stmt->fetch(PDO::FETCH_NUM);


    if (empty($row)) {
        echo 2;
        return null;
    } else {


        switch ($request) {
            case "user_data":
                getUserData($pdo, $mirror_id);
                break;
            case "setup_data":
                getSetupData($pdo, $mirror_id);
                break;
            case "outlook_setup_url":
                setupOutlookURL($pdo, $mirror_id);
                break;
            case "google_setup_url":
                setupGoogleURL($pdo, $mirror_id);
                break;
            case "location":
                updateUserWeatherLocation($pdo, $mirror_id);
                break;
            case "calendar_setup":
                updateUserEmail($pdo, $mirror_id);
                break;
            default:
                echo 4;
                return null;
        }
    }

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
    $stmt = $pdo->prepare("SELECT * FROM view_setup WHERE mirror_id=:mirror_id");
    $stmt->execute(['mirror_id' => $mirror_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
}


function updateUserEmail($pdo, $mirror_id)
{
    $service_provider = $_POST["calendar_provider"];
    $sql = "UPDATE view_setup SET calendar_provider =:calendar_provider, calendar_setup=:setup WHERE mirror_id=:mirror_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'mirror_id' => $mirror_id,
        'calendar_provider' => $service_provider,
        'setup' => 1
    ]);
    echo 1;
}

function updateUserWeatherLocation($pdo, $mirror_id)
{
    $location = $_POST["location"];
    $sql = "UPDATE view_setup SET location =:location WHERE mirror_id=:mirror_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'mirror_id' => $mirror_id,
        'location' => $location,
    ]);
    echo 1;
}

function setupGoogleURL($pdo, $mirror_id)
{
    $stmt = $pdo->prepare("SELECT google_setup_link FROM view_setup WHERE mirror_id=:mirror_id");
    $stmt->execute(['mirror_id' => $mirror_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo urlencode($data["google_setup_link"]);
}

function setupOutlookURL($pdo, $mirror_id)
{
    $stmt = $pdo->prepare("SELECT outlook_setupURL FROM view_setup WHERE mirror_id=:mirror_id");
    $stmt->execute(['mirror_id' => $mirror_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo urlencode($data["outlook_setupURL"]);
}

