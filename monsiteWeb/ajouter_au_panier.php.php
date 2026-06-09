<?php
session_start();
require_once 'includes/connexion.php'; // optionnel si tu veux vérifier que le produit existe

$id_produit = $_GET['id'];
$quantite = $_POST['quantite'];

// Création du panier s’il n’existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

// Ajout ou mise à jour de la quantité
if (isset($_SESSION['panier'][$id_produit])) {
    $_SESSION['panier'][$id_produit] += $quantite;
} else {
    $_SESSION['panier'][$id_produit] = $quantite;
}

// Redirection vers la page du panier
header('Location: panier.php');
exit();
?>
<nav style="background: #007BFF; padding: 10px;">
    <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none;">Accueil</a>
    <a href="produits.php" style="color: white; margin-right: 15px; text-decoration: none;">Produits</a>
    <a href="panier.php" style="color: white; margin-right: 15px; text-decoration: none;">Panier</a>
    <a href="paiement.html" style="color: white; text-decoration: none;">Paiement</a>
</nav>
