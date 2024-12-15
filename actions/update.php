<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Initialiser une réponse par défaut
$response = [
    "success" => false,
    "error" => "Mise à Jour non autorisée"
];

try {
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier si toutes les clés nécessaires sont présentes
    if (
        isset($data['id']) && !empty($data['id']) &&
        isset($data['nom']) && !empty($data['nom']) &&
        isset($data['adresse']) && !empty($data['adresse']) &&
        isset($data['phone']) && !empty($data['phone']) &&
        isset($data['decision']) && !empty($data['decision']) &&
        isset($data['remarque']) && !empty($data['remarque'])
    ) {

        $id = htmlspecialchars($data['id']);
        $nom = htmlspecialchars(trim($data['nom']));
        $adresse = htmlspecialchars(trim($data['adresse']));
        $phone = htmlspecialchars(trim($data['phone']));
        $decision = htmlspecialchars(trim($data['decision']));
        $remarque = htmlspecialchars(trim($data['remarque']));

        $sql = "UPDATE contact SET nom = $nom, adresse = $adresse, phone = $phone, decision = $decision, remarque = $remarque WHERE id = $id";
        if ($bdd->exec($sql)) {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }

        $response = [
            "success" => true,
            "message" => "Mise à jour réussie"
        ];
    }
} catch (Exception $e) {
    // Retourner une erreur en cas d'exception
    $response = [
        "success" => false,
        "error" => $e->getMessage()
    ];
}

// Envoyer la réponse sous format JSON
echo json_encode($response);
