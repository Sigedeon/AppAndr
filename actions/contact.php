<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données requises sont présentes
if (isset($data['nom']) && isset($data['adresse']) && isset($data['phone']) && 
    isset($data['decision']) && isset($data['date_save']) && isset($data['remarque']) && 
    isset($data['id_users'])) {
    
    // Protection contre les failles XSS
    $nom = htmlspecialchars($data['nom']);
    $adresse = htmlspecialchars($data['adresse']);
    $phone = htmlspecialchars($data['phone']);
    $decision = htmlspecialchars($data['decision']);
    $date_save = htmlspecialchars($data['date_save']);
    $remarque = htmlspecialchars($data['remarque']);
    $id_users = htmlspecialchars($data['id_users']);

    $updateContact = $bdd->prepare('
        UPDATE contact 
        SET nom = ?, 
            adresse = ?, 
            phone = ?, 
            decision = ?, 
            remarque = ?,
            date_modification = NOW()
        WHERE id = ?
    ');
    
    if ($updateContact->execute([$nom, $adresse, $phone, $decision, $remarque, $id])) {
        if ($updateContact->rowCount() > 0) {
            // Succès de la mise à jour
            $response = [
                "success" => true,
                "message" => "Contact mis à jour avec succès"
            ];
            http_response_code(200);
        } else {
            // Aucune modification effectuée
            throw new Exception("Aucun contact trouvé avec cet ID");
        }
    } else {
        // Erreur lors de la mise à jour
        throw new Exception("Erreur lors de la mise à jour du contact");
    }
}
// Envoyer la réponse JSON
echo json_encode($response);
?>