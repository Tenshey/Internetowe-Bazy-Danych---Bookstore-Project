<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Uzytkownicy;

if (!empty($_POST)) {
    $uzytkownicy = new Uzytkownicy();
    $uzytkownicy->zaloguj($_POST['login'], $_POST['haslo'], 'użytkownik');
    header("Location: $_POST[powrot]");
    exit();
}

header("Location: index.php");