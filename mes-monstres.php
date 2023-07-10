<?php
session_start();

// Inclusion du header et du fichier config
require_once "assets/core/header.php";
require_once "assets/core/config.php";
?>

<main>
    <div class="contenair-monstres">
        <section class="mes-monstres">
            <h3>Filtrer mes monstres</h3>
            <form class="form-monstres" action="">
                <div class="filtre-monstres">
                    <div class="form-groupe">
                        <h4>Monstre</h4>
                        <input type="text" class="input-monstres" placeholder="Nom du monstre">
                    </div>
                    <div class="form-groupe">
                        <h4>Etape</h4>
                        <select class="select-monstre" name="" id="">
                            <option value="">Toutes les étapes</option>
                            <option value="">Etape 1</option>
                            <option value="">Etape 2</option>
                        </select>
                    </div>
                    <div class="form-groupe">
                        <h4>Type de monstre</h4>
                        <select class="select-monstre" name="" id="">
                            <option value="">Tous les monstres</option>
                            <option value="">Monstre ordinaire</option>
                            <option value="">Boss de donjon</option>
                            <option value="">Archimonstre</option>
                        </select>
                    </div>
                </div>
                <button class="button">Filtrer</button>
            </form>
        </section>

        <section class="mes-mobs">
            <h3>Mes monstres</h3>
            <article>
                <h4></h4>
                <img src="" alt="">
                <div class="monstre-nombre">
                    <span class="moins-button" data-id="2" title="Retirer un exemplaire de ce monstre"><i class="fa-solid fa-minus"></i></span>
                    <span class="nombre" id="nombre_2">0</span>
                    <span class="plus-button" data-id="2" title="Ajouter un exemplaire de ce monstre"><i class="fa-solid fa-plus"></i></span>
                </div>
                <div class="content-rechprop">
                    <input type="radio" class="radio-rechprop" name="status_2" id="propose_2" data-id="2" value="propose">
                    <label class="label-rechprop propose" for="propose_2" id="label_propose_2">Proposer</label>
                    <input type="radio" class="radio-rechprop" name="status_2" id="aucun_2" data-id="2" value="aucun" checked="">
                    <label class="label-rechprop" for="aucun_2" id="label_aucun_2">-</label>
                    <input type="radio" class="radio-rechprop" name="status_2" id="recherche_2" data-id="2" value="recherche">
                    <label class="label-rechprop recherche" for="recherche_2" id="label_recherche_2">Rechercher</label>
                    <div class="toggle"></div>
                </div>
            </article>
        </section>
    </div>
</main>

<?php
// Inclusion du footer
require_once "assets/core/footer.php";
?>
