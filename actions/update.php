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
        isset($data['date_save']) && !empty($data['date_save']) &&
        isset($data['remarque']) && !empty($data['remarque'])
    ) {
        
        $id = intval($data['id']);
        $nom = htmlspecialchars(trim($data['nom']));
        $adresse = htmlspecialchars(trim($data['adresse']));
        $phone = htmlspecialchars(trim($data['phone']));
        $decision = htmlspecialchars(trim($data['decision']));
        $date_save = htmlspecialchars(trim($data['date_save']));
        $remarque = htmlspecialchars(trim($data['remarque']));

        // Préparation et exécution de la requête UPDATE
        $updateCont = $bdd->prepare(
            'UPDATE contact SET nom = ?, adresse = ?, phone = ?, decision = ?, date_save = ?, remarque = ? WHERE id = ?'
        );

        if ($updateCont->execute([$nom, $adresse, $phone, $decision, $date_save, $remarque, $id])) {
            // Vérifier si une ligne a été modifiée
            if ($updateCont->rowCount() > 0) {
                $response = [
                    "success" => true,
                    "message" => "Mise à jour réussie"
                ];
            } else {
                throw new Exception("Aucun enregistrement trouvé avec cet ID");
            }
        } else {
            throw new Exception("Erreur lors de la mise à jour");
        }
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
?>