<?php

require_once 'vendor/autoload.php';

use Ibd\Uzytkownicy;

if(empty($_GET['id'])) {
    header("Location: admin.uzytkownicy.lista.php");
    exit();
} else {
    $id = (int)$_GET['id'];
}

$uzytk = new Uzytkownicy();

if(!empty($_POST)) {
	if($uzytk->edytuj($_POST, $id)) {
		header("Location: admin.uzytkownicy.edycja.php?id=$id&msg=1");
		exit();
	}
} else {
	$uzytk->setDane($uzytk->pobierz($id));
}

include 'admin.header.php';



?>

<h2>
	Użytkownicy
	<small>edycja</small>
</h2>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Użytkownik został zapisany.</p>
<?php endif; ?>

<form method="post" action="">
<div class="form-group">
        <label for="imie">Imię</label>
        <input type="text" id="imie" name="imie" class="form-control <?= ($uzytk->blad('imie') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('imie') ?>"/>
    </div>
    <div class="form-group">
        <label for="nazwisko">Nazwisko</label>
        <input type="text" id="nazwisko" name="nazwisko" class="form-control <?= ($uzytk->blad('nazwisko') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('nazwisko') ?>"/>
    </div>
    <div class="form-group">
        <label for="adres">Adres</label>
        <input type="text" id="adres" name="adres" class="form-control <?= ($uzytk->blad('adres') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('adres') ?>"/>
    </div>
    <div class="form-group">
        <label for="telefon">Telefon</label>
        <input type="text" id="telefon" name="telefon" class="form-control <?= ($uzytk->blad('telefon') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('telefon') ?>"/>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" class="form-control <?= ($uzytk->blad('email') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('email') ?>"/>
    </div>
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" class="form-control <?= ($uzytk->blad('login') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('login') ?>" readonly />
    </div>
    <div class="form-group">
        <label for="haslo">Hasło</label>
        <input type="password" id="haslo" name="haslo" class="form-control <?= ($uzytk->blad('haslo') == 1) ? 'is-invalid' : '' ?>" />
    </div>
	<div class="form-group">
		<label for="login">Grupa</label>
		<select name="grupa" id="grupa" class="form-control <?= ($uzytk->blad('login') == 1) ? 'is-invalid' : '' ?>">
			<option <?= $uzytk->dane('grupa') == 'użytkownik' ? 'selected' : '' ?> >użytkownik</option>
			<option <?= $uzytk->dane('grupa') == 'administrator' ? 'selected' : '' ?> >administrator</option>
		</select>
	</div>
	
    <button type="submit" class="btn btn-primary">Zapisz</button>
	<a href="admin.uzytkownicy.lista.php" class="btn btn-link">powrót</a>
</form>

<?php include 'admin.footer.php'; ?>