<?php
// On démarre la session pour pouvoir lire les messages d'erreur/succès
// stockés par le controller
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Shopesa</title>
    <!-- On relie le fichier CSS qui se trouve dans le dossier public -->
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-box">

        <!-- Logo / Titre du site -->
        <h1 class="auth-logo">Shopesa</h1>
        <h2 class="auth-title">Connexion</h2>

        <?php
        // On vérifie s'il y a un message d'erreur en session
        // isset() vérifie si la variable existe
        if (isset($_SESSION['error'])) : ?>
            <!-- On affiche le message d'erreur -->
            <div class="alert alert-error">
                <?php
                // On affiche le message en sécurisant contre les failles XSS
                // htmlspecialchars() convertit les caractères spéciaux en entités HTML
                echo htmlspecialchars($_SESSION['error']);

                // On supprime le message après l'avoir affiché pour ne pas le revoir
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php
        // On vérifie s'il y a un message de succès (ex: après une inscription réussie)
        if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success">
                <?php
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <!-- action : envoie les données vers le controller -->
        <!-- method="POST" : les données sont envoyées de façon sécurisée (non visibles dans l'URL) -->
        <form action="../controllers/AuthController.php" method="POST">

            <!-- Champ caché pour indiquer au controller quelle action effectuer -->
            <input type="hidden" name="action" value="login">

            <!-- Champ Email -->
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="exemple@email.com"
                    required <!-- required = le navigateur bloque si le champ est vide -->
                >
            </div>

            <!-- Champ Mot de passe -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Votre mot de passe"
                    required
                >
                <!-- Lien pour afficher/masquer le mot de passe (géré en JS) -->
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>

            <!-- Bouton de soumission du formulaire -->
            <button type="submit" class="btn-auth">Se connecter</button>

        </form>

        <!-- Lien vers la page d'inscription -->
        <p class="auth-switch">
            Pas encore de compte ? 
            <a href="register.php">S'inscrire</a>
        </p>

    </div>

</div>

<script>
// Fonction pour afficher ou masquer le mot de passe
// fieldId : l'id du champ password à basculer
function togglePassword(fieldId) {
    // On récupère l'élément HTML par son id
    const field = document.getElementById(fieldId);

    // Si le type est "password" (masqué), on le passe à "text" (visible)
    // Sinon on le remet à "password"
    field.type = field.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>