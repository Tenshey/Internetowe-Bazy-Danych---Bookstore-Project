<?php

namespace Ibd;

class Ksiazki
{
	/**
	 * Instancja klasy obsługującej połączenie do bazy.
	 *
	 * @var Db
	 */
	private $db;

	/**
	 * Domyślna tablica z błędami, zawiera nazwy wymaganych pól
	 *
	 * @var array
	 */
	private $bledy = [
		'tytul' => 0,
		'id_kategorii' => 0,
		'id_autora' => 0,
		'cena' => 0,
		'liczba_stron' => 0,
		'isbn' => 0
	];

	/**
	 * Przechowuje dane z formularza dodawania.
	 *
	 * @var array
	 */
	private $dane = [];

	function setDane($dane)
	{
		$this->dane = $dane;
	}
	
	public function __construct()
	{
		$this->db = new Db();
	}

	/**
	 * Pobiera wszystkie książki.
	 *
	 * @return array
	 */
	public function pobierzWszystkie()
	{
		$sql = "SELECT zdjecie, tytul, k.id, concat(imie, ' ', nazwisko) as author, c.nazwa, k.cena from ksiazki k join autorzy a on k.id_autora=a.id join kategorie c ON k.id_kategorii=c.id";

		return $this->db->pobierzWszystko($sql);
	}

	/**
	 * Zwraca wartość błędu dla określonego pola.
	 *
	 * @param string $pole
	 * @return string|bool
	 */
	public function blad($pole)
	{
		if (isset($this->bledy[$pole])) {
			return $this->bledy[$pole];
		} else {
			return false;
		}
	}

	/**



	
	 * Pobiera dane książki o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function pobierz($id)
	{
		return $this->db->pobierz('ksiazki', $id);
	}

	/**
	 * Zwraca wpisaną wartość dla określonego pola (lub wszystkie)
	 *
	 * @param string $pole
	 * @return string
	 */

	public function dane($pole = null)
	{
		if (is_null($pole)) {
			return $this->dane;
		}
			
		
		if (isset($this->dane[$pole])) {
			return $this->dane[$pole];
		} else {
			return '';
		}
	}
	
	public function waliduj($dane)
    {
        // usuwanie spacji z początku i końca
        foreach ($dane as $kl => $wart)
            $dane[$kl] = trim($wart);

        $this->dane = $dane;
        if (empty($dane['tytul']))
            $this->bledy['tytul'] = 1;
        if (empty($dane['id_kategorii']))
            $this->bledy['id_kategorii'] = 1;
        if (empty($dane['id_autora']))
            $this->bledy['id_autora'] = 1;
        if (empty($dane['cena']))
            $this->bledy['cena'] = 1;
        if (empty($dane['isbn']))
            $this->bledy['isbn'] = 1;
        if (empty($dane['liczba_stron']))
            $this->bledy['liczba_stron'] = 1;

        return $this->bledy;
	}


	
	public function czySaBledy()
    {
        return array_sum($this->bledy) > 0;
    }

	


	public function pobierzSzczegoly($id)
	{
		return $this->db->pobierzSzczeg($id);
	}

	/*
	 * Pobiera wszystkie książki.
	 *
	 * @return array
	 */
	/*public function pobierzWszystkie()
	{
		$sql = "
			SELECT k.*, CONCAT(a.imie, ' ', a.nazwisko) AS autor, kat.nazwa AS kategoria
			FROM ksiazki k 
			JOIN autorzy a ON k.id_autora = a.id
			JOIN kategorie kat ON k.id_kategorii = kat.id
			";

		return $this->db->pobierzWszystko($sql);
	}
	*/

	/**



	
	 * Pobiera najlepiej sprzedające się książki.
	 * 
	 */
	public function pobierzBestsellery()
	{
		$sql = "SELECT zdjecie, tytul, opis, k.id, concat(imie, ' ', nazwisko) as author, c.nazwa, k.cena, sum(zs.liczba_sztuk) from ksiazki k join autorzy a on k.id_autora=a.id join kategorie c ON k.id_kategorii=c.id join zamowienia_szczegoly zs on k.id=zs.id_ksiazki GROUP by k.id order by sum(zs.liczba_sztuk) DESC LIMIT 5";

		return $this->db->pobierzWszystko($sql);
	}





	/**
	 * Pobiera zapytanie SELECT oraz jego parametry;
	 *
	 * @return array
	 */
	public function pobierzZapytanie($params)
	{
		$parametry = [];
		$sql = "SELECT zdjecie, tytul, k.id, concat(imie, ' ', nazwisko) as author, c.nazwa, k.cena from ksiazki k join autorzy a on k.id_autora=a.id join kategorie c ON k.id_kategorii=c.id WHERE 1=1 ";

		// dodawanie warunków do zapytanie
		if (!empty($params['fraza'])) {
		$sql = "SELECT zdjecie, tytul, k.id, concat(a.imie, ' ', a.nazwisko) as author, c.nazwa, k.cena from ksiazki k join autorzy a on k.id_autora=a.id join kategorie c ON k.id_kategorii=c.id WHERE 1=1 ";
			$sql .= "AND (k.tytul LIKE :fraza OR opis like :fraza or concat(imie, ' ', nazwisko) LIKE :fraza)";
			$parametry['fraza'] = "%$params[fraza]%";
		}
		if (!empty($params['id_kategorii'])) {
			$sql .= "AND k.id_kategorii = :id_kategorii ";
			$parametry['id_kategorii'] = $params['id_kategorii'];
		}
		
		// dodawanie sortowania
		if (!empty($params['sortowanie'])) {
			$kolumny = ['k.tytul', 'k.cena', 'a.nazwisko'];
			$kierunki = ['ASC', 'DESC'];
			list($kolumna, $kierunek) = explode(' ', $params['sortowanie']);
			
			if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
				$sql .= " ORDER BY " . $params['sortowanie'];
			}
		}
		//var_dump($sql);
		return ['sql' => $sql, 'parametry' => $parametry];
	}

