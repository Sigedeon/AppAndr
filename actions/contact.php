<?php
header('Content-Type: application/json; charset=utf-8');
// Ajouter ces headers pour CORS si nécessaire
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include_once('../actions/config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "error" => "Méthode non autorisée. Utilisez POST."
    ]);
    http_response_code(405);
    exit;
}

$response = [
    "success" => false,
    "error" => "Données manquantes"
];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Vérifier si json_decode a réussi
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Format JSON invalide");
    }

    // Validation des données requises
    $required_fields = ['nom', 'adresse', 'phone', 'decision', 'date_save', 'remarque'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception("Le champ $field est requis");
        }
    }

    // Nettoyer les données
    $nom = htmlspecialchars(trim($data['nom']));
    $adresse = htmlspecialchars(trim($data['adresse']));
    $phone = htmlspecialchars(trim($data['phone']));
    $decision = htmlspecialchars(trim($data['decision']));
    $date_save = htmlspecialchars(trim($data['date_save']));
    $remarque = htmlspecialchars(trim($data['remarque']));

    // Validation du téléphone
    if (!preg_match('/^\d{10}$/', $phone)) {
        throw new Exception("Numéro de téléphone invalide");
    }

    // Validation de la date
    if (!strtotime($date_save)) {
        throw new Exception("Format de date invalide");
    }

    $insertCont = $bdd->prepare(
        'INSERT INTO contact (nom, adresse, phone, decision, date_save, remarque) 
         VALUES (:nom, :adresse, :phone, :decision, :date_save, :remarque)'
    );

    $success = $insertCont->execute([
        ':nom' => $nom,
        ':adresse' => $adresse,
        ':phone' => $phone,
        ':decision' => $decision,
        ':date_save' => $date_save,
        ':remarque' => $remarque
    ]);

    if (!$success) {
        throw new Exception("Erreur lors de l'enregistrement dans la base de données");
    }

    $response = [
        "success" => true,
        "message" => "Enregistrement réussi",
        "id" => $bdd->lastInsertId()
    ];
    http_response_code(201); // Created

} catch (Exception $e) {
    $response = [
        "success" => false,
        "error" => $e->getMessage()
    ];
    http_response_code(400); // Bad Request
}

echo json_encode($response);
exit;
?>