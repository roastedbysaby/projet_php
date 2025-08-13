<?php
require_once 'php_functions.php';
session_start();
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
        <title>Panier | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="panier-page">
                <h1>Votre panier</h1>
                <hr>

                <div class="panier-section card mb-3">
                <?php if (empty($panier['produits'])) { ?>
                    <div class="alert alert-info">Votre panier est vide</div>

                <?php } else { ?>
                    <ul class="list-group">
                        <?php foreach ($panier['produits'] as $prod) { ?>
                            <li class="list-group-item">
                                <div class="row g-0 align-items-center">
                                    <div class="col-5">
                                        <strong><?= $prod['name'] ?></strong>
                                    </div>

                                    <div class="col-auto d-flex align-items-center justify-content-center mx-auto">
                                        <small class="me-2">Quantite : </small>

                                        <form action="update_quantite.php" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="produit_id" value="<?= $prod['produit_id'] ?>">
                                            <button type="submit" name="action" value="baisser" class="btn btn-sm btn-outline-secondary me-1"><strong>-</strong></button>
                                            <input type="number" name="quantite" class="form-control text-center quantity-input" style="width: 60px;" value="<?= $prod['quantite'] ?>" min="1">
                                            <button type="submit" name="action" value="augmenter" class="btn btn-sm btn-outline-secondary ms-1"><strong>+</strong></button>
                                        </form>

                                    </div>

                                    <div class="col-auto ms-3" style="width: 80px; text-align: end;">
                                        <span>$<?= number_format($prod['prodTotal'], 2) ?></span>
                                    </div>    
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <hr>

                    <div class="d-flex justify-content-end">
                        <div class="text-start">
                            <p><strong>Sous-total :</strong></p>
                            <p><small>Taxe 1 (5%) :</small></p>
                            <p><small>Taxe 2 (9%) :</small></p>
                            <h4 class="total-lbl">Total :</h4>
                        </div>

                        <div class="text-end">
                            <p><strong>$<?= number_format($panier['sous-total'], 2) ?></strong></p>
                            <p><small><strong>$<?= number_format($panier['taxe1'], 2) ?></strong></small></p>
                            <p><small><strong>$<?= number_format($panier['taxe2'], 2) ?></strong></small></p>
                            <h4 class="total-amount">$<?= number_format($panier['total'], 2) ?></h4>
                        </div>
                    </div>
                </div>

                <div class="cart-btn-section">
                    <a href="achat.php" class="btn btn-secondary">Continuer a magasiner</a>
                    <?php if (isset($_SESSION['nom']) && $_SESSION['panier'] > 0) { ?>
                        <a href="checkout.php" class="btn btn-success">Checkout</a>
                    <?php
                    } 
                    if (!isset($_SESSION['nom']) && !empty($_SESSION['panier'])) { ?>
                        <a href="index.php">Veuillez vous inscrire ou vous connecter pour finaliser la commande</a>    
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php' ?>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>