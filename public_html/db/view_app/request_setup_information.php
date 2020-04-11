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
            case "weather_location":
                updateUserWeatherLocation($pdo, $mirror_id);
                break;
            case "calendar_provider":
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
    $service_provider = $_POST["service_provider"];
    $sql = "UPDATE view_setup SET service_provider =:service_provider, calendar_setup=:setup WHERE mirror_id=:mirror_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'mirror_id' => $mirror_id,
        'service_provider' => $service_provider,
        'setup' => 1
    ]);
    echo "updated user " . $service_provider;
}

function updateUserWeatherLocation($pdo, $mirror_id)
{
    $weather_location = $_POST["weather_location"];
    $sql = "UPDATE view_setup SET weather_city =:weather_location WHERE mirror_id=:mirror_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'mirror_id' => $mirror_id,
        'weather_location' => $weather_location,
    ]);
    echo "updated users location to " . $weather_location;
}

function setupOutlookURL($pdo, $mirror_id)
{
    $stmt = $pdo->prepare("SELECT outlook_setupURL FROM view_setup WHERE mirror_id=:mirror_id");
    $stmt->execute(['mirror_id' => $mirror_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo urlencode($data["outlook_setupURL"]);
}

