<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

// Vérifier si les champs nécessaires sont présents
if (isset($json['email']) && isset($json['mdp'])) {
    $email = htmlspecialchars($json['email']);
    $mdp = htmlspecialchars($json['mdp']);


    $totalAC = $bdd->prepare('SELECT * FROM contacts WHERE decision = AC');
    $totalAC->execute();
    
    $totalRC = $bdd->prepare('SELECT * FROM contacts WHERE decision = RC');
    $totalRC->execute();

    $totalRDV = $bdd->prepare('SELECT * FROM contacts WHERE decision = RDV');
    $totalRDV->execute();

    $ac = $totalAC->rowCount();

    $rc = $totalRC->rowCount();

    $rdv = $totalRDV->rowCount();

    // Préparer la requête pour rechercher l'utilisateur par email
    $getUser = $bdd->prepare('SELECT * FROM user WHERE email = ?');
    $getUser->execute(array($email));
    

    if ($getUser->rowCount() > 0) {
        $user = $getUser->fetch();

        // Vérifier le mot de passe avec password_verify() si le mot de passe est haché
        if ($mdp == $user['mdp']) {
            // Connexion réussie, envoyer l'ID utilisateur et d'autres informations utiles
            $result["success"] = true;
            $result["id"] = $user['id'];
            $result["nom"] = $user['nom'];
            $result["email"] = $user['email'];
            $result["prenom"] = $user['prenom'];
            $result["phone"] = $user['phone'];
            $result["equipe"] = $user['equipe'];

            $result["totalac"] = $ac;
            $result["totalrc"] = $rc;
            $result["totalrdv"] = $rdv;


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

