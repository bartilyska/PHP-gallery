<?php
//funkcje php
require_once 'business.php';
function upload(&$model) //przekazywany wskaznik by edytowac tablice
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["zdjecie"]) )
	{
		$wyslane_zdjecie = $_FILES["zdjecie"]["name"];
		$rozszerzenie = strtolower(pathinfo($wyslane_zdjecie, PATHINFO_EXTENSION));
		$flag=1;
		$error="";
		if($rozszerzenie!=="jpg" && $rozszerzenie!=="png" && $rozszerzenie!=="jpeg") //spr. format
		{
			$error=$error."Złe rozszerzenie pliku.";
			$flag=0;
		}
		if($_FILES["zdjecie"]["error"]==UPLOAD_ERR_FORM_SIZE) //1MB
		{
			$error=$error."Za duzy rozmiar.";
			$flag=0;
		}
		if($_FILES["zdjecie"]["error"]==UPLOAD_ERR_OK && $flag===1) //przeszlo oba testy i jest git
		{
				$sciezka=$_SERVER['DOCUMENT_ROOT'].'/images/normal/'. basename($_FILES['zdjecie']['name']); //web/images/xxx.jpg
				if(move_uploaded_file($_FILES['zdjecie']['tmp_name'],$sciezka)) //jezeli przeszlo
				{
					if($rozszerzenie=="png")
						$focia=imagecreatefrompng($sciezka);
					else
						$focia=imagecreatefromjpeg($sciezka); 
					
					$wysokosc=imagesy($focia);$szerokosc=imagesx($focia);
					$mini_wys=125;$mini_szer=200;
					$napis=$_POST['znak'];//znak wodny
					
					$miniatura=imagecreatetruecolor($mini_szer,$mini_wys); //miniaturka o danych rozmiarach
					imagecopyresampled($miniatura,$focia,0,0,0,0,$mini_szer,$mini_wys,$szerokosc,$wysokosc);
					imagejpeg($miniatura, $_SERVER['DOCUMENT_ROOT'].'/images/mini/'.$napis.basename($_FILES['zdjecie']['name'])); //nazwa
					
					$kolor = imagecolorallocate($focia, 236,10,10); //kolor napisu
					imagestring($focia, 5, 40, 40, $napis,$kolor); //wstawianie znaku
					imagestring($focia, 5, 40, 80, $napis,$kolor);
					imagejpeg($focia, $_SERVER['DOCUMENT_ROOT'].'/images/znak/'.$napis.basename($_FILES['zdjecie']['name'])); 
					imagedestroy($focia);
					imagedestroy($miniatura);
					
					$prywatne=0;
					if(isset($_POST['ustaw']))
					{
						$opcja=$_POST['ustaw'];
						if($opcja==='prywatne')
							$prywatne=1; //tylko wtedy jest prywatne w przeciwnym razie public
					}
					$dane = [
						'autor' => $_POST['autor'],
						'tytul' => $_POST['tytul'],
						'prywatne' => $prywatne,
						'nazwa' => $napis.basename($_FILES['zdjecie']['name'])
					];
					zapisz_dane($dane);
				}
				else
				{
					$error=$error."Nie trafil na serwer.";
				}
		}
		else
		{
			$error=$error." Nie przesłano.";
		}
		$model['error']=$error;
	}
    return 'upload_view';
}

