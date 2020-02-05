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
    if (isset($_POST["login"])) {
      if (empty($_POST["username"]) || empty($_POST["password"])) {
        echo "all fields are required";
        echo "$connect";
      } else {
        $query = "SELECT * FROM users WHERE mirror_id = :username AND password =:password";
        $statement = $connect->prepare($query);
        $statement->execute(
          array(
            'username' => $_POST["username"],
            'password' => $_POST["password"],
          )
        );
        $count = $statement->rowCount();
        if ($count > 0) {
          $data = [
            'date_cookie' => date("Y-m-d H:i:s"),
            'username' => $_POST["username"],
          ];

          $query = "UPDATE users SET login_cookie=:date_cookie WHERE mirror_id =:username";
          $statement = $connect->prepare($query);
          $statement->execute($data);
          echo "login_success";
        } else {
          echo "Wrong initials";
        }
      }

    }

    else if(isset($_POST["login_cookie"])){

    }

  } catch (PDOException $error) {
    $message = $error->getMessage();
    echo "PDOException " . $message;

  }
}
