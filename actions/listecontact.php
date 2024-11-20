<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contacts');
    $getAllUsers->execute();

    // Vérifier si des utilisateurs existent
    if ($getAllUsers->rowCount() > 0) {
        $users = $getAllUsers->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les utilisateurs sous forme de tableau associatif
        
        // Construire la réponse
        $result["success"] = true;
        $result["data"] = $users; // Ajouter les utilisateurs à la réponse
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
?>
