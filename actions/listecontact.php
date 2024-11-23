<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

$id_user = $json['id_user'];


try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contacts WHERE id_user = ? ORDER BY id_user DESC');
    $getAllUsers->execute(array($id_user));

    $totalAC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'AC' and id_user = 6");
    $totalAC->execute($id_user);
    
    $totalRC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'AC' and id_user = 6");
    $totalRC->execute($id_user);

    $totalRDV = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'AC' and id_user = 6");
    $totalRDV->execute($id_user);

    $ac = $totalAC->rowCount();

    $rc = $totalRC->rowCount();

    $rdv = $totalRDV->rowCount();


    if ($getAllUsers->rowCount() > 0) {
        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC);

        $result["success"] = true;
        $result["data"] = $users;

        $result["id"] = $user['id'];
        $result["nom"] = $user['nom'];
        $result["email"] = $user['email'];
        $result["prenom"] = $user['prenom'];
        $result["phone"] = $user['phone'];
        $result["equipe"] = $user['equipe'];

        $result["totalac"] = $ac;
        $result["totalrc"] = $rc;
        $result["totalrdv"] = $rdv;
        
        
    } else {
        $result["success"] = false;
        $result["error"] = "Aucun enregistrement trouvé";
    }
} catch (Exception $e) {
    // Gérer les exceptions et erreurs
    $result["success"] = false;
    $result["error"] = "Erreur : " . $e->getMessage();
}

// Retourner la réponse au format JSON
echo json_encode($result);
