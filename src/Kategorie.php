<?php

namespace Ibd;

class Kategorie
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
	 * Pobiera wszystkie kategorie.
	 *
	 * @return array
	 */
	public function pobierzWszystkie(): array
	{
		$sql = "SELECT * FROM kategorie";

		return $this->db->pobierzWszystko($sql);
	}

	public function pobierz($id)
	{
		return $this->db->pobierz('kategorie', $id);
	}


	public function pobierzSelect($params = [])
	{
		$sql = "SELECT * FROM kategorie WHERE 1=1 ";

		return $sql;
	}

	public function usun($id)
	{
		return $this->db->usun('kategorie', $id);
	}

	public function edytuj($dane, $id)
	{
		$update = [
			'nazwa' => $dane['nazwa'],
		];
		
		return $this->db->aktualizuj('kategorie', $update, $id);
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

	public function dodaj($dane)
	{
		return $this->db->dodaj('kategorie', [
			'nazwa' => $dane['nazwa']
		]);
	}

}
