# Shopping Cart
Cette application a été réalisé avec le Framewrok [Symfony](https://symfony.com/) version 7.

-   demo : [cart.hidja.fr](cart.hidja.fr)
-   api : [cart.hidja.fr/api/produits](cart.hidja.fr/api/produits)

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

Pour installer cette application, il faut installer au préalable un certain nombre d'application, voir leurs documentation officiel.
* [Docker](https://www.docker.com/) et son plugin [Docker Compose](https://docs.docker.com/compose/)
* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org/)

### Installation

A l'aide de Git cloner cette application à partir de mon compte [Github](https://github.com/rhidja/cart) sur votre serveur:

```
$ git clone https://github.com:rhidja/shopping-cart.git
```

Aller dans le projet et lancer la commande suivante :

```
$ cd shoping-cart/
$ make init
```

- Installer les tous les composants de Symfony dans le dossier vendor
- Créer la base de données
- Faire la migration de la base de données
- Charger les fixtures
- Installer les composants JS
- Faire le dump des assetics

Pour exécuter les tests unitaires lancer les commandes suivantes :

```
$ make phpunit
```
