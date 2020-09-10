<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Uzytkownicy;

$uzytkownicy = new Uzytkownicy();

if (isset($_POST['zapisz'])) {
    $uzytkownicy->waliduj($_POST);

    if (!$uzytkownicy->czySaBledy()) {
        // brak błędów, można dodać użytkownika
        $dane = $uzytkownicy->dane();
        $uzytkownicy->dodaj($dane);
        header("Location: index.php?msg=1");
    }
}

include 'header.php';
?>

<h1>Rejestracja</h1>

<?php if ($uzytkownicy->czySaBledy()): ?>
    <div class="alert alert-danger">
        <strong>Wystąpił błąd</strong><br/>
        Proszę wypełnić zaznaczone pola.
    </div>
<?php endif; ?>
<?php if($uzytkownicy->loginZajety()): ?>
<div class="alert alert-danger">
    <strong>Wystąpił błąd</strong><br/>
    Podany login jest juz uzyty.
</div>
<?php endif; ?>
<?php if($uzytkownicy->emailZajety()): ?>
<div class="alert alert-danger">
    <strong>Wystąpił błąd</strong><br/>
    Podany e-mail jest juz uzyty.
</div>
<?php endif; ?>



<form method="post" action="">
    <div class="form-group">
        <label for="imie">Imię</label>
        <input type="text" id="imie" name="imie" class="form-control <?= ($uzytkownicy->blad('imie') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('imie') ?>"/>
    </div>
    <div class="form-group">
        <label for="nazwisko">Nazwisko</label>
        <input type="text" id="nazwisko" name="nazwisko" class="form-control <?= ($uzytkownicy->blad('nazwisko') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('nazwisko') ?>"/>
    </div>
    <div class="form-group">
        <label for="adres">Adres</label>
        <input type="text" id="adres" name="adres" class="form-control <?= ($uzytkownicy->blad('adres') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('adres') ?>"/>
    </div>
    <div class="form-group">
        <label for="telefon">Telefon</label>
        <input type="text" id="telefon" name="telefon" class="form-control <?= ($uzytkownicy->blad('telefon') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('telefon') ?>"/>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" class="form-control <?= ($uzytkownicy->blad('email') == 1 || $uzytkownicy->blad('email') == 4) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('email') ?>"/>
    </div>
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" class="form-control <?= ($uzytkownicy->blad('login') == 1 || $uzytkownicy->blad('login') == 3) ? 'is-invalid' : '' ?>" value="<?= $uzytkownicy->dane('login') ?>"/>
    </div>
    <div class="form-group">
        <label for="haslo">Hasło</label>
        <input type="password" id="haslo" name="haslo" class="form-control <?= ($uzytkownicy->blad('haslo') == 1) ? 'is-invalid' : '' ?>" />
    </div>

    <input type="submit" name="zapisz" id="zapisz" class="btn btn-primary" value="Zarejestruj się" /><br/><br/>
</form>

<?php include 'footer.php'; ?>