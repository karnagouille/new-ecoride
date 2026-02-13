# EcoRide — Plateforme de covoiturage écologique

EcoRide est une application web développée avec Symfony permettant aux utilisateurs de proposer, rechercher et réserver des trajets de covoiturage en mettant en avant l’aspect écologique des déplacements.

Projet réalisé dans le cadre de la formation Studi — Année 2026.

## Prérequis

- PHP 8.2+
- Composer
- MySQL
- Symfony CLI
- Git
## Installation du projet

### 1. Cloner le dépôt

git clone https://github.com/TONLIEN/ecoRide.git
cd ecoride

### 2. Installer les dépendances

composer install

### 3. Configurer le fichier .env

Modifier les informations de connexion à la base de données :

DATABASE_URL="mysql://root:@127.0.0.1:3306/ecoride"

### 4. Créer la base de données

php bin/console doctrine:database:create

### 5. Importer la base de données

Importer le fichier SQL fourni dans le projet :

ecoride.sql

### 6. Lancer les migrations (si nécessaire)

php bin/console doctrine:migrations:migrate

### 7. Lancer le serveur

symfony server:start

## Organisation Git

- main : version stable en production
- develop : version de développement

- ## Déploiement

L’application est déployée sur Heroku :

Lien : https://tonlien.herokuapp.com

## Technologies utilisées

- Symfony
- PHP
- MySQL
- Twig
- Bootstrap
- JavaScript
- Doctrine ORM



