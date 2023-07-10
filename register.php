<?php
// Inclusion du header et du fichier config
require_once "assets/core/header.php";
require_once "assets/core/config.php";

// Connexion à la base de données
$connexion = new PDO($dsn, $dbUser, $dbPassword);

// Sélection des noms de serveurs de la base de données
$sql_serveur = "SELECT id, nom_serveur FROM serveur ORDER BY nom_serveur ASC";
$query_serveur = $connexion->prepare($sql_serveur);
$query_serveur->execute();

// Récupération des serveurs
$servers = $query_serveur->fetchAll(PDO::FETCH_ASSOC);

// Initialisation du tableau d'erreurs
$erreurs = [];

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim(htmlspecialchars($_POST["email"]));
    $pseudo = trim(htmlspecialchars($_POST["pseudo"]));
    $password = trim(htmlspecialchars($_POST["password"]));
    $confpassword = trim(htmlspecialchars($_POST["confpassword"]));
    $serveur = trim(htmlspecialchars($_POST["serveur"]));
    
    // Vérification de la présence et de la non-nullité des champs requis
    if (empty($email) || empty($pseudo) || empty($password) || empty($confpassword) || empty($serveur)) {
        $erreurs[] = "Veuillez compléter tous les champs obligatoires.";
    } else {
        // Vérification de la correspondance des mots de passe
        if ($password != $confpassword) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérification de la longueur minimale du mot de passe
        if (strlen($password) < 8) {
            $erreurs[] = "Le mot de passe saisi est trop court : 8 caractères minimum.";
        }

        // Vérification de la disponibilité de l'email
        $sql_email = "SELECT email FROM utilisateur WHERE email = :email";
        $query_email = $connexion->prepare($sql_email);
        $query_email->bindParam(":email", $email);
        $query_email->execute();
        if ($query_email->fetch()) {
            $erreurs[] = "Veuillez choisir un autre email, cet email est déjà utilisé.";
        }

        // Vérification de la disponibilité du pseudo
        $sql_pseudo = "SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo";
        $query_pseudo = $connexion->prepare($sql_pseudo);
        $query_pseudo->bindParam(":pseudo", $pseudo);
        $query_pseudo->execute();
        if ($query_pseudo->fetch()) {
            $erreurs[] = "Veuillez choisir un autre pseudo, ce pseudo est déjà utilisé.";
        }

        // Si aucune erreur n'est présente, procéder à l'inscription
        if (empty($erreurs)) {
            // Options de hachage
            $options = ["cost" => 12];
            $hash = password_hash($password, PASSWORD_DEFAULT, $options);

            // Préparation de la requête SQL
            $sql = "INSERT INTO utilisateur (email, pseudo, hash_pwd, id_serveur) VALUES (:email, :pseudo, :hash, :id_serveur);";
            $query = $connexion->prepare($sql);

            // Liaison des paramètres
            $query->bindParam(":email", $email);
            $query->bindParam(":pseudo", $pseudo);
            $query->bindParam(":hash", $hash);
            $query->bindParam(":id_serveur", $serveur);

            // Exécution de la requête
            $query->execute();
        }
    }
}
?>

<main>
    <form method="post" class="form form-inscription">
        <h4>Inscription</h4>
        
        <?php if (empty($erreurs) && $_SERVER["REQUEST_METHOD"] === "POST"): ?>
            <?php $compteCree = true; ?>
        <?php endif; ?>

        <?php if (!empty($erreurs)): ?>
            <p class="erreur-couleur">
                <?php foreach ($erreurs as $erreur): ?>
                    <?php echo $erreur; ?><br>
                <?php endforeach; ?>
            </p>
        <?php endif; ?>

        <?php if (isset($compteCree) && $compteCree): ?>
            <p class="compte-couleur">Votre compte a bien été créé !</p>
        <?php endif; ?>

        <input type="email" class="input" name="email" placeholder="Email *" required>
        <input type="text" class="input" name="pseudo" placeholder="Pseudo *" required>
        <input type="password" class="input" name="password" placeholder="Mot de passe *" required>
        <input type="password" class="input" name="confpassword" placeholder="Confirmation du mot de passe *" required>
        <select class="select-login-signin" name="serveur" required>
            <option value="">Serveur</option>
            <?php foreach($servers as $server): ?>
                <option value="<?php echo htmlspecialchars($server['id']); ?>"><?php echo htmlspecialchars($server['nom_serveur']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">S'inscrire</button>
    </form>
</main>


<?php
// Inclusion du footer
require_once "assets/core/footer.php";
?>
