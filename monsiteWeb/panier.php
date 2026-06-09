<?php
session_start();
require_once 'includes/connexion.php';
<link rel="stylesheet" href="kerim.css">


$panier = $_SESSION['panier'] ?? array();
$total = 0;

echo "<h2>Votre panier</h2>";

if (!empty($panier)) {
    $ids = implode(',', array_keys($panier));
    $result = $connexion->query("SELECT * FROM produits WHERE id IN ($ids)");

    while ($produit = $result->fetch_assoc()) {
        $id = $produit['id'];
        $quantite = $panier[$id];
        $sous_total = $produit['prix'] * $quantite;
        $total += $sous_total;

        echo "<p>{$produit['nom']} - Quantité : $quantite - Prix : {$produit['prix']} FCFA - Sous-total : $sous_total FCFA</p>";
    }

    echo "<hr><p><strong>Total : $total FCFA</strong></p>";
    echo '<a href="commande.php">Passer la commande</a>';
} else {
    echo "<p>Votre panier est vide.</p>";
}
?>
<nav style="background: #007BFF; padding: 10px;">
    <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none;">Accueil</a>
    <a href="produits.php" style="color: white; margin-right: 15px; text-decoration: none;">Produits</a>
    <a href="panier.php" style="color: white; margin-right: 15px; text-decoration: none;">Panier</a>
    <a href="paiement.html" style="color: white; text-decoration: none;">Paiement</a>
</nav>

