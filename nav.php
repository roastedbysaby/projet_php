<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Projet php</a>

        <div class="contact-us-nav text-white">
            <small><i class="fa-solid fa-location-dot"></i> Nous rejoindre | </small>
            <small><i class="fa-solid fa-phone-alt fa-flip-horizontal"></i> Par telephone : (123)456-7890 |</small>
            <small><i class="fa-solid fa-envelope"></i> Par e-mail : lorem@ipsum.com</small>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle main navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Index</a></li>
                <li class="nav-item"><a class="nav-link" href="ajout.php">Inscription</a></li>
                <?php if (isset($_SESSION['nom'])) { ?>
                    <li class="nav-item"><a class="nav-link" href="recherche.php">Recherche</a></li>
                    <li class="nav-item"><a class="nav-link" href="achat.php">Achat</a></li>
                    <li class="nav-item">
                        <form method="POST" action="logout.php">
                            <button class="nav-link" type="submit">Deconnexion</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>