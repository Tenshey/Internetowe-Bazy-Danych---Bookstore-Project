<?php

namespace Ibd;

class Stronicowanie
{
	/**
	 * Instancja klasy obsługującej połączenie do bazy.
	 *
	 * @var Db
	 */
	private $db;

	/**
	 * Liczba rekordów wyświetlanych na stronie.
	 * 
	 * @var int
	 */
	private $naStronie = 5;

	/**
	 * Aktualnie wybrana strona.
	 *
	 * @var int
	 */
	private $strona = 0;

	/**
	 * Dodatkowe parametry przekazywane w pasku adresu (metodą GET).
	 * 
	 * @var array
	 */
	private $parametryGet = [];

	/**
	 * Parametry przekazywane do zapytania SQL.
	 * 
	 * @var array
	 */
	private $parametryZapytania;
	
	public function __construct($parametryGet, $parametryZapytania)
	{
		$this->db = new Db();
		$this->parametryGet = $parametryGet;
		$this->parametryZapytania = $parametryZapytania;

		if (!empty($parametryGet['strona'])) {
			$this->strona = (int) $parametryGet['strona'];
		}
	}

	/**
	 * Dodaje do zapytania SELECT klauzulę LIMIT.
	 *
	 * @param string $select
	 * @return string
	 */
	public function dodajLimit(string $select): string
	{
		return sprintf('%s LIMIT %d, %d', $select, $this->strona * $this->naStronie, $this->naStronie);
	}

	/**
	 * Generuje linki do wszystkich podstron.
	 *
	 * @param string $select Zapytanie SELECT
	 * @param string $plik Nazwa pliku, do którego będą kierować linki
	 * @return string
	 */
	public function pobierzLinki(string $select, string $plik): string
	{
		$rekordow = $this->db->policzRekordy($select, $this->parametryZapytania);
		$liczbaStron = ceil($rekordow / $this->naStronie);
		$parametry = $this->_przetworzParametry();
		$lastPage = $liczbaStron-1;

		$linki = "<nav><ul class='pagination'> ";
		if($liczbaStron>1)
		{
			$firstPage = 0;
			
			$linki .= "<li class='page-item'><a href='$plik?$parametry&strona=$firstPage' class='page-link'><<</a></li>";
			if($this->strona>0)
			{
				$previousPage = $this->strona-1;
				$linki .= "<li class='page-item'><a href='$plik?$parametry&strona=$previousPage' class='page-link'><</a></li>";

			}
			else
			{
				$linki .= "<li class='page-item disabled'><a class='page-link'><</a></li>";
			}
		}
		for($i = 0; $i < $liczbaStron; $i++) {
			if($i == $this->strona){
				$linki .= "<li class='page-item active'><a class='page-link'>" . ($i + 1) . "</a></li>";
			} else {
				$linki .= "<li class='page-item'><a href='$plik?$parametry&strona=$i' class='page-link'>" . ($i + 1) . "</a></li>";
			}
		}
		if($liczbaStron>1)
		{
			if($this->strona<$liczbaStron-1)
			{
				$nextPage = $this->strona+1;
				$linki .= "<li class='page-item'><a href='$plik?$parametry&strona=$nextPage' class='page-link'>></a></li>";

			}
			else
			{
				$linki .= "<li class='page-item disabled'><a class='page-link'>></a></li>";
			}
			$lastPage = $liczbaStron-1;
			$linki .= "<li class='page-item'><a href='$plik?$parametry&strona=$lastPage' class='page-link'>>></a></li>";

		}
		$linki .= "</ul>";
		if($this->strona==0)
		{
			$start = 1;
		}
		else
		{
			$start = $this->strona*$this->naStronie+1;
		}
		if($this->strona==$lastPage)
		{
			$end = $rekordow;
		}
		else
		{
			$end = $start+$this->naStronie-1;
		}
		
		$linki .= "$start - $end z $rekordow rekordów.";
		$linki .= "</nav>";

		return $linki;
	}

	/**
	 * Przetwarza parametry wyszukiwania.
	 * Wyrzuca zbędne elementy i tworzy gotowy do wstawienia w linku zestaw parametrów.
	 *
	 * @return string
	 */
	private function _przetworzParametry(): string
	{
		$temp = array();
		$usun = array('szukaj', 'strona');
		foreach($this->parametryGet as $kl => $wart) {
			if(!in_array($kl, $usun))
				$temp[] = "$kl=$wart";
		}

		return implode('&', $temp);
	}
}
