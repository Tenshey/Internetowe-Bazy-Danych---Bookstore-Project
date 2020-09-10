<?php
require_once 'vendor/autoload.php';
include 'admin.header.php';

use Ibd\Ksiazki;
use Ibd\Kategorie;
use Ibd\Autorzy;

$ksiazki = new Ksiazki();

if (!empty($_POST)) {
	$ksiazki->waliduj($_POST);

	if (!$ksiazki->czySaBledy()) {
		if ($ksiazki->dodaj($_POST, $_FILES)) {
			header("Location: admin.ksiazki.lista.php");
		}
	}
}

$select = $ksiazki->pobierzZapytanie([]);
$lista = $ksiazki->pobierzWszystkie($select);

// pobieranie kategorii
$kategorie = new Kategorie();
$listaKategorii = $kategorie->pobierzWszystkie();

$autorzy = new Autorzy();
$listaAutorow = $autorzy->pobierzWszystko("SELECT * FROM autorzy");
?>

<h2>
	Książki
	<small><a href="#" id="aDodajKsiazke">dodaj</a></small>
</h2>

<form method="post" action="" id="fDodajKsiazke" enctype="multipart/form-data">
	<div class="form-group">
		<label for="tytul">Tytuł</label>
		<input type="text" id="tytul" name="tytul" class="form-control <?= ($ksiazki->blad('tytul') == 1) ? 'is-invalid' : '' ?>" value="<?= $ksiazki->dane('tytul') ?>" />
	</div>
	<div class="form-group">
		<label for="id_kategorii">Kategoria</label>
		<select name="id_kategorii" id="id_kategorii" class="form-control <?= ($ksiazki->blad('id_kategorii') == 1) ? 'is-invalid' : '' ?>">
			<?php foreach ($listaKategorii as $kat) : ?>
				<option value="<?= $kat['id'] ?>" <?= $ksiazki->dane('id_kategorii') == $kat['id'] ? 'selected="selected"' : '' ?>><?= $kat['nazwa'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label for="id_autora">Autor</label>
		<select name=" id_autora" id="id_autora" class="form-control <?= ($ksiazki->blad('id_autora') == 1) ? 'is-invalid' : '' ?>">
			<?php foreach ($listaAutorow as $a) : ?>
				<option value="<?= $a['id'] ?>" <?= $ksiazki->dane('id_autora') == $a['id'] ? 'selected="selected"' : '' ?>><?= $a['imie'] . ' ' . $a['nazwisko'] ?></option>
			<?php endforeach; ?>
			</select>
	</div>
	<div class="form-group">
		<label for="cena">Cena</label>
		<input type="text" id="cena" name="cena" class="form-control <?= ($ksiazki->blad('cena') == 1) ? 'is-invalid' : '' ?>" value="<?= $ksiazki->dane('cena') ?>" />
	</div>
	<div class="form-group">
		<label for="liczba_stron">Liczba stron</label>
		<input type="text" id="liczba_stron" name="liczba_stron" class="form-control <?= ($ksiazki->blad('liczba_stron') == 1) ? 'is-invalid' : '' ?>" value="<?= $ksiazki->dane('liczba_stron') ?>" />
	</div>
	<div class="form-group">
		<label for="isbn">ISBN</label>
		<input type="text" id="isbn" name="isbn" class="form-control <?= ($ksiazki->blad('isbn') == 1) ? 'is-invalid' : '' ?>" value="<?= $ksiazki->dane('isbn') ?>" />
	</div>
	<div class="form-group">
		<label for="opis">Opis</label>
		<textarea name="opis" id="opis" class="form-control <?= ($ksiazki->blad('opis') == 1) ? 'is-invalid' : '' ?>"><?= $ksiazki->dane('opis') ?></textarea>
	</div>
	<div class="form-group">
		<label for="zdjecie">Zdjęcie okładki (format JPG)</label>
		<input type="file" id="zdjecie" name="zdjecie" class="form-control <?= ($ksiazki->blad('zdjecie') == 1) ? 'is-invalid' : '' ?>" />
	</div>

	<button type="submit" class="btn btn-primary">Dodaj</button>
	<hr />
</form>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1) : ?>
	<p class="alert alert-success">Książka została dodana.</p>
<?php endif; ?>

<table id="ksiazki" class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Id</th>
			<th>Tytuł</th>
			<th>Autor</th>
			<th>Kategoria</th>
			<th>Cena PLN</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $ks) : ?>
			<tr>
				<td>
					<?php if (!empty($ks['zdjecie'])) : ?>
						<img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" />
					<?php else : ?>
						brak zdjęcia
					<?php endif; ?>
				</td>
				<td><?= $ks['id'] ?></td>
				<td><?= $ks['tytul'] ?></td>
				<td><?= $ks['author'] ?></td>
				<td><?= $ks['nazwa'] ?></td>
				<td><?= $ks['cena'] ?></td>
				<td>
					<a href="admin.ksiazki.edycja.php?id=<?= $ks['id'] ?>" title="edycja" class="aEdytujKsiazke"><em class="fas fa-pencil-alt"></em></a>
					<a href="admin.ksiazki.usun.php?id=<?= $ks['id'] ?>" title="usuń" class="aUsunKsiazke"><em class="fas fa-trash"></em></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php include 'admin.footer.php'; ?>