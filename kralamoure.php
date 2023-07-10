<?php
session_start();

// Inclusion du fichier d'en-tête et du fichier de config
require_once "assets/core/header.php";
require_once "assets/core/config.php";

// Définir une redirection
$_SESSION['redirect'] = 'kralamoure.php';

// Définition de la fonction pour obtenir tous les serveurs
function getServers($db) {
    $stmt = $db->prepare("SELECT * FROM serveur ORDER BY nom_serveur ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Création d'une nouvelle instance de PDO pour accéder à la base de données
$db = new PDO($dsn, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$servers = getServers($db);

// Si des serveurs existent, on sélectionne le serveur envoyé par GET ou par défaut le premier serveur, sinon on sélectionne null
$selectedServer = isset($servers) && !empty($servers) ? ($_GET['server'] ?? $servers[0]['nom_serveur']) : ($_GET['server'] ?? null);

// Configuration de la date et de l'heure
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

// On récupère le mois et l'année en GET ou par défaut le mois et l'année courante
$ym = $_GET['ym'] ?? date('Y-m');

// Création d'un objet DateTime
$dt = DateTime::createFromFormat('Y-m|', $ym);

if ($dt === false) {
    die('Erreur : la date fournie n\'est pas valide.');
}

$today = new DateTime('today');

// Formatter la date
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, null, null, 'MMMM yyyy');
$html_title = ucfirst(mb_convert_encoding($formatter->format($dt), 'UTF-8', 'auto'));

// Obtenir le mois précédent et le mois suivant
$prev = $dt->modify('-1 month')->format('Y-m');
$next = $dt->modify('+2 months')->format('Y-m');

$dt->modify('-1 month');

// Obtenir le nombre de jours dans le mois et le jour de début de la semaine
$day_count = (int)$dt->format('t');
$start_day = (int)$dt->format('N');

$weeks = [];
$week = str_repeat('<td></td>', $start_day - 1);

$event_link = '';

// Définition de la fonction pour supprimer les événements passés
function removePastEvents($db) {
    $currentDate = date("Y-m-d H:i:s");
    $stmt = $db->prepare("DELETE FROM kralamoure WHERE date < ?");
    $stmt->execute([$currentDate]);
}



// Appeler la fonction pour supprimer les événements passés
removePastEvents($db);

$events = [];
if (isset($_SESSION['id'])) {
    // Préparation de la requête pour récupérer les événements si l'utilisateur est connecté
    $stmt = $db->prepare("
        SELECT kralamoure.*, utilisateur.pseudo 
        FROM kralamoure 
        INNER JOIN organisateur ON organisateur.id_kralamoure = kralamoure.id
        INNER JOIN utilisateur ON utilisateur.id = organisateur.id_utilisateur
        WHERE YEAR(kralamoure.date) = ? 
        AND MONTH(kralamoure.date) = ? 
        AND kralamoure.id_serveur = ?
    ");

    if ($stmt) {
        $stmt->execute([$dt->format('Y'), $dt->format('m'), $_SESSION['id_serveur']]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Préparation de la requête pour récupérer les événements si l'utilisateur n'est pas connecté
    $stmt = $db->prepare("
        SELECT kralamoure.*, utilisateur.pseudo 
        FROM kralamoure 
        JOIN organisateur ON kralamoure.id = organisateur.id_kralamoure
        JOIN utilisateur ON utilisateur.id = organisateur.id_utilisateur
        WHERE YEAR(kralamoure.date) = ? 
        AND MONTH(kralamoure.date) = ?
        AND kralamoure.id_serveur = (SELECT id FROM serveur WHERE nom_serveur = ?)
    ");

    if ($stmt) {
        $stmt->execute([$dt->format('Y'), $dt->format('m'), $selectedServer]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

for ($day = 1; $day <= $day_count; $day++) {
    $date = DateTime::createFromFormat('Y-m-j|', $ym . '-' . $day);
    $isToday = $today == $date;
    $isPast = $date < $today;
    $event = null;

    // Boucle pour trouver un événement ce jour
    foreach ($events as $e) {
        $event_date = DateTime::createFromFormat('Y-m-d H:i:s', $e['date']);
        if ($event_date && $event_date->format('Y-m-j') == $date->format('Y-m-j')) {
            $event = $e;
            break;
        }
    }

    if ($event) {
        // Récupérer les informations de l'utilisateur
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE pseudo = ?");
        $stmt->execute([$event['pseudo']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('Erreur : utilisateur introuvable.');
        }

        // Définir le lien de l'événement
        if (isset($_SESSION['pseudo']) && $event['pseudo'] == $_SESSION['pseudo']) {
            $event_link = sprintf('<img src="assets/img/tentacule-primaire.png" title="Vous êtes l\'organisateur" alt="" class="edit_event" data-id="%s" data-date="%s" data-description="%s" data-organizer="%s" data-time="%s">', $event['id'], $event_date->format('Y-m-d'), htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8'), htmlspecialchars($event['pseudo'], ENT_QUOTES, 'UTF-8'), $event_date->format('H:i'));
        } else {
            $event_link = sprintf('<img src="assets/img/tentacule-secondaire.png" title="Ouverture Kralamoure prévu" alt="" class="join_event" data-id="%s" data-date="%s" data-description="%s" data-organizer="%s" data-time="%s">', $event['id'], $event_date->format('Y-m-d'), htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8'), htmlspecialchars($event['pseudo'], ENT_QUOTES, 'UTF-8'), $event_date->format('H:i'));
        }

        // Récupérer les détails de l'événement
        $event_date = $event_date->format('H:i d/m/Y');
        $event_description = $event['description'];
        $event_organizer = $user['pseudo'];
    } else if (isset($_SESSION['id']) && !$isPast) {
        // Créer un lien pour ajouter un événement si l'utilisateur est connecté et la date n'est pas passée
        $event_link = sprintf('<span title="Créer un evenement" class="ajouter_event" data-date="%s">+ Créer</span>', $date->format('Y-m-d'));
    } else {
        $event_link = '';
    }

    // Créer la structure de la semaine
    $week .= sprintf('<td%s>%d %s</td>', $isToday ? ' class="today"' : '', $day, $event_link);

    if (($day + $start_day - 1) % 7 === 0 || $day == $day_count) {
        if($day == $day_count) {
            $week .= str_repeat('<td></td>', 7 - ($day + $start_day - 1) % 7);
        }
        $weeks[] = '<tr>' . $week . '</tr>';
        $week = '';
    }
} // Fermeture de la boucle for
?>

<main>
    <!-- Conteneur principal pour la page Kralamoure -->
    <div class="container-kralamoure">
        <!-- Titre de la page -->
        <h3>Kralamoure</h3>
        <!-- Sous-titre de la page avec le titre du mois courant -->
        <h4>
            <?= $html_title; ?>
            </h4>
            <!-- Liens pour naviguer entre les mois -->
            <a href="?ym=<?= $prev; ?>">&lt;</a>
            <a href="?ym=<?= date('Y-m'); ?>">Aujourd'hui</a>
            <a href="?ym=<?= $next; ?>">&gt;</a>
        

        <!-- Vérifie si l'utilisateur est connecté ou non -->
        <?php if (!isset($_SESSION['id'])) { 
            $servers = getServers($db);
            if (!empty($servers)) {
                $selectedServer = $_GET['server'] ?? '';
        ?>
        <!-- Form pour sélectionner le serveur -->
        <form method="GET">
            <select name="server">
                <!-- Liste des serveurs disponibles -->
                <?php foreach($servers as $server) { 
                    if (!empty($server) && isset($server['nom_serveur'])) {
                        $selected = ($server['nom_serveur'] == $selectedServer) ? 'selected' : '';
                ?>
                <!-- Option pour chaque serveur -->
                <option value="<?php echo $server['nom_serveur']; ?>" <?php echo $selected; ?>><?php echo $server['nom_serveur']; ?></option>
                <?php 
                    }
                } ?>
            </select>
            <!-- Bouton pour valider la sélection du serveur -->
            <button type="submit">Sélectionner le serveur</button>
        </form>
        <?php 
            }
        } ?>
        <!-- Conteneur pour le calendrier -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <!-- Ligne pour les noms des jours de la semaine -->
                <tr class="tr-jour">
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mer</th>
                    <th>Jeu</th>
                    <th>Ven</th>
                    <th>Sam</th>
                    <th>Dim</th>
                </tr>
                <!-- Boucle pour afficher chaque semaine -->
                <?php foreach ($weeks as $week) {
                    echo $week;
                } ?>
            </table>
        </div>
    
        <!-- Modales pour les interactions avec les événements -->
        <div id="myModal-un" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un événement</h2>
                <form id="addEventForm" action="add-event.php" method="POST">
                    <label for="date">Date</label><br>
                    <input type="date" id="event_date" name="date" value="<?= date('Y-m-d'); ?>" readonly><br>
                    <label for="heure">Heure</label><br>
                    <select id="heure" name="heure"></select><br>
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea><br>
                    <input type="submit" onclick="confirmCreate(event)" value="Ajouter">
                </form>
            </div>
        </div>

        <!-- Affichage des détails d'un événement -->
        <div id="myModal-deux" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="event_date"></h2>
                <p id="event_description"></p>
                <p id="event_organizer"></p>
                <p id="event_time"></p>
            </div>
        </div>

        <!-- Modification et suppression d'un événement -->
        <div id="myModal-trois" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="event_date"></h2>
                <p id="event_description"></p>
                <p id="event_organizer"></p>
                <p id="event_time"></p>
                <form id="updateEventForm" action="update-event.php" method="POST">
                    <label for="update_description">Modifier la description</label>
                    <textarea id="update_description" name="description"></textarea>
                    <input type="hidden" name="event_id" value="<?php echo isset($event['id']) ? $event['id'] : ''; ?>">
                    <input type="submit" onclick="confirmUpdate(event)" value="Mettre à jour">
                </form>
                <form id="deleteEventForm" action="delete-event.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo isset($event['id']) ? $event['id'] : ''; ?>">
                    <input type="submit" onclick="confirmDelete(event)" value="Supprimer">
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Inclusion du pied de page -->
<?php
require_once "assets/core/footer.php";
?>
