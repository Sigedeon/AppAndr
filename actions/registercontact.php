<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php'); // Assurez-vous que config.php contient les informations de connexion MySQL

// Récupérer les données JSON envoyées par l'application
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['nom']) && isset($data['adresse']) && isset($data['phone']) && isset($data['decision']) && isset($data['remarque'])) {
    
    // Protection contre les failles XSS
    $nom = htmlspecialchars($data['nom']);
    $adresse = htmlspecialchars($data['adresse']);
    $phone = htmlspecialchars($data['phone']);
    $decision = htmlspecialchars($data['decision']);
    $remarque = htmlspecialchars($data['remarque']);
    $id_user = htmlspecialchars($data['id_user']);
    

    // Vérifier si l'utilisateur existe déjà avec le même nom
    $checkUser = $bdd->prepare('SELECT * FROM users WHERE nom = ?');
    $checkUser->execute(array($nom));

    if ($checkUser->rowCount() == 0) {
        // Insérer le nouvel utilisateur dans la base de données
        $insertUser = $bdd->prepare('INSERT INTO contacts (nom, adresse, phone, decision, remarque, id_user) VALUES (?, ?, ?, ?, ?, ?)');
        
        if ($insertUser->execute(array($nom, $adresse, $phone, $decision, $remarque, $id_user))) {
            $response = [
                "success" => true,
                "message" => "Enregistrement réussi"
            ];
        } else {
            $response = [
                "success" => false,
                "error" => "Erreur lors de l'enregistrement"
            ];
        }
    } else {
        // L'utilisateur existe déjà
        $response = [
            "success" => false,
            "error" => "Cet email est déjà utilisé"
        ];
    }
} else {
    // Paramètres manquants
    $response = [
        "success" => false,
        "error" => "Données manquantes pour"
    ];
}

// Envoyer la réponse sous format JSON
echo json_encode($response);
?>