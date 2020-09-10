<?php

require_once 'vendor/autoload.php';

use Ibd\Autorzy;
use Ibd\Stronicowanie;

$autorzy = new Autorzy();

if (!empty($_POST)) {
    $autorzy = new Autorzy();
    if ($autorzy->dodaj($_POST)) {
        header("Location: admin.autorzy.lista.php?msg=1");
    }
}

$zapytanie = $autorzy->pobierzZapytanie($_GET);

// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new Stronicowanie($_GET, $zapytanie['parametry']);
$linki = $stronicowanie->pobierzLinki($zapytanie['sql'], 'admin.autorzy.lista.php');
//$select = $autorzy->pobierzSelect();
$select = $stronicowanie->dodajLimit($zapytanie['sql']);
//$lista = $autorzy->pobierzWszystko($select);
$lista = $autorzy->pobierzStrone($select, $zapytanie['parametry']);

include 'admin.header.php';
?>

<h2>
    Autorzy
    <small><a href="#" id="aDodajAutora">dodaj</a></small>
</h2>

<form method="post" action="" id="fDodajAutora" class="form-inline mb-3">
    <input type="text" placeholder="Imię" name="imie" class="form-control mr-1" />
    <input type="text" placeholder="Nazwisko" name="nazwisko" class="form-control mr-1" />
    <button type="submit" class="btn btn-primary">Dodaj</button>
</form>

<form method="get" action="" class="form-inline mb-4">
	<input type="text" name="fraza" placeholder="szukaj" class="form-control form-control-sm mr-2" value="<?=$_GET['fraza'] ?? '' ?>" />
	
	<select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
		<option value="">sortowanie</option>
		<option value="a.nazwisko ASC"
			<?=($_GET['sortowanie'] ?? '') == 'a.nazwisko ASC' ? 'selected' : '' ?>
		>nazwisku rosnąco</option>
		<option value="a.nazwisko DESC"
			<?=($_GET['sortowanie'] ?? '') == 'a.nazwisko DESC' ? 'selected' : '' ?>
        >nazwisku malejąco</option>
        <option value="a.imie ASC"
			<?=($_GET['sortowanie'] ?? '') == 'a.imie ASC' ? 'selected' : '' ?>
		>imieniu rosnąco</option>
		<option value="a.imie DESC"
			<?=($_GET['sortowanie'] ?? '') == 'a.imie DESC' ? 'selected' : '' ?>
		>imieniu malejąco</option>
	</select>
	
	<button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
</form>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Autor został dodany.</p>
<?php endif; ?>

<table id="autorzy" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Liczba powiązanych książek</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['imie'] ?></td>
                <td><?= $a['nazwisko'] ?></td>
                <td><?= $a['liczba'] ?></td>
                <td>
                    <a href="admin.autorzy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujAutora"><em class="fas fa-pencil-alt"></em></a>
                    <?php if ($a['liczba']=='0'): ?>
                        <a href="admin.autorzy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora"><em class="fas fa-trash"></em></a>
                    <?php endif; ?>
                    </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav class="text-center">
    <?=$linki?>
</nav>

<?php include 'admin.footer.php'; ?>