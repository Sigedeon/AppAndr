<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

$id_user = htmlspecialchars($json['id_user']);

try{
    // Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
    $getUser = $bdd->prepare('SELECT * FROM users WHERE id =?');
    $getUser->execute(array($id_user));

    if ($getUser->rowCount() > 0) {
        $user = $getUser->fetch(PDO::FETCH_ASSOC);
    } else {
        $result["success"] = false;
        $result["error"] = "Utilisateur non trouvé";
        return;
    }

    // Calculer le nombre de contacts par décision
    $totalAC = $bdd->prepare("SELECT COUNT(*) as totaacl FROM contacts WHERE id_user =? and decision = 'AC'");
    $totalAC->execute([$id_user]);
    $totalAC = $totalAC->fetch()['totalac'];

    $totalRC = $bdd->prepare("SELECT COUNT(*) as totalrc FROM contacts WHERE id_user =? and decision = 'RC'");
    $totalRC->execute([$id_user]);
    $totalRC = $totalRC->fetch()['totalrc'];

    $totalRDV = $bdd->prepare("SELECT COUNT(*) as totalrcdv FROM contacts WHERE id_user =? and decision = 'RC'");
    $totalRDV->execute([$id_user]);
    $totalRDV = $totalRDV->fetch()['totalrdv'];

    $result["totalac"] = $totalAC;
    $result["totalrc"] = $totalRC;
    $result["totalrdv"] = $totalRDV;
    $result["success"] = true;


} catch (Exception $e) {
    // Gérer les exceptions et erreurs
    $result["success"] = false;
    $result["error"] = "Erreur : " . $e->getMessage();
    return;
}

// Retourner la réponse au format JSON
echo json_encode($result);
?>