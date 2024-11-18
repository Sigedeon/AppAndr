<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Inscription</h4>
                    </div>
                    <div class="card-body">
                        <form id="registrationForm" onsubmit="handleSubmit(event)">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                                <div class="error-feedback">Veuillez entrer votre nom</div>
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                                <div class="error-feedback">Veuillez entrer votre prénom</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="error-feedback">Veuillez entrer un email valide</div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       pattern="[0-9]{10}" required>
                                <div class="error-feedback">Veuillez entrer un numéro à 10 chiffres</div>
                            </div>

                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" 
                                       minlength="8" required>
                                <div class="error-feedback">Le mot de passe doit contenir au moins 8 caractères</div>
                            </div>

                            <div class="mb-3">
                                <label for="mdp_confirm" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="mdp_confirm" required>
                                <div class="error-feedback">Les mots de passe ne correspondent pas</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleSubmit(event) {
            event.preventDefault();
            
            // Validation des mots de passe
            const mdp = document.getElementById('mdp').value;
            const mdp_confirm = document.getElementById('mdp_confirm').value;
            
            if (mdp !== mdp_confirm) {
                document.getElementById('mdp_confirm')
                    .nextElementSibling.style.display = 'block';
                return;
            }

            // Création de l'objet de données
            const formData = {
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                mdp: mdp
            };

            // Envoi des données à l'API
            fetch('https://api.mascodeproduct.com/devApp/actions/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Inscription réussie !');
                    document.getElementById('registrationForm').reset();
                } else {
                    alert(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'inscription');
            });
        }

        // Validation en temps réel
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                if (!this.checkValidity()) {
                    this.nextElementSibling.style.display = 'block';
                } else {
                    this.nextElementSibling.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>