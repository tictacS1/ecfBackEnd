# Symfony

Ce repo contient des données pour un projet de Back-end a destination d'un Libraire pour un site d'emprunts de Livres.

 Il s'agit de la 1ère Partie de l'ECF de Malick GONDY pour la promo 11.

## Prérequis

* Linux, MacOS ou Windows.
* Bash
* PHP 8
* Composer
* Symfony-cli
* MariaDB

## Installation

#### Dépendences de Composer :

`composer require`

`composer install`

#### Vérifier si les prérequis sont la :

```
symfony check:requirements
```

#### Activation du protocole HTTPS (à faire une seule fois par poste) :

```
sudo apt install libnss3-tools
```

```
symfony server:ca:install
```

#### Configuration du projet :

**Vérification de la version de mariadb :**

```
mariadb --version
```

```
mariadb  Ver 15.1 Distrib 10.8.3-MariaDB, for Linux (x86_64) using readline 5.1
```

**Création et Configuration du fichier .env.local :**

```
cd ecfBackEnd
touch .env.local
code .env.local
```

**Configuration de l'accès à la BDD dans le fichier `.env.local` :**

*Pensez bien a remplacer les informations entre guillemets !*

```
APP_ENV=dev
```

```
DATABASE_URL="mysql://"Nom d'utilisateur":"Mot de passe"@127.0.0.1:3306/"Nom de BDD"?serverVersion=mariadb-"Version de mariadb"&charset=utf8mb4"
```

**Cela devrait donner :**

```
DATABASE_URL="mysql://src_symfony_5_4:123@127.0.0.1:3306/src_symfony_5_4?serverVersion=mariadb-10.8.3&charset=utf8mb4"
```

**Créez une base de données puis vérifiez son accés:**

```
php bin/console do:da:cr
```

```
php bin/console do:sc:va
```

**Installation des packages :**

Installation de `doctrine/fixtures-bundle` :

```
composer require orm-fixtures --dev
```

Installation de `fakerphp/faker` :

```
composer require fakerphp/faker --dev
```

Installation de `javiereguiluz/easyslugger` :

```
composer require javiereguiluz/easyslugger --dev
```

Installation de `knplabs/knp-paginator-bundle` :

```
composer require knplabs/knp-paginator-bundle
```

---

(Optionel : Changer la configuration de la langue dans le fichier `config/packages/translation.yaml`) :

```
  framework:
-     default_locale: en
+     default_locale: fr
      translator:
          default_path: '%kernel.project_dir%/translations'
          fallbacks:
              - en
```

## Migration et fixtures

Lancez  la commande ci-dessous afin de charger le schéma de base de données et les Fixtures.

*Pensez à vérifier que vous êtes a la racine de votre dossier de projet symfony !*

Puis lancez `bin/dofilo.sh` dans la console

` bin/dofilo.sh `

## Vérification des Fixtures

Pour vérifier les données rendez vous sur PhpMyAdmin et entrez le nom d'utilisateur et mot de passe renseigné dans `.env.local`

*PS: Si le schema de BDD est d'une trop mauvaise qualité je peut le refaire dans une meilleure résolution*

## Setup du serveur web

**Lancez le serveur web :**

```
symfony serve
```

Dans la console la sortie de cette commande vous renvoie une adresse pour votre serveur de test.

Exemples :

   -https://127.0.0.1:8000/

   -https://localhost:8000/

L'URL peut être différent selon les informations renseignées dans `.env.local `

Votre URL est affichée dans la console apres exécution de la commande `symfony serve`

## Utilisation des requêtes

Pour vérifier les requêtes naviguez dans ces 4 URLs :

    -`https://127.0.0.1:8000/test/user`

    -`https://127.0.0.1:8000/test/livre`

    -`https://127.0.0.1:8000/test/emprunteur`

    -`https://127.0.0.1:8000/test/emprunt`

Pour vérifier certaines données rendez vous sur PhpMyAdmin et entrez le nom d'utilisateur et mot de passe renseigné dans `.env.local`

*PS: La requête de supression d'un livre ne marche que si le livre n'est pas emprunté*

## Mentions légales

Ce projet est sous licence MIT.

Licence disponible ici [LICENCE](LICENCE)
