<?php
require_once 'vendor/autoload.php';
use Ibd\Uzytkownicy;

$uzytk = new Uzytkownicy();

if (!empty($_POST)) {
	$uzytk->waliduj($_POST);

    if (!$uzytk->czySaBledy()) {
		if ($uzytk->dodaj($_POST, $_POST['grupa'])) {
			header("Location: admin.uzytkownicy.lista.php?msg=1");
		}
	}
}

$select = $uzytk->pobierzSelect();
$lista = $uzytk->pobierzWszystko($select);

include 'admin.header.php';
?>

<h2>
	Użytkownicy
	<small><a href="#" id="aDodajUzytkownika">dodaj</a></small>
</h2>

<form method="post" action="" id="fDodajUzytkownika" class="mb-3">
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
        <input type="text" id="login" name="login" class="form-control <?= ($uzytk->blad('login') == 1) ? 'is-invalid' : '' ?>" value="<?= $uzytk->dane('login') ?>"/>
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
	
    <button type="submit" class="btn btn-primary">Dodaj</button>
</form>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
	<p class="alert alert-success">Użytkownik został dodany.</p>
<?php endif; ?>

<table id="autorzy" class="table table-striped">
	<thead>
		<tr>
			<th>Id</th>
			<th>Imię</th>
			<th>Nazwisko</th>
			<th>Login</th>
			<th>Grupa</th>
			<th>Email</th>
			<th>Telefon</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $a): ?>
			<tr>
				<td><?= $a['id'] ?></td>
				<td><?= $a['imie'] ?></td>
				<td><?= $a['nazwisko'] ?></td>
				<td><?= $a['login'] ?></td>
				<td><?= $a['grupa'] ?></td>
				<td><?= (empty($a['email'])) ? '-' : $a['email'] ?></td>
				<td><?= (empty($a['telefon'])) ? '-' : $a['telefon'] ?></td>
				<td>
					<a href="admin.uzytkownicy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujUzytkownika"><em class="fas fa-pencil-alt"></em></a>
					<a href="admin.uzytkownicy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunUzytkownika"><em class="fas fa-trash"></em></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php include 'admin.footer.php'; ?>