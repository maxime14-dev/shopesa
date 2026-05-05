<?php
// On démarre la session pour lire les messages d'erreur/succès du controller
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Shopesa</title>
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-box">

        <h1 class="auth-logo">Shopesa</h1>
        <h2 class="auth-title">Créer un compte</h2>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-error">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/AuthController.php" method="POST">

            <!-- Champ caché pour indiquer qu'on veut faire une inscription -->
            <input type="hidden" name="action" value="register">

            <!-- Champ Nom -->
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    placeholder="Jean Dupont"
                    required
                >
            </div>

            <!-- Champ Email -->
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="exemple@email.com"
                    required
                >
            </div>

            <!-- Champ Mot de passe -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Minimum 6 caractères"
                    required
                >
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>

            <!-- Champ Confirmation du mot de passe -->
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Répétez votre mot de passe"
                    required
                >
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>

            <!-- Indicateur de force du mot de passe (géré en JS) -->
            <div class="password-strength">
                <div class="strength-bar" id="strength-bar"></div>
                <span id="strength-text"></span>
            </div>

            <button type="submit" class="btn-auth">S'inscrire</button>

        </form>

        <p class="auth-switch">
            Déjà un compte ? 
            <a href="login.php">Se connecter</a>
        </p>

    </div>

</div>

<script>
// Fonction pour afficher/masquer le mot de passe
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}

// Fonction pour évaluer la force du mot de passe en temps réel
// On écoute l'événement "input" = chaque fois que l'utilisateur tape
document.getElementById('password').addEventListener('input', function () {
    
    // On récupère la valeur actuelle du champ
    const val = this.value;
    
    // On récupère les éléments HTML de l'indicateur
    const bar  = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');

    // On calcule un score selon des critères de sécurité
    let score = 0;
    if (val.length >= 6)               score++; // Au moins 6 caractères
    if (val.match(/[A-Z]/))            score++; // Au moins une majuscule
    if (val.match(/[0-9]/))            score++; // Au moins un chiffre
    if (val.match(/[^A-Za-z0-9]/))     score++; // Au moins un caractère spécial

    // On adapte la couleur et le texte selon le score obtenu
    if (score <= 1) {
        bar.style.width           = '25%';
        bar.style.backgroundColor = '#e74c3c'; // Rouge = faible
        text.textContent          = 'Faible';
    } else if (score === 2) {
        bar.style.width           = '50%';
        bar.style.backgroundColor = '#f39c12'; // Orange = moyen
        text.textContent          = 'Moyen';
    } else if (score === 3) {
        bar.style.width           = '75%';
        bar.style.backgroundColor = '#3498db'; // Bleu = bon
        text.textContent          = 'Bon';
    } else {
        bar.style.width           = '100%';
        bar.style.backgroundColor = '#2ecc71'; // Vert = fort
        text.textContent          = 'Fort';
    }
});
</script>

</body>
</html>