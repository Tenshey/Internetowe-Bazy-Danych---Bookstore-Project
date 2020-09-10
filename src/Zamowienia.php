<?php

namespace Ibd;

class Zamowienia
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Dodaje zamówienie.
     * 
     * @param int $idUzytkownika
     * @return int Id zamówienia
     */
    public function dodaj($idUzytkownika)
    {
        return $this->db->dodaj('zamowienia', [
            'id_uzytkownika' => $idUzytkownika,
            'id_statusu' => 1
        ]);
    }

    public function pokazHistorieZamowien($idUzytkownika)
    {
        $parmsEmpty = [];
        $sql = "SELECT z.id as 'nr_zamowienia', nazwa as 'status', data_dodania FROM zamowienia as z JOIN zamowienia_statusy as zs ON z.id_statusu = zs.id where z.id_uzytkownika = {$idUzytkownika}";
        return $this->db->pobierzWszystko($sql, $parmsEmpty);
    }

    public function pokazSzczegolyZamowienia($idUzytkownika, $idZamowienia)
    {
        $parmsEmpty = [];
        $sql = "SELECT id from zamowienia where md5(id) = '{$idZamowienia}' and id_uzytkownika = {$idUzytkownika}";
        //echo '<script>console.log('.$sql.')</script>';
		$wiersze = $this->db->pobierzWszystko($sql, $parmsEmpty);
        $numerZamówienia = $wiersze[0][0];
        $sql = "SELECT k.tytul as 'tytul', zs.cena as 'cena', zs.liczba_sztuk as 'liczba_sztuk' from ksiazki as k join zamowienia_szczegoly as zs on zs.id_ksiazki = k.id where zs.id_zamowienia = {$numerZamówienia}";
        return $this->db->pobierzWszystko($sql, $parmsEmpty);
    }

    
    public function pokazSzczegolyZamowieniaAdmin($idZamowienia)
    {
        $parmsEmpty = [];
        //$sql = "SELECT id from zamowienia where id = '{$idZamowienia}";
        //echo '<script>console.log('.$sql.')</script>';
		//$wiersze = $this->db->pobierzWszystko($sql, $parmsEmpty);
        //$numerZamówienia = $wiersze[0][0];
        $sql = "SELECT k.tytul as 'tytul', zs.cena as 'cena', zs.liczba_sztuk as 'liczba_sztuk' from ksiazki as k join zamowienia_szczegoly as zs on zs.id_ksiazki = k.id where zs.id_zamowienia = {$idZamowienia}";
        return $this->db->pobierzWszystko($sql, $parmsEmpty);
    }


    public function pobierzStatus($idZamowienia)
    {
        //var_dump($idZamowienia);
        $parmsEmpty = [];
        $sql = "SELECT s.id from zamowienia_statusy s join zamowienia as z on s.id=z.id_statusu where z.id = {$idZamowienia}";
        //var_dump($sql);
        //echo '<script>console.log('.$sql.')</script>';
        $wiersze = $this->db->pobierzWszystko($sql, $parmsEmpty);
        //var_dump($wiersze);
        $statusZamówienia = $wiersze[0][0];
        //$sql = "SELECT k.tytul as 'tytul', zs.cena as 'cena', zs.liczba_sztuk as 'liczba_sztuk' from ksiazki as k join zamowienia_szczegoly as zs on zs.id_ksiazki = k.id where zs.id_zamowienia = {$idZamowienia}";
        //var_dump($statusZamówienia);
        
        return $statusZamówienia;
    }

    public function edytuj($dane, $id)
	{
		$update = [
			'id_statusu' => $dane['id_status'],
		];
		
		return $this->db->aktualizuj('zamowienia', $update, $id);
	}

    /**
     * Dodaje szczegóły zamówienia.
     * 
     * @param int $idZamowienia
     * @param array $dane Książki do zamówienia
     */
    public function dodajSzczegoly($idZamowienia, $dane)
    {
        foreach ($dane as $ksiazka) {
            $this->db->dodaj('zamowienia_szczegoly', [
                'id_zamowienia' => $idZamowienia,
                'id_ksiazki' => $ksiazka['id'],
                'cena' => $ksiazka['cena'],
                'liczba_sztuk' => $ksiazka['liczba_sztuk']
            ]);
        }
    }

	/**
	 * Pobiera wszystkie zamówienia.
	 * 
	 * @return array
	 */
	public function pobierzWszystkie()
	{
		$sql = "
			SELECT z.*, u.login, s.nazwa AS status,
			ROUND(SUM(sz.cena*sz.liczba_sztuk), 2) AS suma,
			COUNT(sz.id) AS liczba_produktow,
			SUM(sz.liczba_sztuk) AS liczba_sztuk
			FROM zamowienia z JOIN uzytkownicy u ON z.id_uzytkownika = u.id
			JOIN zamowienia_statusy s ON z.id_statusu = s.id
			JOIN zamowienia_szczegoly sz ON z.id = sz.id_zamowienia
			GROUP BY z.id
	    ";

		return $this->db->pobierzWszystko($sql);
    }
}
