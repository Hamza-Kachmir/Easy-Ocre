<?php
session_start();

// Inclusion du fichier config
require_once "assets/core/config.php";
$db = new PDO($dsn, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_event = $_POST['event_id'];

    try {
        // Supprimer d'abord l'entrée correspondante dans la table organisateur
        $stmt = $db->prepare("DELETE FROM organisateur WHERE id_kralamoure = ?");
        $stmt->execute([$id_event]);

        // Ensuite, supprimer l'événement de la table kralamoure
        $stmt = $db->prepare("DELETE FROM kralamoure WHERE id = ?");
        $stmt->execute([$id_event]);
    } catch (PDOException $e) {
        die("Erreur lors de la suppression de l'événement : " . $e->getMessage());
    }

    header("Location: kralamoure.php");
    exit;
}
?>
