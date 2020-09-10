<?php
require_once 'vendor/autoload.php';
session_start();
// jesli nie podano parametru id, przekieruj do listy książek
if(empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$dane = $ksiazki->pobierzSzczegoly($id)
?>

<h2><?=$dane['tytul']?></h2>

<p>
	<a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
</p>
<p>
<?php if(!empty($dane['zdjecie'])): ?>
<img src="zdjecia/<?=$dane['zdjecie']?>" alt="<?=$dane['tytul'] ?>" class="img-thumbnail" />
<?php else: ?>
brak zdjęcia
<?php endif; ?>
<table class="table table-striped">
  <tbody>
    <tr>
      <td><b>Autor</b></td>
      <td><?=$dane['author']?></td>
    </tr>
    <tr>
      <td><b>Kategoria</b></td>
      <td><?=$dane['nazwa']?></td>
    </tr>
    <tr>
      <td><b>Cena</b></td>
      <td><?=$dane['cena']?></td>
    </tr>
    <tr>
      <td><b>Liczba stron</b></td>
      <td><?=$dane['liczba_stron']?></td>
    </tr>
    <tr>
      <td><b>ISBN</b></td>
      <td><?=$dane['isbn']?></td>
    </tr>
    <tr>
      <td><b>Opis</b></td>
      <td><?=$dane['opis']?></td>
    </tr>
  </tbody>
</table>

</p>


<?php include 'footer.php'; ?>