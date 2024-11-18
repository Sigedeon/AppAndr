<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

// Vérifier si les champs nécessaires sont présents
if (isset($json['email']) && isset($json['mdp'])) {
    $email = htmlspecialchars($json['email']);
    $mdp = htmlspecialchars($json['mdp']);

    // Préparer la requête pour rechercher l'utilisateur par email
    $getUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');
    $getUser->execute(array($email));

    if ($getUser->rowCount() > 0) {
        $user = $getUser->fetch();

        // Vérifier le mot de passe avec password_verify() si le mot de passe est haché
        if (password_verify($mdp, $user['mdp'])) {
            // Connexion réussie, envoyer l'ID utilisateur et d'autres informations utiles
            $result["success"] = true;
            $result["user_id"] = $user['id'];  // Ajout de l'ID utilisateur dans la réponse
            $result["message"] = "Connexion réussie";
        } else {
            // Mot de passe incorrect
            $result["success"] = false;
            $result["error"] = "Mot de passe incorrect";
        }
    } else {
        // Utilisateur non trouvé
        $result["success"] = false;
        $result["error"] = "Utilisateur non trouvé";
    }
} else {
    // Données manquantes
    $result["success"] = false;
    $result["error"] = "Données manquantes";
}

// Retourner la réponse en JSON
echo json_encode($result);
?>

