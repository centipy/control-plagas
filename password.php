<?php
// temp_hash.php
$password_to_hash = '12345'; // O la contraseña que quieras
$hashed_password = password_hash($password_to_hash, PASSWORD_BCRYPT);
echo "Contraseña original: " . $password_to_hash . "<br>";
echo "Hash generado: " . $hashed_password . "<br>";
?>