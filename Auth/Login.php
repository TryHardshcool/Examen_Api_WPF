<?php
/**
 * API - Connexion utilisateur
 * 
 * Verifie l'email et le mot de passe envoyes, puis ouvre une session
 * si les identifiants sont corrects.
 * 
 * Methode HTTP supportee : POST
 * Attend un JSON avec les champs : email, mot_de_passe
 * 
 * @category API
 * @package  Vehicules_API
 */

// __DIR__ pointe vers le dossier Auth/ ; on remonte d'un niveau pour trouver Config/
require_once __DIR__ . '/../Config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "erreur" => "Methode non autorisee",
        "methodes_autorisees" => ["POST"]
    ]);
    exit;
}

// Decodage des donnees JSON envoyees dans le corps de la requete
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['email']) || empty($data['mot_de_passe'])) {
    http_response_code(422);
    echo json_encode(["erreur" => "Email et mot de passe requis"]);
    exit;
}

// Recherche de l'utilisateur par email
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
$stmt->execute([$data['email']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Verification du mot de passe (hache avec password_hash)
if (!$utilisateur || !password_verify($data['mot_de_passe'], $utilisateur['mot_de_passe'])) {
    http_response_code(401);
    echo json_encode(["erreur" => "Email ou mot de passe incorrect"]);
    exit;
}

// Identifiants valides : ouverture de la session
$_SESSION['user_id'] = $utilisateur['id'];
$_SESSION['nom'] = $utilisateur['nom'];

echo json_encode([
    "message" => "Connexion reussie",
    "utilisateur" => [
        "id" => $utilisateur['id'],
        "nom" => $utilisateur['nom'],
        "email" => $utilisateur['email']
    ]
]);