<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

// Initialiser une réponse par défaut
$response = [
    "success" => false,
    "error" => "erreur lors de update"
];


try {
    // Récupérer les données
    $id = htmlspecialchars($data['id']);
    $nom = trim($data['nom']);
    $adresse = trim($data['adresse']);
    $phone = trim($data['phone']);
    $decision = trim($data['decision']);
    $remarque = trim($data['remarque']);

    // Préparer la requête
    $sql = $bdd->prepare("UPDATE contacts SET nom = ?, adresse = ?, phone = ?, decision = ?, remarque = ? WHERE id = ?");

    // Exécuter la requête avec des valeurs liées
    $result = $sql->execute([$nom, $adresse, $phone, $decision, $remarque, $id]);

    // Vérifier si la mise à jour a réussi
    if ($result) {
        // Réponse en cas de succès
        echo json_encode([
            "success" => "success",
            "message" => "Mise à jour réussie"
        ]);
    } else {
        throw new Exception("Erreur lors de la mise à jour");
    }
} catch (Exception $e) {
    // Gestion des erreurs
    echo json_encode([
        "success" => "error",
        "message" => $e->getMessage()
    ]);
}


// Envoyer la réponse sous format JSON
echo json_encode($response);
