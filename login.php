<?php
session_start();

// Inclusion du header et du fichier config
require_once "assets/core/header.php";
require_once "assets/core/config.php";

$erreur = ""; // Variable pour stocker le message d'erreur

if (!empty($_POST)) {
    // Vérification de la présence et de la non-vacuité des champs email et password
    if (isset($_POST["email"]) && $_POST["email"] !== "" &&
        isset($_POST["password"]) && $_POST["password"] !== "") {

        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        // Connexion à la base de données
        $connexion = new PDO($dsn, $dbUser, $dbPassword);
        $sql = "SELECT id, pseudo, hash_pwd, id_serveur FROM utilisateur WHERE email LIKE :email OR pseudo LIKE :email;";
        $query = $connexion->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);

        if ($query->execute()) {
            $result = $query->fetch();
            if ($result) {
                // Vérification du mot de passe avec password_verify
                if (password_verify($password, $result["hash_pwd"])) {
                    $succes = "Vous vous êtes identifié avec succès, vous êtes maintenant connecté !";
                    // Stockage des données dans la session
                    $_SESSION['email'] = $email;
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['pseudo'] = $result['pseudo'];
                    $_SESSION['id_serveur'] = $result['id_serveur'];

                    if (isset($_SESSION['redirect'])) {
                        // Redirection vers la page de redirection si elle est définie
                        header('Location: ../' . $_SESSION['redirect']);
                        unset($_SESSION['redirect']);
                    } else {
                        // Redirection vers la page d'accueil par défaut
                        header('Location: ../index.php');
                    }
                    exit;
                } else {
                    $erreur = "Vérifiez vos identifiants.";
                }
            } else {
                $erreur = "Vérifiez vos identifiants.";
            }
        }
    } else {
        $erreur = "Tous les champs obligatoires ne sont pas remplis.";
    }
}
?>

<main>
    <form method="post" class="form">
        <h3>Connexion</h3>
        <?php if (!empty($erreur)): ?>
            <p class="erreur-couleur"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <input name="email" type="text" class="input" placeholder="Pseudo ou Email *" required>
        <input type="password" name="password" class="input" placeholder="Mot de passe *" required>
        <button type="submit">Se connecter</button>
        <a href="">Mot de passe oublié ?</a>
    </form>
</main>

<?php
// Inclusion du footer
require_once "assets/core/footer.php";
?>
