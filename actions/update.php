<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php'); // Assurez-vous que config.php contient les informations de connexion MySQL

// Récupérer les données JSON envoyées par l'application
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['id']) && isset($data['nom']) && isset($data['adresse']) && isset($data['phone']) && isset($data['decision']) && isset($data['remarque'])) {

    // Protection contre les failles XSS
    $id = htmlspecialchars($data['id']);
    $nom = htmlspecialchars($data['nom']);
    $adresse = htmlspecialchars($data['adresse']);
    $phone = htmlspecialchars($data['phone']);
    $decision = htmlspecialchars($data['decision']);
    $remarque = htmlspecialchars($data['remarque']);

    try {
        // Préparer la requête de mise à jour
        $updateQuery = $bdd->prepare(
            'UPDATE contacts 
             SET nom = ?, adresse = ?, phone = ?, decision = ?, remarque = ?
             WHERE id = ?'
        );

        // Exécuter la requête
        if ($updateQuery->execute([$nom, $adresse, $phone, $decision, $remarque, $id])) {
            $response = [
                "success" => true,
                "message" => "Mise à jour réussie"
            ];
        } else {
            $response = [
                "success" => false,
                "error" => "Erreur lors de la mise à jour"
            ];
        }
    } catch (PDOException $e) {
        $response = [
            "success" => false,
            "error" => "Erreur de base de données : " . $e->getMessage()
        ];
    }
} else {
    // Paramètres manquants
    $response = [
        "success" => false,
        "error" => "Données manquantes"
    ];
}

// Envoyer la réponse sous format JSON
echo json_encode($response);
?>
