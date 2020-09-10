<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Zamowienia;

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
$szczegolyZamowienia = $zamowienia->pokazSzczegolyZamowienia($_SESSION['id_uzytkownika'], $idZamowienia);
/*if (isset($_POST['zamow'])) {
    $idZamowienia = $zamowienia->dodaj($_SESSION['id_uzytkownika']);
    $zamowienia->dodajSzczegoly($idZamowienia, $listaKsiazek);
    $koszyk->wyczysc(session_id());

    header("Location: index.php?msg=3");
} */

$wartoscZamowienia = 0;

include 'header.php';
?>

<h1>Szczegóły zamówienia</h1>

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

</form>
<?php include 'footer.php'; ?>