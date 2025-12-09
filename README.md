# sae3-01

## Auteurs du projet :

Gerbaux Valentin - gerb0023
Gomes Dos Santos Timoti - gome0063
Guyot Antoine - guyo0082
Pierret Timothé - pier0271

## Description du projet : 

Resto'N est une application de gestion de restaurants est con¸cue pour aider les propriétaires de restaurants
a gérer efficacement leur établissement. Elle permet aux utilisateurs de créer des réservations,
de prendre des commandes, et de contrôler les stocks.

# .env.local
# Remplacez LOGIN, PASSWORD et NOM_BASE par vos infos
DATABASE_URL="mysql://LOGIN:PASSWORD@mysql:3306/NOM_BASE?serverVersion=10.2.25-MariaDB&charset=utf8mb4"
# Creer la Table sur le phpMyAdmin
# Créer les fichiers de migration SQL
php bin/console make:migration

# Envoyer les tables dans la base
php bin/console doctrine:migrations:migrate

# Remplir la base avec des fausses données (Admin, plats, stocks...)
php bin/console doctrine:fixtures:load


MAILER_DSN="smtp://ilanrigolio1@gmail.com:infayrhbqgrcaaxo@smtp.gmail.com:587?encryption=tls&auth_mode=login"
MAILER_FROM=ilanrigolio1@gmail.com

