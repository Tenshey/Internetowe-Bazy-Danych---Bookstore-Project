<!--<?php
require_once 'vendor/autoload.php';
session_start();
include 'header.php';
?>-->


<?php include 'header.php'; ?>

<?php
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case '1':
            $msg = 'Konto zostało utworzone, możesz się zalogować.';
            break;
        case '2':
            $msg = 'Zostałeś pomyślnie wylogowany.';
            break;
        case '3':
            $msg = 'Zamówienie zostało złożone.';
            break;
        default:
            $msg = '';
    }
}
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-success"><?= $msg ?></div>
<?php endif; ?>

<h1>Witamy w księgarni internetowej</h1>

<p>
    Projekt na zaliczenie przedmiotu Internetowe Bazy Danych w roku akademickim <?=ROK_AKADEMICKI ?>.
</p>

<?php include 'footer.php'; ?>