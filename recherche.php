<?php
require_once 'php_functions.php';
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: ajout.php");
    exit();
}
try {
    $connection = mysqli_connect("localhost", "root", "", "projet_php") or die("Connection failed : " . mysqli_connect_error());
    $sports_array = get_sports($connection);
    $niveaux_array = get_niveaux($connection);
    $regions_array = get_regions($connection);
    $selected_sport_id = "";
    $selected_sport_name = "";
    $selected_niveau_id = "";
    $selected_niveau_name = "";
    $selected_region = "";
    $partners = null;
    
    $niveaux_name_array = [];
    foreach($niveaux_array as $niveau) {
        $niveaux_name_array[$niveau['niveau_id']] = $niveau['niveau_name'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['rechercher'])) {
            $selected_sport_id = $_POST['sport'] ?? '';
            $selected_niveau_id = $_POST['niveau'] ?? '';
            $selected_region = $_POST['region'] ?? '';
            $partners = get_partners($connection, $_POST);

            foreach ($sports_array as $sport) {
                if ($sport['sport_id'] == $selected_sport_id) {
                    $selected_sport_name = $sport['sport_name'];
                    break;
                }
            }

            if (!empty($selected_niveau_id)) {
                $selected_niveau_name = $niveaux_name_array[$selected_niveau_id];
            }
        }
    }

} catch (Exception $e) {
    error_log("Database connection error : " . $e->getMessage());
    echo "<h1>Database connection problem. Try again.</h1>";

} finally {
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recherche | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="recherche-page">
                <h1>Le site des rencontres sportives</h1>
                <hr>

                <div class="recherche-form-container">
                    <form class="recherche-form" method="POST" action="recherche.php">
                        <h3>Rechercher des partenaires</h3>
                        <div class="info-section">

                            <label for="sport">Sport pratique :</label>
                            <select name="sport" id="sport" required>
                                <option value="" selected>Choississez un sport</option>
                                <?php foreach ($sports_array as $sport) { ?>
                                    <option value="<?= $sport['sport_id'] ?>"
                                        <?php if ($selected_sport_id == $sport['sport_id']) echo 'selected'; ?>>
                                            <?= $sport['sport_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label for="niveau">Niveau :</label>
                            <select name="niveau" id="niveau">
                                <option value="" selected>Choississez un niveau</option>
                                <?php foreach ($niveaux_array as $niveau) { ?>
                                    <option value="<?= $niveau['niveau_id'] ?>"
                                        <?php if ($selected_niveau_id == $niveau['niveau_id']) echo 'selected'; ?>>
                                            <?= $niveau['niveau_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label for="region">Region :</label>
                            <select name="region" id="region">
                                <option value="" selected>Choississez une region</option>
                                <?php foreach ($regions_array as $region) { ?>
                                    <option value="<?= $region ?>"
                                        <?php if ($selected_region == $region) echo 'selected'; ?>>
                                            <?= $region ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="btn-section">
                            <button type="submit" class="btn btn-success" name="rechercher">Rechercher</button>
                            <a href="recherche.php" class="btn btn-danger">Effacer</a>
                        </div>

                        <div class="link-section">
                            <a href="accueil.php" class="btn btn-warning">Accueil</a>
                            <a href="ajout.php" class="btn btn-warning">Inscription</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="result-section">
                <?php if (isset($_POST['rechercher'])) { ?>
                    <?php if (mysqli_num_rows($partners) > 0) { ?>
                        <h2 class="mt-4">
                            Resultats de recherche pour le sport : <strong><?= $selected_sport_name ?></strong>
                            <?php if (!empty($selected_niveau_name)) { ?>
                                - Niveau : <strong><?= $selected_niveau_name ?></strong>
                            <?php } ?>
                            <?php if (!empty($selected_region)) { ?>
                                - Region : <strong><?= $selected_region ?></strong>
                            <?php } ?>
                        </h2>

                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prenom</th>
                                    <?php if (empty($selected_niveau_id)) { ?>
                                        <th scope="col">Niveau</th>
                                    <?php } ?>
                                    <?php if (empty($selected_region)) { ?>
                                        <th scope="col">Region</th>
                                    <?php } ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($partners)) { ?>
                                    <tr>
                                        <td><?= $row['nom'] ?></td>
                                        <td><?= $row['prenom'] ?></td>
                                        <?php if (empty($selected_niveau_id)) { ?>
                                            <td><?= $row['niveau'] ?></td>
                                        <?php } ?>
                                        <?php if(empty($selected_region)) { ?>
                                            <td><?= $row['region'] ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    <?php } else { ?>
                        <div class="alert alert-info">Aucun partenaire trouver</div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php'; ?>
        </footer>
    </body>
</html>