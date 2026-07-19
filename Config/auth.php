<?php
/**
 * Middleware d'authentification
 * 
 * A inclure (require_once) dans tout fichier API qui doit etre protege.
 * Verifie qu'une session utilisateur valide existe, sinon bloque la
 * requete avec un code 401.
 * 
 * @category Config
 * @package  Vehicules_API
 */

function verifierAuth()
{
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["erreur" => "Non authentifie. Veuillez vous connecter."]);
        exit;
    }
}

verifierAuth();