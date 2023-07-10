<?php
session_start();

// Inclusion du header et du fichier config
require_once "assets/core/header.php";
require_once "assets/core/config.php";
?>

<main>
    <div class="texte-acceuil">
        <section class="texte-avantage">
            <div class="texte-un">
                <h1>Easy Ocre</h1>
                <div class="element-avantage">
                    <?php if (isset($_SESSION['id'])): ?>
                        <h5 class="bvn-pseudo">Bienvenue <?php echo $_SESSION['pseudo']; ?> !</h5>
                        <p>Vous pouvez maintenant profiter des avantages du site :</p>
                    <?php else: ?>
                        <p><a href="/login.php">Connectez-vous</a> ou <a href="/register.php">inscrivez-vous</a> pour profiter des avantages du site :</p>
                    <?php endif; ?>
                </div>
                <ul>
                    <li>Découvrez les monstres à capturer</li>
                    <li>Suivez votre avancement dans la quête</li>
                    <li>Gérez finement vos monstres</li>
                    <li>Echangez avec d'autres joueurs</li>
                </ul>
            </div>
            <img class="otomai" src="/assets/img/otomail.png" alt="">
        </section>

        <section class="texte-a-propos">
            <div class="texte-un">
                <h3>Easy Ocre, c'est quoi ?</h3>
                <p>Easy Ocre vous permet de gérer vos monstres pour la quête du Dofus Ocre :</p>
                <h5>L'éternelle moisson.</h5>
                <p>Cette quête consiste à capturer plus de 600 monstres.</p>
                <p>Avec Easy Ocre vous pouvez : notez, échangez et indiquez aux autres utilisateurs les monstres que vous proposez ou recherchez.</p>
            </div>
            <img class="logo-a-propos" src="/assets/img/logo.png" alt="">
        </section>
    </div>
</main>

<?php
// Inclusion du footer
require_once "assets/core/footer.php";
?>
