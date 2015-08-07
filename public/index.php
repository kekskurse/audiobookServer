<?php
session_start();
include(__DIR__."/../vendor/autoload.php");
include(__DIR__."/../helper/url.php");
include(__DIR__."/../config/config.php");


if (!isset($_SERVER['PHP_AUTH_USER'])) {
  header('WWW-Authenticate: Basic realm="Please Login"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'Text, der gesendet wird, falls der Benutzer auf Abbrechen drückt';
  exit;
} else {
  $id = $user->checkUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
  if($id!==false)
  {
    $_SESSION["id"] = $id;
  }
}
if(!isset($_SESSION["id"]))
{
    echo "Auth wrong";
    #var_dump($_SESSION);
    header('WWW-Authenticate: Basic realm="Login wrong or expied"');
    header('HTTP/1.0 401 Unauthorized');
    exit();
}
session_write_close();
$app = new \Slim\Slim([]);
include(__DIR__."/../controller/v1/index.php");

$app->fastAccess = $fastAccess;
$app->slowAccess = $slowAccess;
$app->storage = $storage;
$app->enabledMirror = $enabledMirror;
$app->run();
?>
