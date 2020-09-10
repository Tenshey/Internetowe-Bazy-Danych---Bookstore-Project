<?php

namespace Ibd;

class Uzytkownicy
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
        'imie' => 0,
        'nazwisko' => 0,
        'adres' => 0,
        'email' => 0,
        'login' => 0,
        'haslo' => 0
    ];

    /**
     * Przechowuje dane z formularza dodawania.
     * 
     * @var array
     */
    private $dane = [];

    public function __construct()
    {
        $this->db = new Db();
    }

    public function setDane($dane)
    {
        $this->dane = $dane;
    }
    
    /**
     * Waliduje dane użytkownika; zwraca tablicę z błędami (kluczami są nazwy pól)
     * wartości błędów:
     * 0 - brak błędu
     * 1 - wymagane puste pole
     * 2 - zły format pola
     * 3 - login zajęty
     * 4 - e-mail zajęty
     *
     * @param array $dane
     * @return array
     */
    public function waliduj($dane)
    {
        // usuwanie spacji z początku i końca
        foreach ($dane as $kl => $wart)
            $dane[$kl] = trim($wart);

        
        $parmsEmpty = [];

        $this->dane = $dane;
        if (empty($dane['imie']))
            $this->bledy['imie'] = 1;
        if (empty($dane['nazwisko']))
            $this->bledy['nazwisko'] = 1;
        if (empty($dane['adres']))
            $this->bledy['adres'] = 1;
        $emailAddress = $dane['email'];
        //var_dump($emailAddress);
        
        $sqlEmail = "SELECT email from uzytkownicy where email = '{$emailAddress}'";

        //var_dump($sqlEmail);
        
        $countEmail = $this->db->policzRekordy($sqlEmail,$parmsEmpty);
        //ar_dump($countEmail);
        if (empty($dane['email']) || !filter_var($dane['email'], FILTER_VALIDATE_EMAIL))
        $this->bledy['email'] = 1;
        if($this->bledy['email']==0)
        {
            if ($countEmail>0)
            {
                $this->bledy['email'] = 4;
            }
        }
        $login= $dane['login'];
        $sqlLogin = "SELECT login from uzytkownicy where login = '{$login}'";
        //var_dump($sqlLogin);
        $countLogin = $this->db->policzRekordy($sqlLogin,$parmsEmpty);
        //var_dump($countLogin);
        if (empty($dane['login']))
            $this->bledy['login'] = 1;
        if($this->bledy['login']==0)
            {
                if ($countLogin>0)
                {
                    $this->bledy['login'] = 3;
                }
        }
        if (empty($dane['haslo']))
            $this->bledy['haslo'] = 1;

        return $this->bledy;
    }

    /**
     * Zwraca wartość błędu dla określonego pola.
     *
     * @param string $pole
     * @return string|bool
     */
    public function blad($pole)
    {
        if (isset($this->bledy[$pole]))
            return $this->bledy[$pole];
        else
            return false;
    }

    /**
     * Zwraca wpisaną wartość dla określonego pola (lub wszystkie)
     *
     * @param string $pole
     * @return string
     */
    public function dane($pole = null)
    {
        if (is_null($pole))
            return $this->dane;

        if (isset($this->dane[$pole]))
            return $this->dane[$pole];
        else
            return '';
    }

    /**
     * Sprawdza czy formularz zawiera błędy.
     * 
     * @return bool
     */
    public function czySaBledy()
    {
        return array_sum($this->bledy) > 0;
    }

    public function loginZajety()
    {
        if($this->bledy['login']==3)
        {
            return true;
        }
    }
    public function emailZajety()
    {
        if($this->bledy['email']==4)
        {
            return true;
        }
    }

    /**
     * Dodaje użytkownika do bazy.
     * 
     * @param array $dane
     * @param string $grupa
     * @return int
     */
    public function dodaj($dane, $grupa = 'użytkownik')
    {
        return $this->db->dodaj('uzytkownicy', [
            'imie' => $dane['imie'],
            'nazwisko' => $dane['nazwisko'],
            'adres' => $dane['adres'],
            'telefon' => $dane['telefon'],
            'email' => $dane['email'],
            'login' => $dane['login'],
            'haslo' => md5($dane['haslo']),
            'grupa' => $grupa
        ]);
    }

    /**
     * Loguje użytkownika do systemu. Zapisuje dane o autoryzacji do sesji.
     *
     * @param string $login
     * @param string $haslo
     * @param string $grupa
     * @return bool
     */
    public function zaloguj($login, $haslo, $grupa)
    {
        $haslo = md5($haslo);
        $dane = $this->db->pobierzWszystko(
            "SELECT * FROM uzytkownicy WHERE login = :login AND haslo = '$haslo' AND grupa = '$grupa'", ['login' => $login]
        );

        if ($dane) {
            $_SESSION['id_uzytkownika'] = $dane[0]['id'];
            $_SESSION['grupa'] = $dane[0]['grupa'];
            $_SESSION['login'] = $dane[0]['login'];

            return true;
        }

        return false;
    }

    /**
     * Sprawdza, czy jest zalogowany użytkownik.
     *
     * @param string $grupa
     * @return bool
     */
    public function sprawdzLogowanie($grupa = 'administrator')
    {
        if (!empty($_SESSION['id_uzytkownika']) && !empty($_SESSION['grupa']) && $_SESSION['grupa'] == $grupa)
            return true;

        return false;
    }

    /**
     * Pobiera zapytanie SELECT z użytkownikami.
     *
     * @return array
     */
    public function pobierzSelect($params = array())
    {
        $sql = "SELECT * FROM uzytkownicy WHERE 1=1 ";

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
     * Usuwa użytkownika.
     *
     * @param int $id
     * @return bool
     */
    public function usun($id)
    {
        return $this->db->usun('uzytkownicy', $id);
    }

    /**
     * Pobiera dane użytkownika o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz($id)
    {
        return $this->db->pobierz('uzytkownicy', $id);
    }

    /**
     * Zmienia dane użytkownika.
     *
     * @param array $dane
     * @param int $id
     * @return bool
     */
    public function edytuj($dane, $id)
    {
        $update = [
            'imie' => $dane['imie'],
            'nazwisko' => $dane['nazwisko'],
            'adres' => $dane['adres'],
            'telefon' => $dane['telefon'],
            'email' => $dane['email'],
            'grupa' => $dane['grupa']
        ];

        if (!empty($dane['haslo']))
            $update['haslo'] = md5($dane['haslo']);

        return $this->db->aktualizuj('uzytkownicy', $update, $id);
    }
}
