<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['id']) && isset($data['nom'])) {
    
    // Protection contre les failles XSS
    $id = htmlspecialchars($data['id']);
    $nom = htmlspecialchars($data['nom']);
    $adresse = htmlspecialchars($data['adresse']);
    $phone = htmlspecialchars($data['phone']);
    $decision = htmlspecialchars($data['decision']);
    $remarque = htmlspecialchars($data['remarque']);

    // Préparer la requête de mise à jour
    $updateQuery = $bdd->prepare(
        'UPDATE contact 
         SET nom = ?, adresse = ?, phone = ?, decision = ?, remarque = ? 
         WHERE id = ?'
    );

    // Exécuter la requête avec les valeurs
    if ($updateQuery->execute(array($nom, $adresse, $phone, $decision, $remarque, $id))) {
        $response = [
            "success" => true,
            "message" => "Contact mis à jour avec succès"
        ];
    } else {
        $response = [
            "success" => false,
            "error" => "Erreur lors de la mise à jour du contact"
        ];
    }
} else {
    // Paramètres manquants
    $response = [
        "success" => false,
        "error" => "Données manquantes"
    ];
}

// Envoyer la réponse JSON
echo json_encode($response);
