<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Zamowienia;
use Ibd\Statusy;

if (empty($_SESSION['id_uzytkownika'])) {
	header("Location: index.php");
	exit();
}

if(empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idZamowienia = $_GET['id'];
	
//$koszyk = new Koszyk();
$zamowienia = new Zamowienia();
$szczegolyZamowienia = $zamowienia->pokazSzczegolyZamowieniaAdmin($idZamowienia);

$status = $zamowienia->pobierzStatus($idZamowienia);

/*if (isset($_POST['zamow'])) {
    $idZamowienia = $zamowienia->dodaj($_SESSION['id_uzytkownika']);
    $zamowienia->dodajSzczegoly($idZamowienia, $listaKsiazek);
    $koszyk->wyczysc(session_id());

    header("Location: index.php?msg=3");
} */


$statusy = new Statusy();
$listaStatusow = $statusy->pobierzWszystkie();
//var_dump($status);

$wartoscZamowienia = 0;


 if(!empty($_POST)) {
	if($zamowienia->edytuj($_POST, $idZamowienia)) {
		header("Location: admin.zamowienie.szczegoly.php?id=$idZamowienia&msg=1");
		exit();
	}
 }

include 'admin.header.php';


?>

<h1>Szczegóły zamówienia</h1>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
	<p class="alert alert-success">Status został zmieniony.</p>
<?php endif; ?>

<form method="post" action="">

<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>Tytuł</th>
			<th>Cena jednostkwa</th>
			<th>Liczba sztuk</th>
			<th>Razem</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($szczegolyZamowienia as $pozycja): ?>
		<tr>
			<td><?=$pozycja['tytul']?></td>
			<td><?=$pozycja['cena']?></td>
			<td><?=$pozycja['liczba_sztuk']?></td>
			<td><?= $pozycja['cena'] * $pozycja['liczba_sztuk'] ?></td>
			<?php $wartoscZamowienia = $wartoscZamowienia+$pozycja['cena'] * $pozycja['liczba_sztuk'] ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
    <tfoot>
		<tr>
			<td colspan="3">&nbsp;</td>
			<td><b><?= $wartoscZamowienia ?></b></td>
		</tr>
    </tfoot>

</table>

<div class="form-group">
		<label for="id_status">Status</label>
		<select name="id_status" id="id_status" class="form-control">
			<?php foreach ($listaStatusow as $stat) : ?>
				<option value="<?= $stat['id'] ?>" <?= $status == $stat['id'] ? 'selected="selected"' : '' ?>><?= $stat['nazwa'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<button type="submit" class="btn btn-primary">Zmień status</button>
	<a href="admin.zamowienia.lista.php" class="btn btn-link">powrót</a>
	<hr />

</form>
<?php include 'admin.footer.php'; ?>