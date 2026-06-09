<?php
require_once 'includes/connexion.php';

$result = $connexion->query("SELECT * FROM produits");
<link rel="stylesheet" href="kerim.css">


if ($result->num_rows > 0) {
    while ($produit = $result->fetch_assoc()) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px'>";
        echo "<h2>" . htmlspecialchars($produit['nom']) . "</h2>";
        echo "<p>" . htmlspecialchars($produit['description']) . "</p>";
        echo "<p><strong>Prix : </strong>" . $produit['prix'] . " FCFA</p>";
        echo "<a href='produit.php?id=" . $produit['id'] . "'>Voir le produit</a>";
        echo "</div>";
    }
} else {
    echo "Aucun produit disponible.";
}
?>
<nav style="background: #007BFF; padding: 10px;">
    <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none;">Accueil</a>
    <a href="produits.php" style="color: white; margin-right: 15px; text-decoration: none;">Produits</a>
    <a href="panier.php" style="color: white; margin-right: 15px; text-decoration: none;">Panier</a>
    <a href="paiement.html" style="color: white; text-decoration: none;">Paiement</a>
</nav>

