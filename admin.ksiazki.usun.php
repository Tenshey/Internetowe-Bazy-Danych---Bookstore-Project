<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
session_start();

require_once 'classes/Db.php';
require_once 'classes/Ksiazki.php';

if (isset($_POST)) {
    $ksiazki = new Ksiazki();
    if ($ksiazki->usun($_GET['id'])) {
      echo 'ok';
    }
}