<?php

function pdo_connect($user, $password)
{
  global $MYSQL_SERVER;
  try {
    $connection = new PDO("mysql:host=".$MYSQL_SERVER, $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connection;
  } catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
  }
}

function pdo_query($connection, $query)
{
  try {
    $connection->query($query);
  } catch (PDOException $e) {
    die('pdo_query failed: ' . $e->getMessage());
  }
}

function create_db($connection)
{
  global $DB_NAME;
  try {
    $dbname = $DB_NAME;
    $dbname = "`" . str_replace("`", "``", $dbname) . "`";
    pdo_query($connection,"DROP DATABASE IF EXISTS " . $dbname);
    pdo_query($connection,"CREATE DATABASE IF NOT EXISTS $dbname");
    pdo_query($connection,"use $dbname");
  } catch (PDOException $e) {
    die('Creating database failed: ' . $e->getMessage());
  }
}

function db_connect($dsn, $user, $password)
{
  try {
    $connection = new PDO($dsn, $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "ok";
    return $connection;
  } catch (PDOException $e) {
    redirect_to(PROJECT_URL."config/setup.php");
    //die('Connection failed: ' . $e->getMessage());
  }
}

function db_disconnect($connection)
{
  if (isset($connection)) {
    $connection = null;
  }
}
