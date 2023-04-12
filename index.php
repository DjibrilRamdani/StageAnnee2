<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['name'])) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion avec Google</title>
</head>
<body>
<h1>Se connecter avec Google</h1>
<a href="login.php">Connexion avec Google test</a>
</body>
</html>
