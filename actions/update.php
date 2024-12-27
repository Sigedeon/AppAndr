<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php'); // Configuration de la base de données

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

// Initialiser la réponse par défaut
$response = ["success" => false, "error" => "Une erreur est survenue"];

// Vérifier que toutes les données nécessaires sont présentes
if (isset($data['id'], $data['nom'], $data['adresse'], $data['phone'], $data['decision'], $data['remarque'])) {
    
    // Récupération et validation des données
    $id = $data['id'];
    $nom = $data['nom'];
    $adresse = $data['adresse'];
    $phone = $data['phone'];
    $decision = $data['decision'];
    $remarque = $data['remarque'];

    // Validation du numéro de téléphone
    if (!preg_match('/^\d{10}$/', $phone)) {
        http_response_code(400);
        $response['error'] = "Le numéro de téléphone est invalide (10 chiffres requis).";
        echo json_encode($response);
        exit;
    }

    try {
        // Préparer la requête de mise à jour
        $updateQuery = $bdd->prepare(
            'UPDATE contacts 
             SET nom = ?, adresse = ?, phone = ?, decision = ?, remarque = ?
             WHERE id = ?'
        );

        // Exécuter la requête
        $success = $updateQuery->execute([$nom, $adresse, $phone, $decision, $remarque, $id]);

        if ($success) {
            http_response_code(200); // Succès
            $response = [
                "success" => true,
                "message" => "Mise à jour réussie"
            ];
        } else {
            http_response_code(500); // Erreur serveur
            $response['error'] = "Erreur lors de la mise à jour des données.";
        }
    } catch (PDOException $e) {
        http_response_code(500); // Erreur serveur
        $response['error'] = "Erreur de base de données : " . $e->getMessage();
    }
} else {
    // Paramètres manquants
    http_response_code(400); // Mauvaise requête
    $response['error'] = "Données manquantes ou invalides.";
}

// Envoyer la réponse JSON
echo json_encode($response);
?>