<?php
require_once 'php_functions.php';
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: index.php");
    exit();
}
try {
    $connection = mysqli_connect("localhost", "root", "", "projet_php") or die("Connection failed : " . mysqli_connect_error());
    $produits_array = get_produits($connection);
    $produits_indexed = [];
    foreach ($produits_array as $produit) {
        $produits_indexed[$produit['produit_id']] = $produit;
    }

} catch (Exception $e) {
    error_log("Database connection error : " . $e->getMessage());
    echo "<h1>Database connection problem. Try again.</h1>";

} finally {
    mysqli_close($connection);
}
$panier = get_cart($produits_indexed);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="checkout-page">
                <h1>Completer votre commande</h1>
                <hr>

                <div class="commande-section">
                    <form class="checkout-form" method="POST" action="checkout.php">
                        <div class="detail-section">
                            <h5>ADRESSE DE LIVRAISON</h5>

                            <div class="section-mix">
                                <input type="text" placeholder="Prenom *">
                                <input type="text" placeholder="Nom *">
                            </div>
                            <div class="section">
                                <input type="text" placeholder="Adresse *">
                            </div>
                            <div class="section">
                                <input type="text" placeholder="Apt / Suite / Unite (Optionel)">
                            </div>
                            <div class="section-mix">
                                <input type="text" placeholder="Ville *">
                                <select>
                                    <option value="">Province *</option>
                                </select>
                            </div>
                            <div class="section">
                                <input type="text" placeholder="Code Postal *">
                            </div>
                        
                            <h5>INFORMATION DE PAIEMENT</h5>
                            <div class="section">
                                <input type="number" placeholder="Numero de carte *">
                            </div>
                            <div class="section-mix">
                                <input type="number" maxlength="2" placeholder="MM *">
                                <input type="number" maxlength="2" placeholder="AA *">
                            </div>
                            <div class="section">
                                <input type="number" maxlength="3" placeholder="CVV *">
                            </div>

                            <div>
                                <button type="submit" class="btn btn-success btn-lg">PASSER VOTRE COMMANDE</button>
                            </div>
                        </div>
                    </form>

                    <div class="vertical-hr"></div>

                    <div class="resume-container">
                        <h5>RESUME DE COMMANDE</h5>
                        <div class="resume-total">
                            <div>
                                <p>Sous-total :<p>
                                <p><small>Taxe 1 :</small></p>
                                <p><small>Taxe 2 :</small></p>
                                <hr class="divider" />
                                <h4>TOTAL :</h4>
                            </div>
                            <div class="montants-section">
                                <p><strong>$<?= $panier['sous-total'] ?></strong></p>
                                <p><small><strong>$<?= $panier['taxe1'] ?></strong></small></p>
                                <p><small><strong>$<?= $panier['taxe2'] ?></strong></small></p>
                                <hr class="divider" />
                                <h4>$<?= $panier['total'] ?></h4>
                            </div>
                        </div>
                        <div>
                            <a href="#">Having trouble? Contact us : support@projet.com</a>
                        </div>
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