<?php
header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');

try {
    // Préparer et exécuter la requête pour récupérer tous les utilisateurs
    $getAllUsers = $bdd->prepare('SELECT * FROM contact');
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


try {
                        boolean success = response.getBoolean("success");
                        if (success) {
                            // Extraire les données utilisateur
                            JSONObject user = response.getJSONObject("data");
                            String noms = user.getString("nom");
                            String emailFetched = user.getString("email");
                            String prenom = user.getString("prenom");
                            String phone = user.getString("phone");
                            String equipe = user.getString("equipe");

                            Toast.makeText(getApplicationContext(), "Bienvenue, " + noms, Toast.LENGTH_SHORT).show();

                            // Naviguer vers l'activité suivante
                            Intent intent = new Intent(getApplicationContext(), ProfilActivity.class);
                            intent.putExtra("nom", noms);
                            intent.putExtra("email", emailFetched);
                            intent.putExtra("prenom", prenom);
                            intent.putExtra("phone", phone);
                            intent.putExtra("equipe", equipe);
                            startActivity(intent);
                            finish();
                        } else {
                            String errorMessage = response.getString("error");
                            Toast.makeText(getApplicationContext(), errorMessage, Toast.LENGTH_SHORT).show();
                        }
                    }

