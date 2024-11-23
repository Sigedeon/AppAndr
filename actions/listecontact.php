<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

$id_user = $json['id_user'];


try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contacts WHERE id_user = ? ORDER BY id_user DESC');
    $getAllUsers->execute(array($id_user));

    $totalAc = $bdd->prepare("SELECT * FROM contacts WHERE id_user = ? and decision = 'AC' ");
    $totalAc->execute(array($id_user));
    $ac = $totalAC->rowCount();

    $totalRc = $bdd->prepare("SELECT * FROM contacts WHERE id_user = ? and decision = 'AC' ");
    $totalRc->execute(array($id_user));
    $rc = $totalRC->rowCount();

    $totalRdv = $bdd->prepare("SELECT * FROM contacts WHERE id_user = ? and decision = 'AC' ");
    $totalRdv->execute(array($id_user));
    $rdv = $totalRdv->rowCount();


    if ($getAllUsers->rowCount() > 0) {
        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC);

        $result["success"] = true;
        $result["data"] = $users;

        $result["ac"] = $ac;
        $result["rc"] = $rc;
        $result["rdv"] = $rdv;
        
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
?>
