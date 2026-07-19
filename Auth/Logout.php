<?php
/**
 * API - Deconnexion utilisateur
 * 
 * Detruit la session en cours.
 * 
 * Methode HTTP supportee : POST
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

// Suppression de toutes les variables de session puis destruction de la session
$_SESSION = [];
session_destroy();

echo json_encode(["message" => "Deconnexion reussie"]);