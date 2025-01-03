<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Zaznaczone zdjęcia</title>
	<link rel="stylesheet" href="static/style.css"/>
</head>
<body>
	<h2>Zaznaczone zdjęcia</h2>
	<form method="POST">
	<?php if (!(empty($tablica))): ?>
		<div class="tablica">
        <?php foreach ($data as $zdjecie): ?>
				<?php if(in_array($zdjecie['_id'],$tablica)): ?> 
					<a href="./images/znak/<?=$zdjecie['nazwa'] ?>">
					<img src="./images/mini/<?=$zdjecie['nazwa'] ?>" alt="zdjecie" class="foto">
					<figcaption>
						Autor: <?=$zdjecie['autor'] ?>
						<br>
						Tytuł: <?=$zdjecie['tytul'] ?>
						<input type="checkbox" id="<?=$zdjecie['_id'] ?>" name="<?=$zdjecie['_id'] ?>" value="guzik">
					</figcaption>
					</a>
				<?php endif ?>
        <?php endforeach ?>
		</div>
		<br>
		<input type="submit" value="Usuń wybrane">
    <?php else: ?>
		<h3>Brak zdjęć</h3>
    <?php endif ?>
	<br><br>
		
	</form>
	<br>
	<a href="upload">Wróć do galerii</a>
</body>
</html>