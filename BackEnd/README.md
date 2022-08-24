TP1_Laravel Par Raphael Bergeron-Larose 2192265

Instructions pour exécuter le programme:
    
    composer global require "laravel/installer",
    composer update --no-scripts,
    composer require laravel/sanctum,
    php artisan vendor:publish -- provider="Laravel\Sanctum\SanctumServiceProvider"

    "Faire sur de creer une base de données nommee TP1_Laravel_Raphael et d'aller modifier les informations de connexion dans le fichier .env(Ligne 14 et 15)"

    php artisan migrate,
    php artisan db:seed,
    php artisan serve


Voici les Routes

|ROUTE | ACTION | DESCRIPTION |
|------|--------|-------------|
| films                    | GET        | Consultation des films (sans critiques et sans acteurs)   |
| films/actors/{id}        | GET        | Consultation de tous les acteurs d’un certain film        |
| films/critics/{id}       | GET        | Consultation d’un certain film avec ces critiques         |
| films/search             | GET        | Recherche de films                                        |
| films                    | POST       | Ajout d’un film (seulement si admin)                      |
| films/{id}               | DELETE     | Suppression d’un film (seulement si admin)                |
| users/register           | POST       | Ajout d’un nouveau user                                   |
| users/login              | POST       | Connexion d’un user                                       |
| users/{id}               | GET        | Consultation des informations d’un certain user           |
| users/logout             | POST       | Déconnexion d’un user                                     |
| users                    | PUT        | Modification d’un user existant                           |
| users/password           | PUT        | Modification du Mot de Passe                              |
| critics                  | POST       | Ajout d’une critique (seulement si membre connecté)       |

Exemple de requete json avec Postman:

films POST:
        
    {            
        "title": "Raph Contre Attack",
        "release_year": "2006",
        "length": 48,
        "description": "A Astounding Epistle of a Database Administrator And a Explorer who must Find a Car in Ancient China",
        "rating": "G",
        "language_id": 1,
        "special_features": "Trailers,Deleted Scenes",
        "image": "null"
    }

users/register POST:

    {
        "login" : "admin",
        "password" : "admin2002",
        "email" : "admin@gmail.com",
        "last_name" : "admin",
        "first_name" : "admin",
        "role_id" : "1"
    }

users/login POST:

    {
        "login" : "admin",
        "password":"admin2002"   
    }

films/search GET:

    {
    "keywords":"A",
    "max_length" : "150",
    "rating" : "G"
    }
