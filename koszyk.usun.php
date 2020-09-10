<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
session_start();
require_once 'vendor/autoload.php';

//require_once 'classes/Db.php';
//require_once 'classes/Koszyk.php';

use Ibd\Koszyk;

$koszyk = new Koszyk();

if(isset($_POST['id_koszyka'])) {
    $koszyk->zmienLiczbeSztuk([$_POST['id_koszyka'] => 0]);
    echo 'ok';
    header("Location: koszyk.lista.php");
}

//header("Location: koszyk.lista.php");