<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['nom']) && isset($data['adresse']) && isset($data['phone']) && 
    isset($data['decision']) && isset($data['date_save']) && isset($data['remarque']) && 
    isset($data['id_users'])) {
    
    // Protection contre les failles XSS
    $nom = htmlspecialchars($data['nom']);
    $adresse = htmlspecialchars($data['adresse']);
    $phone = htmlspecialchars($data['phone']);
    $decision = htmlspecialchars($data['decision']);
    $date_save = htmlspecialchars($data['date_save']);
    $remarque = htmlspecialchars($data['remarque']);
    $id_users = htmlspecialchars($data['id_users']);

    // Vérifier si le contact existe déjà pour cet utilisateur
    $checkContact = $bdd->prepare('SELECT * FROM contact WHERE nom = ? AND id_users = ?');
    $checkContact->execute(array($nom, $id_users));

    if ($checkContact->rowCount() == 0) {
        // Insérer le nouveau contact
        $insertContact = $bdd->prepare('INSERT INTO contact (nom, adresse, phone, decision, date_save, remarque, id_users) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)');
        
        if ($insertContact->execute(array($nom, $adresse, $phone, $decision, $date_save, $remarque, $id_users))) {
            $response = [
                "success" => true,
                "message" => "Contact enregistré avec succès"
            ];
        } else {
            $response = [
                "success" => false,
                "error" => "Erreur lors de l'enregistrement du contact"
            ];
        }
    } else {
        // Le contact existe déjà
        $response = [
            "success" => false,
            "error" => "Ce contact existe déjà pour cet utilisateur"
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
?>