<?php
session_start();

// Inclusion du fichier config
require_once "assets/core/config.php";

$db = new PDO($dsn, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datetime = DateTime::createFromFormat('Y-m-d H:i', $_POST['date'] . ' ' . $_POST['heure']);

    if ($datetime === false) {
        die("Erreur : La date ou l'heure n'est pas au bon format.");
    }

    $datetime = $datetime->format('Y-m-d H:i:s');
    $description = $_POST['description'];
    $id_utilisateur = $_SESSION['id'];
    $id_serveur = $_SESSION['id_serveur'];

    try {
        $stmt = $db->prepare("INSERT INTO kralamoure (date, description, id_serveur) VALUES (?, ?, ?)");
        $stmt->execute([$datetime, $description, $id_serveur]);
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion dans la table kralamoure : " . $e->getMessage());
    }

    $id_kralamoure = $db->lastInsertId();

    try {
        $stmt = $db->prepare("INSERT INTO organisateur (id_utilisateur, id_kralamoure) VALUES (?, ?)");
        $stmt->execute([$id_utilisateur, $id_kralamoure]);
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion dans la table organisateur : " . $e->getMessage());
    }

    header("Location: kralamoure.php");
    exit;
}
?>
