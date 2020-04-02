<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connection.php';
    login($host, $database, $username, $password);
}

function login($host, $database, $username, $password)
{
    try {
        $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);

        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_POST["request"] == "login") { //else request == "create_account"
            if (empty($_POST["username"]) || empty($_POST["password"])) {
                echo "all fields are required";
            } else {
                $query = "SELECT * FROM users WHERE mirror_id = :username";
                $statement = $connect->prepare($query);
                $statement->execute(['username' => $_POST["username"]]);
                $data = $statement->fetch(PDO::FETCH_ASSOC);

                if (count($data["mirror_id"]) == 0) {
                    echo "Serial number does not exist";
                    return null;
                }

                if (!$data["first_login"] && $data["password"] == $_POST["password"]) {
                    continued_login($connect);
                } else if ($data["first_login"]) {
                    first_login($connect);
                } else {
                    echo "wrong initials";
                }
            }

        } else {
            echo "Wrong request";
        }

    } catch (PDOException $error) {
        $message = $error->getMessage();
        echo "PDOException " . $message;

    }
}

function continued_login($connect)
{
    $data = [
        'date_cookie' => date("Y-m-d H:i:s"),
        'username' => $_POST["username"],
    ];

    $query = "UPDATE users SET login_cookie=:date_cookie WHERE mirror_id =:username";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo "login_success";
}

function first_login($connect){
    $data = [
        'date_cookie' => date("Y-m-d H:i:s"),
        'password' => $_POST["password"],
        'username' => $_POST["username"],
        'first_login' => 0,
    ];

    $query = "UPDATE users SET login_cookie=:date_cookie, password=:password, first_login=:first_login WHERE mirror_id =:username";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo "account_created";
}