<?php
//echo 'salut';
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php'); // Assurez-vous que config.php contient les informations de connexion MySQL

// Récupérer les données JSON envoyées par l'application
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['nom']) && isset($data['prenom']) && isset($data['email']) && isset($data['phone']) && isset($data['mdp'])) {
    
    // Protection contre les failles XSS
    $nom = htmlspecialchars($data['nom']);
    $prenom = htmlspecialchars($data['prenom']);
    $email = htmlspecialchars($data['email']);
    $phone = htmlspecialchars($data['phone']);
    
    // Hash du mot de passe pour une meilleure sécurité
    $mdp = password_hash($data['mdp'], PASSWORD_BCRYPT);

    // Vérifier si l'utilisateur existe déjà avec le même email
    $checkUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');
    $checkUser->execute(array($email));

    if ($checkUser->rowCount() == 0) {
        // Insérer le nouvel utilisateur dans la base de données
        $insertUser = $bdd->prepare('INSERT INTO users (nom, prenom, email, phone, mdp) VALUES (?, ?, ?, ?, ?)');
        
        if ($insertUser->execute(array($nom, $prenom, $email, $phone, $mdp))) {
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
        "error" => "Données manquantes"
    ];
}

// Envoyer la réponse sous format JSON
echo json_encode($response);

?>