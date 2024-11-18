<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Initialiser une réponse par défaut
$response = [
    "success" => false,
    "error" => "Données manquantes"
];

try {
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier si toutes les clés nécessaires sont présentes
    if (
        isset($data['nom']) && !empty($data['nom']) &&
        isset($data['adresse']) && !empty($data['adresse']) &&
        isset($data['phone']) && !empty($data['phone']) &&
        isset($data['decision']) && !empty($data['decision']) &&
        isset($data['date_save']) && !empty($data['date_save']) &&
        isset($data['remarque']) && !empty($data['remarque'])
    ) {
        // Nettoyer les données pour éviter les attaques XSS
        $nom = htmlspecialchars(trim($data['nom']));
        $adresse = htmlspecialchars(trim($data['adresse']));
        $phone = htmlspecialchars(trim($data['phone']));
        $decision = htmlspecialchars(trim($data['decision']));
        $date_save = htmlspecialchars(trim($data['date_save']));
        $remarque = htmlspecialchars(trim($data['remarque']));

        // Vérification du format du numéro de téléphone (optionnel)
        if (!preg_match('/^\d{10}$/', $phone)) {
            throw new Exception("Numéro de téléphone invalide");
        }

        // Insertion dans la base de données
        $insertCont = $bdd->prepare(
            'INSERT INTO contact (nom, adresse, phone, decision, date_save, remarque) VALUES (?, ?, ?, ?, ?, ?)'
        );

        if ($insertCont->execute([$nom, $adresse, $phone, $decision, $date_save, $remarque])) {
            $response = [
                "success" => true,
                "message" => "Enregistrement réussi"
            ];
        } else {
            throw new Exception("Erreur lors de l'enregistrement");
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

