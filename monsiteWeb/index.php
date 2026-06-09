<?php
require_once 'includes/connexion.php';
$id = $_GET['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pièces Auto - IROBY</title>
    <link rel="stylesheet" href="kerim.css">

</head>
<body>

<h1>IROBY - Pièces Détachées Auto</h1>

<?php
if ($id) {
    // 🎯 Affichage d'un seul produit
    $res = $connexion->query("SELECT * FROM produits WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $p = $res->fetch_assoc();
        echo "<div class='detail'>";
        echo "<h2>" . htmlspecialchars($p['nom']) . "</h2>";
        echo "<p><strong>Description :</strong> " . htmlspecialchars($p['description']) . "</p>";
        echo "<p><strong>Prix :</strong> " . $p['prix'] . " FCFA</p>";
        echo "<form method='post' action='ajouter_panier.php?id=" . $p['id'] . "'>";
        echo "<label>Quantité : <input type='number' name='quantite' value='1' min='1'></label><br><br>";
        echo "<button type='submit'>Ajouter au panier</button>";
        echo "</form>";
        echo "<a class='retour' href='index.php'>&larr; Retour au catalogue</a>";
        echo "</div>";
    } else {
        echo "<p>Produit introuvable.</p>";
    }
} else {
    // 🧾 Affichage de la liste des produits
    $result = $connexion->query("SELECT * FROM produits");
    if ($result && $result->num_rows > 0) {
        while ($produit = $result->fetch_assoc()) {
            echo "<div class='produit'>";
            echo "<h2>" . htmlspecialchars($produit['nom']) . "</h2>";
            echo "<p>" . htmlspecialchars($produit['description']) . "</p>";
            echo "<p><strong>Prix :</strong> " . $produit['prix'] . " FCFA</p>";
            echo "<a class='bouton' href='index.php?id=" . $produit['id'] . "'>Voir le produit</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucun produit trouvé.</p>";
    }
}
?>

</body>
<nav style="background: #007BFF; padding: 10px;">
    <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none;">Accueil</a>
    <a href="produits.php" style="color: white; margin-right: 15px; text-decoration: none;">Produits</a>
    <a href="panier.php" style="color: white; margin-right: 15px; text-decoration: none;">Panier</a>
    <a href="paiement.html" style="color: white; text-decoration: none;">Paiement</a>
</nav>

</html>
