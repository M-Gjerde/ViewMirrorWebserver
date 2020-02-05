<?php
session_start();

if (!isset($_SESSION["username"])){
  header("Location: /auth/login.php");
}

 ?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/style.css">

  <script src="js/modules.js"> </script>

  <meta name="theme-color" content="#fafafa">
</head>
<body>
    <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
    <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
  </script>
    <script src="https://www.google-analytics.com/analytics.js" async></script>


<div id="wrapper"> </div>
<div id="button">



</div>
<script src="js/grid.js"></script>

</body>

</html>
