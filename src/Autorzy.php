<?php

namespace Ibd;

class Autorzy
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
	 * Pobiera zapytanie SELECT z autorami.
	 *
	 * @return array
	 */
	public function pobierzSelect($params = [])
	{
		$sql = "SELECT * FROM autorzy WHERE 1=1 ";

		return $sql;
	}

	/**
	 * Wykonuje podane w parametrze zapytanie SELECT.
	 * 
	 * @param string $select
	 * @return array
	 */
	public function pobierzWszystko($select)
	{
		return $this->db->pobierzWszystko($select);
	}

	/**
	 * Pobiera dane autora o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function pobierz($id)
	{
		return $this->db->pobierz('autorzy', $id);
	}

	/**
	 * Dodaje autora.
	 *
	 * @param array $dane
	 * @return int
	 */
	public function dodaj($dane)
	{
		return $this->db->dodaj('autorzy', [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		]);
	}

	/**
	 * Usuwa autora.
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function usun($id)
	{
		return $this->db->usun('autorzy', $id);
	}

	/**
	 * Zmienia dane autora.
	 * 
	 * @param array $dane
	 * @param int $id
	 * @return bool
	 */
	public function edytuj($dane, $id)
	{
		$update = [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		];
		
		return $this->db->aktualizuj('autorzy', $update, $id);
	}

	public function pobierzZapytanie($params)
	{
		$parametry = [];
		$sql = "SELECT a.id, a.imie, a.nazwisko, COUNT(k.id) as liczba from autorzy a left join ksiazki k on k.id_autora = a.id WHERE 1=1 GROUP BY a.id";

		// dodawanie warunków do zapytanie
		if (!empty($params['fraza'])) {
		$sql = "SELECT a.id, a.imie, a.nazwisko, COUNT(k.id) as liczba from autorzy a left join ksiazki k on k.id_autora = a.id WHERE 1=1";
			$sql .= " AND (a.imie LIKE :fraza OR a.nazwisko like :fraza) GROUP BY a.id";
			$parametry['fraza'] = "%$params[fraza]%";
		}
		
		// dodawanie sortowania
		if (!empty($params['sortowanie'])) {
			$kolumny = ['a.imie', 'a.nazwisko'];
			$kierunki = ['ASC', 'DESC'];
			list($kolumna, $kierunek) = explode(' ', $params['sortowanie']);
			
			if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
				$sql .= " ORDER BY " . $params['sortowanie'];
			}
		}

		//var_dump($sql);
		return ['sql' => $sql, 'parametry' => $parametry];
	}
	public function pobierzStrone($select, $params)
	{
		return $this->db->pobierzWszystko($select, $params);
	}
}
