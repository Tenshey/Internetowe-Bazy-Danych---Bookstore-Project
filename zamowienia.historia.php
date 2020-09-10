<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Zamowienia;

if (empty($_SESSION['id_uzytkownika'])) {
	header("Location: index.php");
	exit();
}
	
//$koszyk = new Koszyk();
$zamowienia = new Zamowienia();
$listaZamowien = $zamowienia->pokazHistorieZamowien($_SESSION['id_uzytkownika']);

/*if (isset($_POST['zamow'])) {
    $idZamowienia = $zamowienia->dodaj($_SESSION['id_uzytkownika']);
    $zamowienia->dodajSzczegoly($idZamowienia, $listaKsiazek);
    $koszyk->wyczysc(session_id());

    header("Location: index.php?msg=3");
} */

include 'header.php';
?>

<h1>Historia zamówień</h1>

<form method="post" action="">

<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>Numer zamówienia</th>
			<th>Status</th>
			<th>Data dodania</th>
			<th>Szczegóły</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaZamowien as $zamowienie): ?>
		<tr>
			<td><?=$zamowienie['nr_zamowienia']?></td>
			<td><?=$zamowienie['status']?></td>
			<td><?=$zamowienie['data_dodania']?></td>
			<td><a href="zamowienie.szczegoly.php?id=<?=md5($zamowienie['nr_zamowienia'])?>" title="szczegóły"><i class="fas fa-folder-open"></i></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
   <!-- <tfoot>
		<tr>
			<td colspan="7" class="text-right">
				<input type="submit" name="zamow" class="btn btn-primary btn-sm" value="Złóż zamówienie" />
				<a href="koszyk.lista.php" class="btn btn-link btn-sm">Powrót do koszyka</a>
			</td>
		</tr>
    </tfoot> -->

</table>

</form>

<?php include 'footer.php'; ?>