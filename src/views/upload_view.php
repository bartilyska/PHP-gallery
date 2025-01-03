<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Galeria</title>
</head>
<body>
	<?php require_once 'menu_view.php' ?>
	<h2>Wstaw zdjęcie</h2>
	<form enctype="multipart/form-data" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
		<label for="zdjecie">Wybierz plik ze zdjęciem :</label>
		<input type="file" name="zdjecie" id="zdjecie">
		<br>
		<label for="znak">Wpisz znak wodny: </label>
		<input type="text" name="znak" id="znak" required>
		<br>
		<?php if(!empty($_SESSION['login'])): ?> <!--jezeli uzytkownik zalogowany pokaz dodatkowe opcje -->
		<?php $uchwyt=$_SESSION['login'];?>
		<label for="prywatne">Prywatne:</label>
		<input type="radio" id="prywatne" name="ustaw" required value="prywatne">
		<br>
		<label for="publiczne">Publiczne:</label>
		<input type="radio" id="publiczne" name="ustaw" required value="publiczne">
		<br>
		<?php else: ?>
		<?php $uchwyt="";?>
		<?php endif; ?>
		<label for="autor">Kto jest autorem? </label>
		<input type="text" name="autor" id="autor" required value="<?=$uchwyt?>"> <!--wyswietla login jako autora -->
		<br>
		<label for="tytul">Podaj tytul zdjecia: </label>
		<input type="text" name="tytul" id="tytul" required>
		<br>
		<input type="submit" value="Wyślij"/>
		<br>
	</form>
	<?php if (!empty($error)): ?>
        <p><?php echo ($error);  ?></p>
    <?php endif; ?>
	<?php dispatch($routing, '/gallery') ?> <!-- zeby galeria byla razem z uploadem -->
</body>
</html>
