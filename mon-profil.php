<?php
session_start();

// Inclusion du header et du fichier config
require_once "assets/core/header.php";
require_once "assets/core/config.php";

?>

<main>

<section class="contenair-profil">
    <h3><?php echo $_SESSION['pseudo']; ?></h3>

    <div class="contenu-top">
        <div class="contenu-photo-profil">
            <label for="photo-profil">Photo de profil</label>
            <img class="photo-profil" id="photo-profil" src="assets/img/pp-otomai.png" alt="Photo de profil">
        </div>

        <div class="description-joueur">
            <label for="area-profil">Description</label>
            <textarea class="area-profil" name="description" id="area-profil" cols="30" rows="10" placeholder="Vous pouvez entrer une description. Vous pouvez indiquez votre pseudo en jeu ou vos objectifs sur Easy Ocre."></textarea>
        </div>
    </div>

    <div class="contenu-profil">
        <form action="" method="post" class="form-profil">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="">

            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo" value="<?php echo $_SESSION['pseudo']; ?>" disabled>

            <label for="password">Confirmez votre mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="*********" required>

            <label for="new-password">Nouveau mot de passe :</label>
            <input type="password" name="new-password" id="new-password" placeholder="*********" required>

            <label for="serveur">Serveur</label>
            <select class="select-login-signin" name="serveur" id="serveur" required>
                <option value="Draconiros">Draconiros</option>
                <option value="Hell Mina">Hell Mina</option>
                <option value="Imagiro">Imagiro</option>
                <option value="Ombre">Ombre</option>
                <option value="Orukam">Orukam</option>
                <option value="Tal Kasha">Tal Kasha</option>
                <option value="Tylezia">Tylezia</option>
            </select>
            <button class="button-profil" name="submit">Enregistrer</button>
        </form>
    </div>
</section>
</main>
<?php
// Inclusion du footer
require_once "assets/core/footer.php";
?>
