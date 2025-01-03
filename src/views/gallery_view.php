<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Galeria</title>
	<link rel="stylesheet" href="static/style.css"/>
</head>
<body>
	<h2>Galeria zdjęć</h2>
	<form method="POST">
	<?php if (count($publiczne,COUNT_RECURSIVE)>0): ?>
		<div class="tablica">
        <?php foreach ($publiczne as $zdjecie): ?>      <!--wyswietlanie zdjec publicznych + checkboxy do sesji -->
				<a href="./images/znak/<?=$zdjecie['nazwa'] ?>">
				<img src="./images/mini/<?=$zdjecie['nazwa'] ?>" alt="zdjecie" class="foto">
				<figcaption>
					Autor: <?=$zdjecie['autor'] ?>
					<br>
					Tytuł: <?=$zdjecie['tytul'] ?>
					<?php if(in_array($zdjecie['_id'],$tablica)): ?> <!-- TABLICA TO MECHANIZM SESJI ZAWIERA TE ZAZNACZONE -->
						<input type="checkbox" id="<?=$zdjecie['_id'] ?>" name="<?=$zdjecie['_id'] ?>" value="guzik" checked>
					<?php else:?>
						<input type="checkbox" id="<?=$zdjecie['_id'] ?>" name="<?=$zdjecie['_id'] ?>" value="guzik" >
					<?php endif ?>
				</figcaption>
				</a>
		<?php endforeach ?>
	<?php else: ?>
		<h3>Brak zdjęć publicznych.</h3>
    <?php endif ?>
	</div>
	<br>
	<?php if (count($prywatne,COUNT_RECURSIVE)>0): ?>
		<div class="tablo">
        <?php foreach ($prywatne as $zdjecie): ?> <!--rozroznianie uzytkownikow -->
				<?php if( !empty($_SESSION['login']) && ($_SESSION['login']===$zdjecie['autor'])): ?>
					<a href="./images/znak/<?=$zdjecie['nazwa'] ?>">
					<img src="./images/mini/<?=$zdjecie['nazwa'] ?>" alt="zdjecie" class="foto">
					<figcaption>
					Autor: <?=$zdjecie['autor'] ?>
					<br>
					Tytuł: <?=$zdjecie['tytul'] ?>
					<?php if(in_array($zdjecie['_id'],$tablica)): ?> <!--Prywatne zdjecia tez mozna zaznaczac -->
						<input type="checkbox" id="<?=$zdjecie['_id'] ?>" name="<?=$zdjecie['_id'] ?>" value="guzik" checked>
					<?php else:?>
						<input type="checkbox" id="<?=$zdjecie['_id'] ?>" name="<?=$zdjecie['_id'] ?>" value="guzik" >
					<?php endif ?>
					<br>
						PRYWATNE!!!!
					</figcaption>
					</a>
				<?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
	</div>
	<br>
		<input type="submit" value="Zapamiętaj wybrane">
	</form>
	<form method="GET">
	Strona: <br>
	<?php for($i=1;5*($i-1)<count($ile,COUNT_RECURSIVE);$i++): ?>
			<?=$i?> <input type="radio" id="<?=$i?>" name="numer" value="<?=$i?>">
	<?php endfor ?>
	<br>
	<input type="submit" value="Wybierz stronę">
	</form>
	<br>
	<a href="zaznaczone">Obejrz zaznaczone zdjecia</a>
</html>
