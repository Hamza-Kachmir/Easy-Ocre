<?php
session_start();

// Inclusion du fichier config
require_once "assets/core/config.php";
$db = new PDO($dsn, $dbUser, $dbPassword);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_event = $_POST['event_id'];
    $description = $_POST['description'];

    // Validation des données
    if (!empty($description)) {
        // Préparer une instruction de mise à jour
        $stmt = $db->prepare("UPDATE kralamoure SET description = :description WHERE id = :id");

        // Lier les valeurs et exécuter l'instruction
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":id", $id_event);
        $stmt->execute();

        // Rediriger vers la page du calendrier
        header("Location: kralamoure.php");
        exit;
    } else {
        $erreur = "Veuillez saisir une description valide.";
    }
}
?>

<!-- Affichage de l'erreur si elle existe -->
<?php if (isset($erreur)) : ?>
    <p><?php echo htmlspecialchars($erreur); ?></p>
<?php endif; ?>
