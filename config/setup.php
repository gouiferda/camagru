<?php

require_once("../app/functions/http_auth.php");
require_http_auth();
require_once('env.vars.php');
require_once('../app/env.php');
require_once('database.php');
require_once("../app/functions/database.php");
require_once("../app/functions/validation.php");
require_once("../app/functions/cookies.php");
require_once("../app/functions/theme.php");
require_once("../app/classes/DatabaseObject.class.php");
require_once("../app/classes/User.class.php");
require_once("../app/classes/Session.class.php");

$page_title = "Setup";
$sidebar_title = "Sidebar";
$sidebar = "Welcome to Camagru";


$output = "";

$output .= "<h1>Setup page</h1>";

$output .= "<br>";
//create database
$output .= "<p>Creating camagru mysql database <input disabled type='checkbox' checked /> </p>";

$connection = pdo_connect($DB_USER, $DB_PASSWORD);
create_db($connection);

$database = db_connect($DB_DSN, $DB_USER, $DB_PASSWORD);
DatabaseObject::set_database($database);

$sql_commands = [];

//create tables
$tables = [
  "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `username`  varchar(100)  NOT NULL,
      `email`  varchar(300)  NOT NULL,
      `password` varchar(200) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `is_activated` int(11) NOT NULL DEFAULT '0',
      `first_name` varchar(100) NOT NULL,
      `last_name` varchar(100) NOT NULL,
      `activation_code` varchar(100)  NOT NULL DEFAULT '',
      `requested_reset` int(11)  NOT NULL DEFAULT '0',
      `reset_code` varchar(100)  NOT NULL DEFAULT '',
      `receive_notifications` int(11)  NOT NULL DEFAULT '1'
    ) CHARACTER SET utf8 COLLATE utf8_general_ci",
  "posts" => "CREATE TABLE IF NOT EXISTS posts (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `title` varchar(200) NOT NULL,
      `image` varchar(300) NOT NULL,
      `user_id` int(11) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8 COLLATE utf8_general_ci",
  "comments" => "CREATE TABLE IF NOT EXISTS comments (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `comment` text NOT NULL,
      `user_id` int(11) NOT NULL,
      `post_id` int(11) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8 COLLATE utf8_general_ci",
  "likes" => "CREATE TABLE IF NOT EXISTS likes (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `user_id` int(11) NOT NULL,
      `post_id` int(11) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8 COLLATE utf8_general_ci",
];


// $output .= "Using credentials in config/database.php";
// $output .= "<br>";
$output .= "<p>Creating database tables:</p>";

$output .= "<ul>";
foreach ($tables as $table => $table_req) {
  $output .= "<li>";
  $output .= $table . " ";
  $output .= (DatabaseObject::pdo_query($table_req)) ?  "<input disabled type='checkbox' checked />" : "[X]";
  $output .= "</li>";
}
$output .= "</ul>";


//insert to tables
$user = new User([
  "first_name" => "user",
  "last_name" => "onee",
  "username" => "user1",
  "email" => "user1@email.com",
  "password" => "helloWorld10$$",
  "is_activated" => "1",
]);

//creating user
$output .= "<p>Creating a new user:</p>";
$output .= "<p>";
$output .= "<span class='badge badge-primary'>" . $user->username . "</span>";
$output .= "&nbsp;<span class='badge badge-primary'>" . $user->password . "</span> ";
$output .= ($user->save()) ? "<input disabled type='checkbox' checked />" : "[X]";
$output .= "</p>";

$output .= "<br>";

$output .= "<div class='alert alert-dismissible alert-primary'>";
$output .= "<strong>Finished setup! now you can sign in or sign up</strong> - <a href='" . PROJECT_URL . "home' class='alert-link'>Click here to go home</a>";
$output .= "</div>";

//echo $output;
$body = $output;
$output = "";


$files = glob('../uploads/*');
foreach ($files as $file) {
  if (is_file($file))
    unlink($file);
}

// $stmt = null;
db_disconnect($database);

$session = new Session;
$session->logout();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="camagru">
  <meta name="author" content="sgouifer">
  <link rel="icon" href="<?php echo PROJECT_URL; ?>assets/favicon/favicon.ico">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo PROJECT_URL; ?>assets/css/<?php echo BOOTSTRAP; ?>">
  <link rel="stylesheet" href="<?php echo PROJECT_URL; ?>assets/css/fontawesome.css">
  <!-- Custom styles for this template -->
  <link href="<?php echo PROJECT_URL; ?>assets/css/custom.css" rel="stylesheet">

  <title><?php echo $page_title; ?></title>
</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo PROJECT_URL; ?>home" style='font-family: LeckerliOne;'><i class="fas fa-camera-retro"></i> Camagru</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample04">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo PROJECT_URL; ?>home">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo PROJECT_URL; ?>signup">Sign up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo PROJECT_URL; ?>signin">Sign in</a>
        </li>
      </ul>
    </div>
  </nav>

  <main role="main">


    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-9">
            <?php
            echo $body;
            ?>

          </div>
          <div class="col-lg-3">
            <?php
            include_once("../app/views/shared/sidebar.php");
            ?>
          </div>

        </div>
      </div>
    </div>

  </main>

  <?php
  include_once("../app/views/shared/footer.php");
  include_once("../app/views/shared/scripts.php");
  ?>


</body>


</html>