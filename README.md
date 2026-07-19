# API Gestion de Vehicules
## Description
Cette API constitue la couche métier de l'application.
Elle reçoit les requêtes du client WPF, applique les règles métiers puis communique avec la base de données.
## Technologies
- PHP
- REST API
- PDO
## Responsabilites
- Authentification
- Validation
- Gestion métier
- Communication avec PostgreSQL
- Retour des réponses JSON
## Endpoints
POST /login
GET /vehicules
POST /vehicules
PUT /vehicules/{id}
DELETE /vehicules/{id}
## Reponses
Toutes les réponses sont renvoyées en JSON.