/**
	 * Pobiera stronę z danymi książek.
	 * 
	 * @param string $select
	 * @param array $params
	 * @return array
	 */
	public function pobierzStrone($select, $params)
	{
		return $this->db->pobierzWszystko($select, $params);
	}

	/**
	 * Pobiera dane książki o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	/*public function pobierz($id)
	{
		return $this->db->pobierz('ksiazki', $id);
	}
	*/

	/**
	 * Pobiera najlepiej sprzedające się książki.
	 * 
	 */
	/*public function pobierzBestsellery()
	{
		$sql = "SELECT * FROM ksiazki ORDER BY RAND() LIMIT 5";

		// uzupełnić funkcję
	}
*/

	/**
	 * Dodaje książkę do bazy.
	 *
	 * @param array $dane
	 * @param array $pliki Dane wgrywanego pliku z okładką
	 * @return int
	 */
	public function dodaj($dane, $pliki)
	{
		$id = $this->db->dodaj('ksiazki', [
			'id_autora' => $dane['id_autora'],
			'id_kategorii' => $dane['id_kategorii'],
			'tytul' => $dane['tytul'],
			'opis' => $dane['opis'],
			'cena' => $dane['cena'],
			'liczba_stron' => $dane['liczba_stron'],
			'isbn' => $dane['isbn']
		]);

		$rozszerzenie = strtolower(pathinfo($pliki['zdjecie']['name'], PATHINFO_EXTENSION));
		
		if (!empty($pliki['zdjecie']['name']) && $rozszerzenie == 'jpg') {
			// zostal wybrany plik ze zdjeciem do uploadu
			if($this->wgrajPlik($pliki, $id)) {
				$this->db->aktualizuj('ksiazki', ['zdjecie' => "$id.jpg"], $id);
			}
		}

		return $id;
	}

	/**
	 * Wgrywa plik ze zdjęciem na serwer.
	 *
	 * @param array $pliki
	 * @param int $idKsiazki
	 * @return bool
	 */
	public function wgrajPlik($pliki, $idKsiazki)
	{
		$nazwa = $idKsiazki . "_org.jpg";
		
		if ($a=move_uploaded_file($pliki['zdjecie']['tmp_name'], "zdjecia/$nazwa")) {
			$this->stworzMiniature($nazwa, $idKsiazki);
			return true;
		}
		
		return false;
	}

	/**
	 * Tworzy miniaturę wgrywanego zdjęcia.
	 *
	 * @param string $nazwa
	 * @param int $idKsiazki
	 * @param int $szerokosc
	 */
	public function stworzMiniature($nazwa, $idKsiazki, $szerokosc = 46)
	{
		$img = imagecreatefromjpeg("zdjecia/$nazwa");
		$width = imagesx($img);
		$height = imagesy($img);
		$newWidth = $szerokosc;
		$newHeight = floor($height * ( $szerokosc / $width ));

		$tmpImg = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresized($tmpImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		imagejpeg($tmpImg, "zdjecia/$idKsiazki.jpg");
	}

	/**
	 * Zmienia dane książki.
	 *
	 * @param array $dane
	 * @param int $id
	 * @return bool
	 */
	public function edytuj($dane, $id, $pliki)
	{
		$update = [
			'id_autora' => $dane['id_autora'],
			'id_kategorii' => $dane['id_kategorii'],
			'tytul' => $dane['tytul'],
			'opis' => $dane['opis'],
			'cena' => $dane['cena'],
			'liczba_stron' => $dane['liczba_stron'],
			'isbn' => $dane['isbn']
		];

		$rozszerzenie = strtolower(pathinfo($pliki['zdjecie']['name'], PATHINFO_EXTENSION));
		
		if (!empty($pliki['zdjecie']['name']) && $rozszerzenie == 'jpg') {
			// zostal wybrany plik ze zdjeciem do uploadu
			if ($this->wgrajPlik($pliki, $id)) {
				$update['zdjecie'] = "$id.jpg";
			}
		}

		return $this->db->aktualizuj('ksiazki', $update, $id);
	}

	/**
	 * Usuwa książkę.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function usun($id)
	{
		if(file_exists("zdjecia/$id.jpg")) {
			unlink("zdjecia/$id.jpg");
		}
		if (file_exists("zdjecia/" . $id . "_org.jpg")) {
			unlink("zdjecia/" . $id . "_org.jpg");
		}

		return $this->db->usun('ksiazki', $id);
	}

}
