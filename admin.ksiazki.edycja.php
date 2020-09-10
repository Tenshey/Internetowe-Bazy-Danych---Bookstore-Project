<?php

require_once 'vendor/autoload.php';

use Ibd\Ksiazki;
use Ibd\Kategorie;
use Ibd\Autorzy;

if (empty($_GET['id'])) {
    header("Location: admin.ksiazki.lista.php");
    exit();
} else {
    $id = (int)$_GET['id'];
}

$ksiazki = new Ksiazki();

if (!empty($_POST)) {
   if ($ksiazki->edytuj($_POST, $id, $_FILES)) {
       header("Location: admin.ksiazki.edycja.php?id=$id&msg=1");
       exit();
   }
}

include 'admin.header.php';

$dane = $ksiazki->pobierz($id);
$ksiazki->setDane($dane);

// pobieranie kategorii
$kategorie = new Kategorie();
$listaKategorii = $kategorie->pobierzWszystkie();

$autorzy = new Autorzy();
$listaAutorow = $autorzy->pobierzWszystko("SELECT * FROM autorzy");
?>

<h2>
	Książki
	<small>edycja</small>
</h2>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
	<p class="alert alert-success">Książka została zapisana.</p>
<?php endif; ?>

<form method="post" action="" enctype="multipart/form-data">
	<?php if(!empty($dane['zdjecie'])): ?>
		<div>
			<img src="zdjecia/<?=$dane['zdjecie']?>?<?=time()?>" alt="<?=$ks['tytul']?>" />
		</div>
	<?php endif; ?>

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

	<button type="submit" class="btn btn-primary">Zapisz</button>
	<a href="admin.ksiazki.lista.php" class="btn btn-link">powrót</a>
	<hr />
</form>

<?php include 'admin.footer.php'; ?>