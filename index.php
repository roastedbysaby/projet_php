<?php
require_once 'php_functions.php';
session_start();
try {
    $connection = mysqli_connect("localhost", "root", "", "projet_php") or die("Connection failed: " . mysqli_connect_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST['envoyer'])) {
            $email = $_POST['email'];
            $user = get_user($connection, $email);

            if ($user) {
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['dob'] = $user['date_naissance'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['region'] = $user['region'];
                header("Location: index.php");
                exit();
            } else {
                header("Location: ajout.php?message=no_account");
                exit();
            }
        }
    }

    $sports_array = get_sports($connection);

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
        <title>Se connecter | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>


    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="index-page">
                <h1>Liste des sports existants</h1>
                <hr>

                <div class="sports-list-container mb-4">
                    <ul>
                        <?php foreach ($sports_array as $sport) { ?>
                            <li><strong><?= $sport['sport_name'] ?></strong></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="id-form">
                    <form method="POST" action="">
                        <h3>Identification</h3>

                        <label for="email">Votre e-mail :</label>
                        <div class="id-section">
                            <input type="email" name="email" id="email" placeholder="E-mail" required>
                            <button class="btn btn-primary" type="submit" name="envoyer">Envoyer</button>
                        </div>

                    </form>

                    <?php if (!isset($_SESSION['nom'])) { ?>
                        <a href="ajout.php" class="btn btn-success">S'inscrire</a>
                    <?php } ?>
                </div>

                <div class="id-success-section">
                    <?php if (isset($_SESSION['nom'])){ ?>
                        <h2>Bonjour <?= $_SESSION['nom'] . ' ' . $_SESSION['prenom'] ?></h2>
                        <strong>Vous pouvez acceder a la page de recherche ou vous inscrire a un autre sport ci-dessous</strong>
                        <div>
                            <a href="recherche.php" class="btn btn-warning">Rechercher des partenaires</a>
                            <a href="ajout.php?action=ajout_sport" class="btn btn-warning">S'inscrire pour un sport</a>
                        </div>    
                    <?php } ?>
                </div>
            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php' ?>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>