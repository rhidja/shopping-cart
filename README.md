# Shopping Cart

# Features
  - Affichage de tous les produits sur la page d'accueil
  - Affichage de la fiche d'un produit
  - Ajout d'un produit dans le panier
  - Supprimer un produit dans le panier
  - Vider le panier
  - Un espace backoffice "easyadmin" pour la gestion des produits
  - Affichage de la liste des produits dans un panier
  - Application testé
  - Exportation de données sous format CSV et TXT
  - Point d'accès API http://SERVER/api/produits

### Tech

Pour installer cette application il faut installer au préalable un certain nombre d'application, voir leurs doccumentation officiel.
* Composer,
* NodeJs
* Yarn
* PHPUnit

Utilisation de travis-ci pour l'intégration continue.

### Installation

Cloner cette application à partir de mon compte Github:

```
$ git clone https://github.com/rhidja/lexik.git
```

Aller dans le dossier lexik pour installer les dépendances de l'application

```
$ cd lexik
$ composer install
```

Executer les commandes suivantes pour changer les paramètres de la base de données

```
$ sed -i -e "s/db_user/DB_USER/g" .env
$ sed -i -e "s/db_password/DB_PASSWORD/g" .env
$ sed -i -e "s/db_name/DB_NAME/g" .env
```

Créer un dossier pous les exports

```
$ mkdir exports/
$ chmod 777 -R exports/
```

Executer les commandes suivantes pour la création de la base de données

```
$ php bin/console doctrine:database:drop --force
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console --no-interaction doctrine:migrations:migrate
```

Executer la commande suivante pour charger les fixtures

```
$ php bin/console --no-interaction doctrine:fixtures:load
```

Installer les composants JS

```
$ npm install @symfony/webpack-encore --save-dev
$ yarn run encore dev

```
Executer les tests unitaires

```
$ cp phpunit.xml.dist phpunit.xml
$ vendor/bin/simple-phpunit
```