function gallery(&$model)
{
	if (($_SERVER['REQUEST_METHOD'] === 'POST') && !(isset($_FILES["zdjecie"]))) //jak bedzie pusty POST to tablica po prostu bedzie pusta
	{
		$tablica=[];
		if(isset($_SESSION['tablica']))
		$tablica=$_SESSION['tablica'];
			foreach($_POST as $klucz => $wartosc)
			{	
				array_push($tablica,$klucz); //wrzuca do tablicy odpowiednie id
			}
		$_SESSION['tablica']=[];
		$_SESSION['tablica']=$tablica; //do sesji trafiaja zaznaczone id
		$model['ile']=szukaj_danych_publicznych();
		$model['publiczne']=wyswietl_dane_publicznych();
		$model['prywatne']=szukaj_danych_prywatnych();
		$model['tablica']=$_SESSION['tablica']; //sesja do modelu zeby moc wyswietlic
		return 'gallery_view';
	}
	else
	{
		$model['ile']=szukaj_danych_publicznych();
		$model['publiczne']=wyswietl_dane_publicznych();
		$model['prywatne']=szukaj_danych_prywatnych();
		if(isset($_SESSION['tablica']))
		{
			$model['tablica']=$_SESSION['tablica'];
		}
		else
		{
			$model['tablica']=[];
		}
		return 'gallery_view';
	}
}

function zaznaczone(&$model)
{
	if (($_SERVER['REQUEST_METHOD'] === 'POST')) 
	{
		$tablica=[];
			foreach($_POST as $klucz => $wartosc)
			{	
				array_push($tablica,$klucz); //w kluczu jest id 
			}
		$pozostawione=array_diff($_SESSION['tablica'],$tablica); //usunie te wybrane i zostana w sesji odpowiednie
		$_SESSION['tablica']=[];
		$_SESSION['tablica']=$pozostawione;
		$model['data']=szukaj_danych();
		$model['tablica']=$_SESSION['tablica'];
		return 'zaznaczone_view';
	}
	else
	{
		$model['data']=szukaj_danych();
		if(isset($_SESSION['tablica']))
		{
			$model['tablica']=$_SESSION['tablica'];
		}
		else
		{
			$model['tablica']=[];
		}
		return 'zaznaczone_view';
	}
}
		

function register(&$model)
{
	if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['login'])  &&  isset($_POST['haslo1']) && isset($_POST['haslo2']) && 
		isset($_POST['mail']))
		{
			$flag=1;
			$error="";
			$login=$_POST['login'];
			$haslo1=$_POST['haslo1'];
			$haslo2=$_POST['haslo2'];
			$mail=$_POST['mail'];
			if($haslo1!==$haslo2)
			{
				$error=$error."Hasła się różnią. ";
				$flag=0;
			}
			if(!(filter_var($mail, FILTER_VALIDATE_EMAIL))) 
			{
				$error=$error."Błędny email. ";
				$flag=0;
			}
			if(login_w_bazie($login))
			{
				$error=$error."Login zajęty. ";
				$flag=0;
			}
			if($flag===1)
			{
				$hash=password_hash($haslo1, PASSWORD_DEFAULT);
				if(dodaj_uzytkownika($login,$mail,$hash))
				{
					return 'redirect:login'; 
				}
				else
				{
					$error=$error."Problem z bazą. ";
					$model['error']=$error; 
					return 'register_view';
				}
			}
			else //nie powiodla sie rejestracja
			{
				$model['error']=$error; 
				return 'register_view';
			}
		}
	return 'register_view'; 
}

function login(&$model)
{	
	if(!(isset($_SESSION['uzytkownik_id'])))
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['login']) && isset($_POST['haslo']) )
		{
			$login = $_POST['login'];
			$haslo = $_POST['haslo'];
			$sprawdz=szukaj_uzytkownika($login,$haslo);
			if($sprawdz===true)
			{
				return 'redirect:poprawne'; //przekierowanie jezeli sie uda 
			}
			else
			{
				$model['error']="Błędne logowanie";
				return 'login_view'; //powtorka logowania jak sie nie uda
			}
		}
		return 'login_view';
	}
	return 'redirect:upload'; //jak zalogowany to niech sie nie loguje
}
function poprawne(&$model)
{
	if(isset($_SESSION['uzytkownik_id']))
	{
		return 'poprawne_log';
	}
	return 'redirect:upload';
}
function logout(&$model)
{
	session_destroy();
	setcookie(session_name(),'', time() - 42000);
	session_unset();
	return 'redirect:upload';
}
