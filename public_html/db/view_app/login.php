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

    if ($request == "login") {
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

    } else if ($request == "first_login") {
        first_login($connect, $mirror_id, $password);
    } else {
        echo 4;
        return 4;
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

function first_login($connect, $mirror_id)
{
    $email = $_POST["email"]; //TODO return error if not all parameters are found
    $new_password = $_POST["new_password"];
    $full_name = $_POST["full_name"];
    $data = [
        'date_cookie' => date("Y-m-d H:i:s"),
        'new_password' => $new_password,
        'name' => $full_name,
        'email' => $email,
        'mirror_id' => $mirror_id,
        'first_login' => 0,
    ];

    $query = "UPDATE users SET login_cookie=:date_cookie, password=:new_password, first_login=:first_login, email=:email, name=:name WHERE mirror_id =:mirror_id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo 1;

}