<?php
/**
 * API REST - Service d'accès à la base de données
 * 
 * Ce script gère les requêtes HTTP pour l'API de la gestion de vehicule.
 * Il permet de lire, créer, modifier et supprimer des données via des
 * appels HTTP standards (GET, POST, PUT, DELETE).
 * 
 * @author 
 * @version 1.0.0
 * @copyright 2026 - Examen  C#
 */

// demarrage de la session

session_start();

// ============================================================
// 1. CONFIGURATION DES EN-TÊTES HTTP (CORS)
// ============================================================
// Ces en-têtes permettent d'autoriser les requêtes depuis n'importe
// quelle origine (sécurité CORS) et définissent le format de réponse.

/**
 * Autorise l'accès depuis n'importe quelle origine (*)
 * En production, remplacez "*" par le domaine de votre frontend
 * Exemple: "Access-Control-Allow-Origin: https://mon-site.com"
 */
header("Access-Control-Allow-Origin: *");

/**
 * Définit le format de la réponse en JSON
 * Assure que les données sont correctement interprétées par le client
 */
header("Content-Type: application/json; charset=UTF-8");

/**
 * Méthodes HTTP autorisées
 * - GET : Récupérer des données
 * - POST : Créer une nouvelle ressource
 * - PUT : Modifier une ressource existante
 * - DELETE : Supprimer une ressource
 */
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

/**
 * En-têtes HTTP autorisés lors des requêtes
 * Content-Type : Permet d'envoyer des données au format JSON
 */
header("Access-Control-Allow-Headers: Content-Type");


// ============================================================
// 2. CONFIGURATION DE LA BASE DE DONNÉES
// ============================================================
// Paramètres de connexion à la base de données MySQL.
// Ces valeurs sont pour l'environnement de développement.

/**
 * @var string $host Adresse du serveur de base de données
 * En local : localhost | En production : adresse IP ou nom de domaine
 */
$host = "localhost";

/**
 * @var string $dbname Nom de la base de données
 * À modifier selon votre configuration
 */
$dbname = "gestion_vehicules";

/**
 * @var string $user Nom d'utilisateur pour la connexion
 * En local : root | En production : nom d'utilisateur spécifique
 */
$user = "root";

/**
 * @var string $password Mot de passe pour la connexion
 * En local : vide | En production : mot de passe sécurisé
 */
$password = "";

try {
 $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
 http_response_code(500);
 echo json_encode(["error" => $e->getMessage()]);
 exit();
}
