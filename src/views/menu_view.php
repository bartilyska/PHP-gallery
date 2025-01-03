<?php if (empty($_SESSION['uzytkownik_id'])): ?>
<a href="register">Rejestracja</a> |
<a href="login">Logowanie</a>
<?php else: ?>
<a href="register">Rejestracja</a> |
<a href="logout">Wylogowanie</a>
<?php endif ?>