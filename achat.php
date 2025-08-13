<?php
require_once 'php_functions.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'])) {
    $produitId = $_POST['produit_id'];
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (isset($_SESSION['panier'][$produitId])) {
        $_SESSION['panier'][$produitId]['quantite']++;
    } else {
        $_SESSION['panier'][$produitId] = ['quantite' => 1];
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produits | Projet php</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="wrapper">
            <header>
                <?php require_once 'nav.php'; ?>
            </header>

            <div class="achat-page">
                <div class="title-cart">
                    <h1>Catalogue de nos produits sportifs</h1>
                    <button class="cart-btn btn btn-primary" name="panier" id="panier" data-bs-toggle="modal" data-bs-target="#cartModal">
                        panier (
                            <?php
                            $totalQty = 0;
                            if (isset($_SESSION['panier'])) {
                                foreach ($_SESSION['panier'] as $prod) {
                                    $totalQty += $prod['quantite'];
                                }
                            }
                            echo $totalQty;
                            ?>
                            )
                    </button>
                </div>

                <hr>

                <div class="catalog-section">
                    <?php if ($produits_array > 0) { ?>
                        <div class="row row-cols-1 row-cols-md-5 g-4">
                            <?php foreach ($produits_array as $produit) { ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="image-container">
                                                <div class="card-image">
                                                    <img src="<?= $produit['produit_image'] ?>">
                                                </div>
                                            </div>

                                            <h5 class="card-title"><?= $produit['produit_name'] ?></h5>
                                            <p class="card-text">Prix : <strong>$<?= $produit['produit_prix'] ?></strong></p>
                                            
                                            <form method="POST" action="achat.php">
                                                <input type="hidden" name="produit_id" value="<?= $produit['produit_id'] ?>">
                                                <button class="btn btn-success">Ajouter au panier</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    <?php }  else { ?>
                        <div class="alert alert-info">Aucun produit disponible dans le catalogue. Revenez plus tard.</div>
                    <?php } ?>
                </div>

            </div>
        </div>

        <footer class="footer">
            <?php require_once 'footer.php' ?>
        </footer>

        <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLbl" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartModalLbl">Panier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $panier = get_cart($produits_indexed);
                        if (empty($panier['produits'])) {
                            echo "<div class='alert alert-info'>Panier Vide</div>";
                        } else {
                            echo "<ul class='list-group'>";
                            foreach ($panier['produits'] as $prod) {
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                echo "<div><strong>" . $prod['name'] . "</strong>";
                                echo "<br><small>Quantite : " . $prod['quantite'] . "</small></div>";
                                echo "<span>$" . number_format($prod['prodTotal'], 2) . "</span>";
                                echo "</li>";
                            }
                            echo "</ul>";
                            echo "<hr>";
                            echo "<div class='text-right'><strong>Sous-Total : $" . number_format($panier['sous-total'], 2) . "</strong></div>";
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a href="panier.php" class="btn btn-secondary">Acceder au panier</a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>