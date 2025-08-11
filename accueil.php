<?php
require_once 'php_functions.php';
session_start();
try {
    $connection = mysqli_connect("localhost", "root", "", "projet_php") or die("Connection failed : " . mysqli_connect_error());
    $user_count = get_user_count($connection);
    $images_array = get_images($connection);

} catch (Exception $e) {
    error_log("Database connection error : " . $e->getMessage());
    echo "<h1>Database connection problem. Try again.</h1>";

} finally {
    mysqli_close($connection);
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acceuil | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>




    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="front-page">
                <div class="intro">
                    <h2>Bienvenue au site sportif professionel</h2>
                    <p>Inscrivez-vous des aujourd'hui et entrez en contact avec des personnes partageant votre passion pour le sport. Vous pouvez chercher des partenaires par categorie de sport pratiquer, par niveau et par region!</p>
                    <p class="mb-5">
                        <strong>Rejoignez nos <span class="user-count"><?= $user_count ?></span> membres des maintenant!</strong>
                    </p>
                    <div class="join-now">
                        <a href="ajout.php" class="btn btn-primary btn-lg">COMMENCEZ L'AVENTURE</a>
                    </div>    
                </div>

                <div class="w-75 mx-auto p-3">
                    <div id="carouselControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $i = 0;
                            foreach ($images_array as $image) {
                                $active_class = ($i == 0) ? 'active' : '';
                            ?>
                                <div class="carousel-item img-fluid <?= $active_class; ?>">
                                    <img src="<?= $image['url']; ?>" class="d-block w-100" alt="...">
                                </div>
                            <?php $i++;
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Mext</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php' ?>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
