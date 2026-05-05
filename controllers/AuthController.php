<?php
// On démarre la session PHP pour pouvoir stocker des données utilisateur
session_start();

// On inclut le modèle User pour pouvoir utiliser ses méthodes register() et login()
require_once __DIR__ . '/../models/User.php';

// On inclut la connexion à la base de données
require_once __DIR__ . '/../config/db.php';

// On crée une instance (objet) de la classe User en lui passant la connexion BD
// Cet objet nous permettra d'appeler $user->register() et $user->login()
$user = new User($conn);

// On vérifie que la requête vient bien d'un formulaire HTML (méthode POST)
// $_SERVER['REQUEST_METHOD'] contient le type de requête HTTP (GET, POST, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // On récupère l'action envoyée par le formulaire (soit "login" soit "register")
    // $_POST['action'] sera défini dans un champ caché <input type="hidden"> du formulaire
    $action = $_POST['action'] ?? ''; // ?? '' = si non défini, on met une chaîne vide

    // ============================================================
    // CAS 1 : INSCRIPTION
    // ============================================================
    if ($action === 'register') {

        // On récupère les données du formulaire d'inscription
        // trim() supprime les espaces inutiles au début et à la fin
        $nom      = trim($_POST['nom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm_password'] ?? '');

        // Vérification 1 : tous les champs doivent être remplis
        if (empty($nom) || empty($email) || empty($password) || empty($confirm)) {
            // On stocke le message d'erreur en session pour l'afficher dans la vue
            $_SESSION['error'] = 'Veuillez remplir tous les champs.';
            // On redirige l'utilisateur vers la page d'inscription
            header('Location: ../views/register.php');
            exit(); // On arrête le script après la redirection
        }

        // Vérification 2 : les deux mots de passe doivent correspondre
        if ($password !== $confirm) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: ../views/register.php');
            exit();
        }

        // Vérification 3 : l'email doit avoir un format valide (ex: test@gmail.com)
        // filter_var() avec FILTER_VALIDATE_EMAIL vérifie le format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Adresse email invalide.';
            header('Location: ../views/register.php');
            exit();
        }

        // Vérification 4 : le mot de passe doit avoir au moins 6 caractères
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères.';
            header('Location: ../views/register.php');
            exit();
        }

        // Toutes les vérifications sont passées, on appelle la méthode register() du modèle
        $result = $user->register($nom, $email, $password);

        if ($result['success']) {
            // Inscription réussie : on stocke un message de succès en session
            $_SESSION['success'] = $result['message'];
            // On redirige vers la page de connexion
            header('Location: ../views/login.php');
            exit();
        } else {
            // Inscription échouée (ex: email déjà utilisé)
            $_SESSION['error'] = $result['message'];
            header('Location: ../views/register.php');
            exit();
        }
    }

    // ============================================================
    // CAS 2 : CONNEXION
    // ============================================================
    if ($action === 'login') {

        // On récupère les données du formulaire de connexion
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Vérification : les deux champs doivent être remplis
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs.';
            header('Location: ../views/login.php');
            exit();
        }

        // On appelle la méthode login() du modèle avec l'email et le mot de passe
        $result = $user->login($email, $password);

        if ($result['success']) {
            // Connexion réussie : on stocke les infos de l'utilisateur en session
            // Ces données seront accessibles sur toutes les pages du site
            $_SESSION['user_id']   = $result['user']['id'];
            $_SESSION['user_nom']  = $result['user']['nom'];
            $_SESSION['user_role'] = $result['user']['role'];

            // On redirige selon le rôle de l'utilisateur
            if ($result['user']['role'] === 'admin') {
                // Si c'est un admin, on l'envoie vers le tableau de bord admin
                header('Location: ../admin/dashboard.php');
            } else {
                // Si c'est un client, on l'envoie vers la page d'accueil
                header('Location: ../views/home.php');
            }
            exit();
        } else {
            // Connexion échouée : mauvais email ou mot de passe
            $_SESSION['error'] = $result['message'];
            header('Location: ../views/login.php');
            exit();
        }
    }
}
?>