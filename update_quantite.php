<?php
session_start();
$produit_id = intval($_POST['produit_id']);
$quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 0;
$action = $_POST['action'] ?? null;

if (isset($_SESSION['panier'][$produit_id])) {
    $current_qty = intval($_SESSION['panier'][$produit_id]['quantite']);

    if ($action == "augmenter") {
        $new_qty = $current_qty + 1;
    } elseif ($action == "baisser") {
        $new_qty = $current_qty - 1;
    } else {
        $new_qty = $quantite;
    }

    if ($new_qty < 1) {
        unset($_SESSION['panier'][$produit_id]);
    } else {
        $_SESSION['panier'][$produit_id]['quantite'] = $new_qty;
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>