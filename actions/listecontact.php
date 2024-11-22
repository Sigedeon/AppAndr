<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

$id = $json['id'];
$nom = $json['nom'];
$adresse = $json['adresse'];
$phone = $json['phone'];
$decision = $json['decision'];
$remarque = $json['remarque'];
$id_user = $json['id_user'];
$date_save = $json['date_save'];

try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contacts WHERE id_user = ?');
    $getAllUsers->execute($id_user);

    /* $totalAC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'AC', id_user = ? ");
    $totalAC->execute($id_user);

    $totalRC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'RC', id_user = ? ");
    $totalRC->execute($id_user);

    $totalRDV = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'RDV', id_user = ? ");
    $totalRDV->execute($id_user);

    $ac = $totalAC->rowCount();

    $rc = $totalRC->rowCount();

    $rdv = $totalRDV->rowCount(); */


    // Vérifier si des utilisateurs existent
    if ($getAllUsers->rowCount() > 0) {
        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les utilisateurs sous forme de tableau associatif

        // Construire la réponse
        $result["success"] = true;
        $result["data"] = $users;
        /* $result["totalac"] = $ac;
        $result["totalrc"] = $rc;
        $result["totalrdv"] = $rdv; */
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
echo json_encode($result, JSON_PRETTY_PRINT);
