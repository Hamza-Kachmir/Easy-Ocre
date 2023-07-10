<?php
$dsn = "mysql:host=localhost;port=3306;dbname=easy_ocre;charset=utf8";
$dbUser = trim("root");
$dbPassword = "";
$dbo = new PDO($dsn, $dbUser, $dbPassword);
try {
    $dbo = new PDO($dsn, $dbUser, $dbPassword);
    
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    // Gérer l'erreur de connexion à la base de données
}
?>