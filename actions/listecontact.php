<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);
    // Préparer la requête pour rechercher l'utilisateur par email
    $getUser = $bdd->prepare('SELECT * FROM contact');
    $getUser->execute(array($email));

    if ($getUser->rowCount() > 0) {
        $user = $getUser->fetch();
        // Connexion réussie, envoyer l'ID utilisateur et d'autres informations utiles
        $result["success"] = true;
        $result["id"] = $user['id'];
        $result["nom"] = $user['nom'];
        $result["email"] = $user['email'];
        $result["prenom"] = $user['prenom'];
        $result["phone"] = $user['phone'];
        $result["equipe"] = $user['equipe'];

        $result["message"] = "Connexion réussie";
    } else {
        // Utilisateur non trouvé
        $result["success"] = false;
        $result["error"] = "Données non trouver";
    }

// Retourner la réponse en JSON
echo json_encode($result);
