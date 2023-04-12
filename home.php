<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
</head>
<body>
<h1>Bienvenue, <?php echo $_SESSION['name']; ?>!</h1>
<p>Email: <?php echo $_SESSION['email']; ?></p>
<p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
