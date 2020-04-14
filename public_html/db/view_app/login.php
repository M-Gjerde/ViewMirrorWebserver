<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connection.php';
    login($host, $database, $username, $password);
}

function login($host, $database, $username, $password)
{
    $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $request = $_POST["request"];
    $mirror_id = $_POST["mirror_id"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    switch ($request) {
        case "login":
            if (empty($email) || empty($password)) {
                echo "all fields are required";
            } else {
                $query = "SELECT * FROM users WHERE mirror_id = :mirror_id";
                $statement = $connect->prepare($query);
                $statement->execute(['mirror_id' => $mirror_id]);
                $data = $statement->fetch(PDO::FETCH_ASSOC);

                if (count($data["mirror_id"]) == 0) {
                    echo 2;
                    return 2;
                }

                if ($data["password"] == $password) {
                    continued_login($connect, $mirror_id);
                } else {
                    echo 3;
                    return 3;
                }
            }
            break;
        case "first_login":
            first_login($connect);
            break;
        default:
            echo 4;
            break;
    }

    return null;
}

function continued_login($connect, $mirror_id)
{
    $data = [
        'date_cookie' => date("Y-m-d H:i:s"),
        'mirror_id' => $mirror_id
    ];

    $query = "UPDATE users SET login_cookie=:date_cookie WHERE mirror_id =:mirror_id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo 1;
}

function first_login($connect)
{
    //SETUP user first
    $mirror_id = generateCustomerID();
    $query = "INSERT INTO users(mirror_id) VALUES('$mirror_id')";
    $statement = $connect->prepare($query);
    $statement->execute();
    //TODO Error handling
    $query = "INSERT INTO view_control(mirror_id) VALUES('$mirror_id')";
    $statement = $connect->prepare($query);
    $statement->execute();
    //Add rows to other columns
    //TODO Error handling
    $query = "INSERT INTO view_setup(mirror_id) VALUES('$mirror_id')";
    $statement = $connect->prepare($query);
    $statement->execute();
    //TODO Error handling
    //Populate from request
    $email = $_POST["email"]; //TODO return error if not all parameters are found
    $new_password = $_POST["new_password"];
    $full_name = $_POST["full_name"];
    $data = [
        'date_cookie' => date("Y-m-d H:i:s"),
        'new_password' => $new_password,
        'name' => $full_name,
        'email' => $email,
        'mirror_id' => $mirror_id,
    ];
 
    $query = "UPDATE users SET login_cookie=:date_cookie, password=:new_password, email=:email, name=:name WHERE mirror_id =:mirror_id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo $mirror_id;

}

function generateCustomerID()
{
    $numbers = '0123456789';
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomString = '';

    for ($i = 0; $i < 2; $i++) {
        $index = rand(0, strlen($letters) - 1);
        $randomString .= $letters[$index];
    }

    for ($i = 0; $i < 4; $i++) {
        $index = rand(0, strlen($numbers) - 1);
        $randomString .= $numbers[$index];
    }

    return $randomString;
}