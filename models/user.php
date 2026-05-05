<?php
// On inclut le fichier de connexion à la base de données
// __DIR__ retourne le dossier actuel, on remonte d'un niveau pour atteindre config/db.php
require_once __DIR__ . '/../config/db.php';

// Définition de la classe User qui représente un utilisateur dans notre application
class User {

    // Propriété privée pour stocker la connexion à la base de données
    // "private" = accessible uniquement à l'intérieur de cette classe
    private $conn;

    // Constructeur : cette fonction est appelée automatiquement quand on crée un objet User
    // Elle reçoit la connexion BD en paramètre et la stocke dans $this->conn
    public function __construct($conn) {
        $this->conn = $conn; // On sauvegarde la connexion pour l'utiliser dans les autres méthodes
    }

    // ============================================================
    // MÉTHODE REGISTER : Permet d'inscrire un nouvel utilisateur
    // ============================================================
    public function register($nom, $email, $password) {

        // On sécurise l'email avant de l'utiliser dans la requête SQL
        // mysqli_real_escape_string() empêche les injections SQL (attaques malveillantes)
        $emailSafe = mysqli_real_escape_string($this->conn, $email);

        // On vérifie si un utilisateur avec cet email existe déjà dans la BD
        $check = mysqli_query($this->conn, "SELECT id FROM users WHERE email = '$emailSafe'");

        // mysqli_num_rows() compte le nombre de lignes retournées
        // Si > 0, l'email est déjà pris
        if (mysqli_num_rows($check) > 0) {
            // On retourne un tableau avec success = false et un message d'erreur
            return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
        }

        // PASSWORD_BCRYPT est un algorithme de hachage sécurisé
        // On ne stocke JAMAIS un mot de passe en clair dans la BD
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // On sécurise le nom et l'email contre les injections SQL
        $nom = mysqli_real_escape_string($this->conn, $nom);
        $email = mysqli_real_escape_string($this->conn, $email);

        // On prépare la requête SQL d'insertion
        // On n'insère pas "role" car il a une valeur par défaut 'client' dans la BD
        // On n'insère pas "created_at" car il se remplit automatiquement avec current_timestamp()
        $sql = "INSERT INTO users (nom, email, password) VALUES ('$nom', '$email', '$hashedPassword')";

        // On exécute la requête
        if (mysqli_query($this->conn, $sql)) {
            // Si la requête réussit, on retourne success = true
            return ['success' => true, 'message' => 'Inscription réussie.'];
        } else {
            // Si la requête échoue (ex: problème BD), on retourne une erreur
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
        }
    }

    // ============================================================
    // MÉTHODE LOGIN : Permet de connecter un utilisateur existant
    // ============================================================
    public function login($email, $password) {

        // On sécurise l'email contre les injections SQL
        $email = mysqli_real_escape_string($this->conn, $email);

        // On cherche l'utilisateur dans la BD par son email
        $result = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$email'");

        // On vérifie qu'on a trouvé exactement 1 utilisateur avec cet email
        if (mysqli_num_rows($result) === 1) {

            // mysqli_fetch_assoc() convertit le résultat SQL en tableau associatif PHP
            // Ex: $user['nom'], $user['email'], $user['role'], etc.
            $user = mysqli_fetch_assoc($result);

            // password_verify() compare le mot de passe saisi avec le hash stocké en BD
            // Elle retourne true si les deux correspondent
            if (password_verify($password, $user['password'])) {
                // Connexion réussie : on retourne les infos de l'utilisateur
                return ['success' => true, 'user' => $user];
            }
        }

        // Si l'email n'existe pas OU si le mot de passe est incorrect
        // On retourne le même message pour ne pas donner d'indices à un attaquant
        return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
    }
}
?>