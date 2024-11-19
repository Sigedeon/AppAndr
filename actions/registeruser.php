<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php'); // Assurez-vous que config.php contient les informations de connexion MySQL

// Récupérer les données JSON envoyées par l'application
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['nom']) && isset($data['prenom']) && isset($data['email']) && isset($data['phone']) && isset($data['equipe']) && isset($data['mdp'])) {
    
    // Protection contre les failles XSS
    $nom = htmlspecialchars($data['nom']);
    $prenom = htmlspecialchars($data['prenom']);
    $email = htmlspecialchars($data['email']);
    $phone = htmlspecialchars($data['phone']);
    $equipe = htmlspecialchars($data['equipe']);
    $mdp = htmlspecialchars($data['mdp']);
    

    // Vérifier si l'utilisateur existe déjà avec le même nom
    $checkUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');
    $checkUser->execute(array($email));

    if ($checkUser->rowCount() == 0) {
        // Insérer le nouvel utilisateur dans la base de données
        $insertUser = $bdd->prepare('INSERT INTO contacts (nom, prenom, email, phone, equipe, mdp) VALUES (?, ?, ?, ?, ?, ?)');
        
        if ($insertUser->execute(array($nom, $prenom, $email, $phone, $equipe, $mdp))) {
            $response = [
                "success" => true,
                "message" => "Inscription réussie"
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
            "error" => "Utilisateur existe déjà"
        ];
    }
} else {
    // Paramètres manquants
    $response = [
        "success" => false,
        "error" => "Donnees manquantes"
    ];
}

// Envoyer la réponse sous format JSON
echo json_encode($response);
?>