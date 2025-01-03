<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Logowanie</title>
</head>
<body>
	<h2>Zaloguj się</h2>
	<form method="POST">
		<label for="login">Podaj login: </label>
		<input type="text" name="login" id="login" required>
		<br>
		<label for="haslo">Podaj hasło: </label>
		<input type="password" name="haslo" id="haslo" required>
		<br>
		<input type="submit" value="Wyślij"/>
		<br>
	</form>
	<?php if (!empty($error)): ?>
        <p><?php echo ($error);  ?></p>
    <?php endif; ?>
</body>
</html>