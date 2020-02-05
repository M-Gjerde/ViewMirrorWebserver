<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">

  <meta name="theme-color" content="#fafafa">
</head>

<body>


  <h1 style="font-family: Tahoma;" >Login </h1>
  <form action="login.php" method="post" enctype="multipart/form-data">
    <input class="form-control" type="text" name="username" input-placeholder="Username" value="" autofocus><br>
    <input type="password" name="password" input-placeholder="Password" value=""><br>
    <input name="login" type="submit" value="Login">
  </form>

  <div class="field-group">
        <div>
            <input type="checkbox" name="remember" id="remember"
                <?php if(isset($_COOKIE["member_login"])) { ?> checked
                <?php } ?> /> <label for="remember-me">Remember me</label>
        </div>
    </div>






  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>




<?php



session_start();
$host = "localhost";
$username = "view";
$password = "view_db";
$database = "view";
$message = "";


try {
    $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST["login"]))
    {
        if(empty($_POST["username"]) || empty($_POST["password"])){
            echo "all fields are required";
            echo "$connect";
           } else {
            $query = "SELECT * FROM users WHERE username = :username AND password =:password";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    'username' => $_POST["username"],
                    'password' => $_POST["password"],
                )
            );
            $count = $statement->rowCount();
            if ($count > 0) {
                $_SESSION["username"] = $_POST["username"];
                header("location: ../../index.php");
            }
            else {
                echo "Wrong initials";
            }
        }

    }

} catch (PDOException $error) {
    $message = $error->getMessage();
    echo $message;

}
