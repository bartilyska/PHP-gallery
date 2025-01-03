<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Rejestracja</title>
</head>
<body>
	<h2>Zarejestruj się</h2>
	<form method="POST">
		<label for="login">Podaj login: </label>
		<input type="text" name="login" id="login" required>
		<br>
		<label for="mail">Podaj maila: </label>
		<input type="text" name="mail" id="mail" required>
		<br>
		<label for="haslo1">Podaj hasło: </label>
		<input type="password" name="haslo1" id="haslo1" required>
		<br>
		<label for="haslo2">Powtórz hasło: </label>
		<input type="password" name="haslo2" id="haslo2" required>
		<br>
		<input type="submit" value="Wyślij"/>
		<br>
	</form>
	<?php if (!empty($error)): ?>
        <p><?php echo ($error);  ?></p>
    <?php endif; ?>
</body>
</html>