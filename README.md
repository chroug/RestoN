Bienvenue sur le dépôt de l'application de gestion de restaurants. Cette application permet la gestion complète d'une plateforme de restauration : de la commande client à la gestion des stocks par les restaurateurs, en passant par la notation et les avis.

---

## 📑 Table des Matières

1. [Prérequis](#-prérequis)
2. [Installation](#-installation)
3. [Base de Données & Fixtures](#-base-de-données--fixtures)
4. [Lancer le Serveur](#-lancer-le-serveur)
5. [Tests Automatisés (QA)](#-tests-automatisés-qa)
6. [Fonctionnalités](#-fonctionnalités)
7. [Dépannage](#-dépannage)

---

## 🛠 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

* **PHP 8.2** ou supérieur.
* **Composer** (Gestionnaire de dépendances).
* **Symfony CLI** (Recommandé pour le serveur local).
* **SQLite** (Pour l'environnement de test) ou MySQL/MariaDB (Pour le développement).

---

## 🚀 Installation

1.  **Cloner le projet**
    ```bash
    git clone https://github.com/votre-username/sae3-01.git
    cd sae3-01
    ```

2.  **Installer les dépendances PHP**
    ```bash
    composer install
    ```

3.  **Configuration de l'environnement**
    * Copiez le fichier `.env` en `.env.local` :
      ```bash
      cp .env .env.local
      ```
    * Modifiez `DATABASE_URL` dans `.env.local` pour correspondre à votre base de données locale.
    * Pour les e-mails, configurez `MAILER_DSN` (ou mettez `null://null` pour désactiver l'envoi en dev).

---

## 🗄 Base de Données & Fixtures

Pour initialiser la base de données de développement avec des fausses données (Patrons, Clients, Restaurants, Plats) :

```bash
# Création de la base et du schéma
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# (Optionnel) Charger les données de test (Fixtures)
php bin/console doctrine:fixtures:load --no-interaction
```

---

## 🌐 Lancer le Serveur

Utilisez le binaire Symfony pour lancer un serveur web local optimisé :

```bash
symfony server:start
```
L'application sera accessible sur `http://127.0.0.1:8000`.

---

## 🧪 Tests Automatisés (QA)

Nous utilisons **Codeception** pour les tests fonctionnels (E2E).
Des scripts Composer personnalisés ont été créés pour faciliter l'exécution des tests.

### ⚙️ Configuration des tests (`.env.test`)
Assurez-vous que le fichier `.env.test` existe et contient :
```dotenv
KERNEL_CLASS='App\Kernel'
APP_SECRET='$ecretf0rt3st'
SYMFONY_DEPRECATIONS_HELPER=999999
PANTHER_APP_ENV=panther
PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots
# Utilisation de SQLite pour la rapidité des tests
DATABASE_URL="sqlite:///%kernel.project_dir%/var/test.db"
# Désactivation de l'envoi réel d'emails
MAILER_DSN=null://null
# URL par défaut pour les liens absolus
DEFAULT_URI="http://localhost"
```

### 🎯 Commandes de Test (Scripts Composer)

Nous avons simplifié le processus en deux commandes principales :

#### 1. Préparer l'environnement de test
Cette commande vide le cache, supprime l'ancienne base de test, en recrée une neuve et génère le schéma. **À lancer si vous modifiez vos entités.**

```bash
composer test:prepare
```

#### 2. Lancer les tests
Cette commande exécute la suite complète de tests fonctionnels Codeception.

```bash
composer test:run
```

### 📋 Couverture des Tests

Les tests actuels (`tests/Controller/`) couvrent les scénarios critiques :

| Test Suite | Description | Fichier                |
| :--- | :--- |:-----------------------|
| **Registration** | Inscription d'un nouveau client (vérification BDD). | `RegistrationCest.php` |
| **Login** | Connexion sécurisée (hachage mot de passe). | `LoginCest.php`        |
| **Cart** | Tunnel de commande complet (Ajout panier -> Validation). | `CartCest.php`         |

---

## ✨ Fonctionnalités

* **Authentification & Rôles** : Système sécurisé avec rôles `ROLE_CLIENT`, `ROLE_PATRON`, `ROLE_GERANT`, `ROLE_SERVEUR`.
* **Gestion Restaurant** : Les patrons peuvent gérer leurs plats, stocks et menus.
* **Panier & Commandes** : Tunnel d'achat complet avec validation.
* **Système d'Avis** : Les clients peuvent noter les restaurants après commande.
* **Back-Office** : Interface pour les gérants et patrons.

---

## 🔑 Comptes de Test (Générés par les Fixtures)

Tous les comptes utilisent le même mot de passe : "password"

| Rôle           | Email               | Mot de passe |
|----------------|---------------------|--------------|
| 🛠️ Admin      | admin@test.com      | password     |
| 👔 Patron     | patron@test.com     | password     |
| 🍽️ Serveur    | serveur@test.com    | password     |
| 👤 Client     | client@test.com     | password     |

## 🚀 Accès à l'application

Le site est déployé et accessible via le réseau de l'IUT à l'adresse suivante :

http://10.31.33.78/
