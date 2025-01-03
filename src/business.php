<?php
//operacje na bazie
use MongoDB\BSON\ObjectID;
function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}
function zapisz_dane($dane)
{
	 $db = get_db();
	 $db->baza->insertOne($dane);
}
function szukaj_danych()
{
	$db = get_db();
	$data = $db->baza->find()->toArray();
	return $data;
}
function szukaj_danych_publicznych()
{
	$db = get_db();
	$ile = $db->baza->find(['prywatne' =>0])->toArray();
	return $ile;
}
function wyswietl_dane_publicznych()
{
	$db = get_db();
		$numer=1;
	if(isset($_GET['numer']))
		$numer=$_GET['numer'];
	$strona =$numer;
	$stronaRozmiar = 5;
	$opts = [
		'skip' => ($strona - 1) * $stronaRozmiar,
		'limit' => $stronaRozmiar
	];
	$publiczne = $db->baza->find(['prywatne' =>0],$opts)->toArray();
	return $publiczne;
}
function szukaj_danych_prywatnych()
{
	$db = get_db();
	$prywatne = $db->baza->find(['prywatne' =>1])->toArray();
	return $prywatne;
}
function login_w_bazie($login)
{
	$db = get_db();
	$uzytkownik = $db->uzytkownicy->findOne(['login'=>$login]);
	if ($uzytkownik) {return true; }
	 else { return false;}
}
function dodaj_uzytkownika($login,$mail,$hash)
{
	$db=get_db();
	$osoba =$db->uzytkownicy->insertOne([ 'login' => $login, 'haslo' => $hash, 'mail'=>$mail ]);
	return $osoba;
}
function wypisz()
{
	$db = get_db();
	$users = $db->uzytkownicy->find()->toArray();
	return $users;
}
function szukaj_uzytkownika($login,$haslo)
{
	$db = get_db();
	$uzytkownik = $db->uzytkownicy->findOne(['login' => $login]);
	if($uzytkownik !== null && password_verify($haslo,$uzytkownik['haslo']))
	{
		session_regenerate_id();
		$_SESSION['uzytkownik_id'] = $uzytkownik['_id'];
		$_SESSION['login']=$uzytkownik['login'];
		return true;
	}
	else
	{
		return false;
	}
}
