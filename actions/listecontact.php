<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

$id_user =  htmlspecialchars($json['id_user']);


try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contacts WHERE id_user = ? ORDER BY id_user DESC');
    $getAllUsers->execute(array($id_user));


    if ($getAllUsers->rowCount() > 0) {
        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC);

        $result["success"] = true;
        $result["data"] = $users;
        
    } else {
        $result["success"] = false;
        $result["error"] = "Aucun enregistrement trouve";
    }
} catch (Exception $e) {
    // Gérer les exceptions et erreurs
    $result["success"] = false;
    $result["error"] = "Erreur : " . $e->getMessage();
}

// Retourner la réponse au format JSON
echo json_encode($result);
?>
