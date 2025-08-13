<?php
require_once 'php_functions.php';
session_start();
try {
    $connection = mysqli_connect("localhost", "root", "", "projet_php") or die("Connection failed : " . mysqli_connect_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST['envoyer']) &&
            !empty($_POST['nom']) &&
            !empty($_POST['prenom']) &&
            !empty($_POST['dob']) &&
            !empty($_POST['email']) &&
            !empty($_POST['region']) &&
            !empty($_POST['sport']) &&
            !empty($_POST['niveau'])
            ) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];
            $region = $_POST['region'];
            $sport_id = $_POST['sport'];
            $niveau_id = $_POST['niveau'];
            post_user($connection, $nom, $prenom, $dob, $email, $region);
            $user_id = mysqli_insert_id($connection);
            post_inscription($connection, $user_id, $sport_id, $niveau_id);
            $_SESSION['ajout_success'] = "Inscription reussie";
            header("Location: ajout.php");
            exit();
        }


        if (isset($_POST["ajouter"]) && !empty($_POST["newSport"])) {
            $newSport = $_POST["newSport"];
            post_sport($connection, $newSport);
            header("Location: ajout.php");
            exit();
        }
    }
    
    $sports_array = get_sports($connection);
    $niveaux_array = get_niveaux($connection);

} catch (Exception $e) {
    error_log("Database connection error : " . $e->getMessage());
    echo "<h1>Database connection problem. Try again.</h1>";

} finally {
    mysqli_close($connection);
}

$user_nom = '';
$user_prenom = '';
$user_dob = '';
$user_email = '';
$user_region = '';
if (isset($_GET['action']) && $_GET['action'] === 'ajout_sport') {
    if (isset($_SESSION['nom']) && !empty($_SESSION['nom'])) {
        $user_nom = $_SESSION['nom'];
    }
    if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
        $user_prenom = $_SESSION['prenom'];
    }
    if (isset($_SESSION['dob']) && !empty($_SESSION['dob'])) {
        $user_dob = $_SESSION['dob'];
    }
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        $user_email = $_SESSION['email'];
    }
    if (isset($_SESSION['region']) && !empty($_SESSION['region'])) {
        $user_region = $_SESSION['region'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>S'inscrire | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>
            
            <div class="ajout-page">
                <h1>Formulaire d'inscription</h1>
                <hr>

                <div class="ajout-form">
                    <?php if (isset($_GET['message']) && $_GET['message'] === 'no_account') { ?>
                        <div class="alert alert-danger" role="alert">E-mail pas reconnu. Veuillez vous inscrire ci-dessous</div>
                    <?php } ?>

                    <form method="POST" action="ajout.php">
                        <div class="detail-section mb-5">
                            <div class="coordonate-section">
                                <h3>Vos coordonees</h3>
                                
                                <div>
                                    <input type="text" name="nom" id="nom" value="<?= $user_nom ?>" placeholder="Nom">
                                </div>
                                <div>
                                    <input type="text" name="prenom" id="prenom" value="<?= $user_prenom ?>" placeholder="Prenom">
                                </div>
                                <div>
                                    <input type="date" name="dob" id="dob" value="<?= $user_dob ?>" placeholder="jj/mm/aaaa">
                                </div>
                                <div>
                                    <input type="email" name="email" id="email" value="<?= $user_email ?>" placeholder="Email">
                                </div>
                                <div>
                                    <input type="text" name="region" id="region" value="<?= $user_region ?>" placeholder="Region">
                                </div>
                            </div>
                            
                            <div class="sport-section">
                                <h3>Vos pratiques sportives</h3>

                                <select name="sport" id="sport">
                                    <option value="" disabled selected>Choississez votre sport pratique</option>
                                    <?php foreach ($sports_array as $sport) { ?>
                                        <option value="<?= $sport['sport_id'] ?>">
                                            <?= $sport['sport_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <p>Si votre sport n'est pas dans la liste, ajoutez le ici</p>
                                <div class="new-sport-section">
                                    <input type="text" name="newSport" id="newSport" placeholder="Ajouter un sport">
                                    <button class="btn btn-secondary" type="submit" name="ajouter" onclick="enableAjouter()">Ajouter</button>
                                </div>

                                <select name="niveau" id="niveau">
                                    <option value="" disabled selected>Choississez votre niveau</option>
                                    <?php foreach ($niveaux_array as $niveau) { ?>
                                        <option value="<?= $niveau['niveau_id'] ?>">
                                            <?= $niveau['niveau_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="post-success">
                            <?php if (isset($_SESSION['ajout_success'])) {
                                echo '<div class="alert alert-success mt-4">' . $_SESSION['ajout_success'] . '</div>';
                                unset($_SESSION['ajout_success']);
                            } ?>
                        </div>

                        <div class="button-section">
                            <button class="btn btn-primary" type="submit" name="envoyer" onclick="enableEnvoyer()">Envoyer</button>
                            <button class="btn btn-danger" type="reset" name="effacer">Effacer</button>
                            <a href="accueil.php" class="btn btn-success">Accueil</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php'; ?>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function enableEnvoyer() {
                document.getElementById("nom").setAttribute("required", true);
                document.getElementById("prenom").setAttribute("required", true);
                document.getElementById("dob").setAttribute("required", true);
                document.getElementById("email").setAttribute("required", true);
                document.getElementById("region").setAttribute("required", true);
                document.getElementById("sport").setAttribute("required", true);
                document.getElementById("niveau").setAttribute("required", true);
                document.getElementById("newSport").removeAttribute("required");
            }

            function enableAjouter() {
                document.getElementById("newSport").setAttribute("required", true);
                document.getElementById("nom").removeAttribute("required");
                document.getElementById("prenom").removeAttribute("required");
                document.getElementById("dob").removeAttribute("required");
                document.getElementById("email").removeAttribute("required");
                document.getElementById("region").removeAttribute("required");
                document.getElementById("sport").removeAttribute("required");
                document.getElementById("niveau").removeAttribute("required");
            }
        </script>
    </body>
</html>