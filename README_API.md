# Gestion des Candidats REST API - Authentification

Ce projet est une API REST pour l'authentification des utilisateurs avec Laravel Sanctum.

## 1. Inscription (`POST /api/register`)

**URL:** http://127.0.0.1:8000/api/register

**Description :**  
Ce endpoint permet de créer un compte utilisateur et de générer un token d'authentification.

###  Exemple de requête (JSON) :
```json
{
  "name": "Mohsen Elrhazi",
  "email": "mohsen@example.com",
  "password": "123456",
  "password_confirmation": "123456"
}

```Réponse (201 Created) : 

{
  "status": "success",
  "message": "Utilisateur enregistré avec succès",
  "data": {
    "name": "Mohsen Elrhazi",
    "email": "mohsen@example.com",
    "token": "xxxxxxxxxxxxxxxxxx"
  }
}
