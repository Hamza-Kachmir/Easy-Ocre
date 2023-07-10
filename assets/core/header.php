<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Ocre</title>
    <meta name="description" content="Easy Ocre facilite la quête du dofus Ocre">
    <meta name="keywords" content="Dofus Ocre, Dofus, Archimonstre, Mob, Monstre">
    <meta name="author" content="Hamza">
    <link rel="icon" type="image/png" href="assets/img/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <script src="assets/js/app.js" defer></script>
    
</head>


<body>
    <header>
        <nav class="navbar-header">
            <div class="contenair-header">
                <a href="/index.php"><img src="assets/img/logo.png" alt="" class="logo"></a>
                  
                <!-- Page commune -->
                <a href="/kralamoure.php" class="lien-nav">Kralamoure</a>

                <?php if (isset($_SESSION['id'])) : ?>

                    <!-- Pages pour utilisateurs connectés -->
                    <a href="/mes-monstres.php" class="lien-nav">Mes monstres</a>
                    <a href="/communaute.php" class="lien-nav">Communauté</a>
                    <a href="/mon-profil.php" class="lien-nav">Mon profil</a>
                    <a href="logout.php" class="lien-nav">Se déconnecter</a>

                <?php else: ?>

                    <!-- Pages pour utilisateurs non connectés -->
                    <a href="/register.php" class="lien-nav">Inscription</a>
                    <a href="/login.php" class="lien-nav">Connexion</a>

                <?php endif; ?>

                <label for="theme" class="theme">
  <input id="theme" name="theme" class="toggle-checkbox" type="checkbox">
  <div class="toggle-slot">
    <div class="sun-icon-wrapper">
      <div class="iconify sun-icon" data-icon="feather-sun" data-inline="false"></div>
    </div>
    <div class="toggle-button"></div>
    <div class="moon-icon-wrapper">
      <div class="iconify moon-icon" data-icon="feather-moon" data-inline="false"></div>
    </div>
  </div>
  
</label>
<i class="fa-solid fa-bars"></i>


            </div>
        </nav>
    </header>
