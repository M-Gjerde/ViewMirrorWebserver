<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require '../connection.php';
  getPreferences($host, $database, $username, $password);
}
 class Preference {

  private static $instance = NULL;

  private $pdo;  //added private variable for pdo


  private function __construct() {
    try {
      $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo $e->getmessage();
      die();
    }

    $this->pdo = $database; //saved the connection into the new variable
  }

  public static function getInstance() {
    static $instance = null;
    if (self::$instance === NULL) {
      $instance = new Preference();
    }
    return $instance;
  }

  //added a function to get the connection itself
  function getConnection(){
    return $this->pdo;
  }
}

function get_pdo()
{
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connection.php';
    $pdo = Preference::getInstance();
  }
}
