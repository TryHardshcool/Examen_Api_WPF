<?php
/**
 * API REST - Gestion des vehicules
 * 
 * Ce fichier gere les operations CRUD pour les vehicules de la flotte
 * Methodes HTTP supportees : GET, POST, PUT, DELETE
 * 
 * @category API
 * @package  Vehicules_API
 * 
 * 
 */

// Chargement de la configuration de la base de donnees
require_once __DIR__ . '/Config/config.php';

// Verification que l'utilisateur est bien connecte (bloque avec 401 sinon)
require_once __DIR__ . '/Config/auth.php';

/**
 * Methode HTTP utilisee pour la requete
 * @var string $method
 */
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Identifiant du vehicule recupere depuis l'URL
 * @var int|null $id
 */
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

/**
 * Terme de recherche recupere depuis l'URL (recherche par marque, modele ou immatriculation)
 * @var string|null $recherche
 */
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : null;

/**
 * Traitement de la requete selon la methode HTTP
 */
switch ($method) {
    /**
     * METHODE GET - Recuperer les vehicules
     * 
     * Si un ID est fourni : retourne un seul vehicule
     * Si un terme de recherche est fourni (?recherche=...) : filtre par marque, modele ou immatriculation
     * Sinon : retourne tous les vehicules tries du plus recent au plus ancien
     */
    case 'GET':
        if ($id) {
            // Recuperation d'un vehicule specifique par son ID
            // Utilisation d'une requete preparee pour eviter les injections SQL
            $stmt = $pdo->prepare('SELECT * FROM vehicules WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } elseif ($recherche) {
            // Recherche sur la marque, le modele ou l'immatriculation
            $terme = '%' . $recherche . '%';
            $stmt = $pdo->prepare(
                'SELECT * FROM vehicules 
                 WHERE marque LIKE ? OR modele LIKE ? OR immatriculation LIKE ?
                 ORDER BY id DESC'
            );
            $stmt->execute([$terme, $terme, $terme]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            // Recuperation de tous les vehicules tries par ID descendant
            $stmt = $pdo->query('SELECT * FROM vehicules ORDER BY id DESC');
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    /**
     * METHODE POST - Creer un nouveau vehicule
     * 
     * Attend un JSON avec les champs : marque, modele, immatriculation, annee, couleur, statut
     * Retourne un message de succes et l'ID du vehicule cree
     */
    case 'POST':
        // Decodage des donnees JSON envoyees dans le corps de la requete
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Insertion du nouveau vehicule en base de donnees
        $stmt = $pdo->prepare(
            'INSERT INTO vehicules (marque, modele, immatriculation, annee, couleur, statut) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['marque'],
            $data['modele'],
            $data['immatriculation'],
            $data['annee'],
            $data['couleur'] ?? null,
            $data['statut'] ?? 'disponible'
        ]);
        
        // Retour du code 201 (Cree) avec l'ID du vehicule
        http_response_code(201);
        echo json_encode([
            "message" => "Vehicule cree avec succes",
            "id" => $pdo->lastInsertId()
        ]);
        break;

    /**
     * METHODE PUT - Modifier un vehicule existant
     * 
     * Attend un JSON avec les champs : marque, modele, immatriculation, annee, couleur, statut
     * Modifie le vehicule identifie par {id} dans l'URL
     * Retourne un message de succes
     */
    case 'PUT':
        // Decodage des donnees JSON envoyees dans le corps de la requete
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Mise a jour du vehicule
        $stmt = $pdo->prepare(
            'UPDATE vehicules SET marque = ?, modele = ?, immatriculation = ?, annee = ?, couleur = ?, statut = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['marque'],
            $data['modele'],
            $data['immatriculation'],
            $data['annee'],
            $data['couleur'] ?? null,
            $data['statut'] ?? 'disponible',
            $id
        ]);
        
        // Retour du message de succes
        echo json_encode(["message" => "Vehicule modifie avec succes"]);
        break;

    /**
     * METHODE DELETE - Supprimer un vehicule
     * 
     * Supprime le vehicule identifie par {id} dans l'URL
     * Retourne un message de succes
     */
    case 'DELETE':
        // Suppression du vehicule
        $stmt = $pdo->prepare('DELETE FROM vehicules WHERE id = ?');
        $stmt->execute([$id]);
        
        // Retour du message de succes
        echo json_encode(["message" => "Vehicule supprime avec succes"]);
        break;
        
    /**
     * METHODE NON SUPPORTEE
     * 
     * Gere les methodes HTTP non supportees (PATCH, OPTIONS, etc.)
     */
    default:
        http_response_code(405);
        echo json_encode([
            "erreur" => "Methode non autorisee",
            "methodes_autorisees" => ["GET", "POST", "PUT", "DELETE"]
        ]);
        break;
}