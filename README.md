# Shopping Cart
Cette application a été réalisé avec le framewrok Symfony version 4.

-   demo : lexik.hidja.fr
-   api : lexik.hidja.fr/api/prodtuis

### Fonctionalités
  - Affichage de tous les produits sur la page d'accueil
  - Affichage de la fiche d'un produit
  - Ajout d'un produit dans le panier
  - Supprimer un produit du panier
  - Affichage de la liste des produits dans un panier
  - Vider le panier
  - Un espace backoffice pour la gestion des produits
  - Exportation des données sous format csv ou txt
  `php bin/console app:exporter csv`
  - Tests unitaires et fonctionnels

### Techs

Pour installer cette application, il faut installer au préalable un certain nombre d'application, voir leurs doccumentation officiel.
* Git
* Composer
* Yarn
* PHPUnit

### Installation

A l'aide de Git cloner cette application à partir de mon compte Github sur votre serveur:

```
$ git clone https://github.com/rhidja/lexik.git
```

Aller dans le dossier "lexik" et changer les variables d'environnement dans le fichier .`env`
Par la suite, ouvrir le fichier `install.sh` et personnaliser les variables au début du fichier.
Puis lancer les commandes suivantes

```
$ cd lexik/
$ chmod +x install.sh
$ sudo ./install.sh
```

Le script `install.sh` permets de :

- Installer les composants de symfony dans le dossier vendor
- Créer la base de données
- Faire la migration de la base de données
- Charger les fixtures
- Installer les composants JS
- Faire le dump des assetics

Attention : ce script supprime toutes les données qui sont dans la base de données.

Pour exectuer les tests unitaires lancer les commandes suivantes :

```
$ cp phpunit.xml.dist phpunit.xml
$ vendor/bin/simple-phpunit
```
