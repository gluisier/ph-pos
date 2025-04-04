# 🎪 ᴾᴴPOS

> [!NOTE]
> [English version available](README.md)

Une application de caisse (en anglais Point Of Service ou POS) et de gestion de production (ERP) en PHP/Symfony.

Cette aplication est apparue après plusieurs événements locaux, où la vente d'articles comme les boissons et repas habituels ont intéressé les statisticiens.

En tant que pages web, celles-ci sont prévues pour s'adapter aux différents écrans.

L'application consiste en quatre parties principales.

## Liste de prix

Aucunement interactive, cette page est prévue pour être imprimée si elle n'est pas utilisée sur un écran.

## Ventes (POS)

Cette page est prévue pour les vendeurs. Elle permet d'enregistrer les ventes sur le serveur, d'afficher le résumé aux clients, mais peut aussi être utilisées comme simple calculatrice.
L'affichage des articles peut se faire sous forme de tableau ou de tuiles, avec le total affiché en bas de page. Il est possible d'afficher en mode sombre ou clair, et de choisir si l'on veut ou non voir les noms des articles. Des couleurs sont utilisées pour permettre de retrouver rapidement les articles ; en cas de tickets physiques, il est intéressant de faire coïncider ces couleurs.
Plusieurs méthodes de paiement peuvent être utilisées.

## Production (ERP)

En se basant sur les données de vente, cette page peut aider à préparer certains articles. Dans le cas de plats, on peut préparer les saucisses sur le grill, la salade sur les assiettes, trancher le pain, et s'assurer que tout ce qui est sur le grill ou en chauffe-plats suffit à couvrir les ventes.

## Admin (back-office)

Cette partie permet aux responsable la préparation de l'événement, renseignant les informations sur les articles et leurs prix, visualiser les ventes et d'autres informations.

## Technique

### Prérequis

#### PHP

L'analyse du [composer.json](./composer.json) renseigne sur la version de PHP et les extensions requises.
Pour les réglages, cette application a été testée avec 30 secondes de temps d'exécution maximum avec 128 Mo de mémoire à disposition, sur un serveur Apache 2.4.

#### Base de données

L'application se repose sur une base de données, ne serait-ce que pour enregistrer les articles et les utilisateurs. Les migrations existent pour MySQL/MariaDB, et pourraient être adaptées facilement pour d'autres gestionnaires de bases de données.

#### Navigateur

L'utilisation d'un navigateur grand public à jour ne causera aucun souci. En cas de doute, les points sont mentionnés ci-après.
Plutôt que d'utiliser une bibliothèque d'icones, les emojis Unicode sont recommandés pour les étiquettes. _Veuillez noter qu'en conséquence, l'affichage de ceux-ci diffère entre les systèmes d'exploitation, leurs versions et même entre navigateurs sur une même machine._

Les bibliothèques sont chargées depuis [&lt;cdnjs&gt;](https://cdnjs.com/).

##### JavaScript
Afin de pouvoir accéder à toutes les fonctionnalités, votre navigateur doit supporter :

- l'objet BroadcastChannel, sur la page des ventes, pour afficher le résumé (utilisateur authentifié uniquement) ;
- l'objet EventSource, sur la page de production, pour récupérer les nouvelles ventes.

L'application utilise aussi [Alpine.js](//alpinejs.dev/) afin de mieux gérer les mises à jour automatiques sur diverses pages, ainsi que pour construire certaines d'entre elles.

##### CSS
L'application utilise Bootstrap 5.3 qui contient du code, des solutions de contournement et des succédanés afin de fonctionner sur tous les navigateurs à jour.

##### HTML
- L'attribut `inputmode` peut être utilisé sur différentes balises `<input />` afin de déclencher l'affichage des claviers tactiles adaptés. Le type `number` est largement utilisé pour permettre une validation côté client.
- La balise `<output>` est utilisée pour les totaux.

### Installation

1. Récupérez ce dépôt
2. Utilisez [composer](https://getcomposer.com/) pour installer les dépendances
   ```
   $> cd path/to/phPOS
   $path/to/phPOS> composer install
   ```
3. Copiez le fichier `.env` vers `.env.local` et adaptez les paramètres
4. Exécutez les migrations
   ```
   $path/to/phPOS> php bin/console doctrine:migrations:migrate
   ```
5. Enregistrez un nouvel utilisateur par le biais de la page d'enregistrement
6. Activez-le directement en base, et accordez-lui le rôle `ROLE_ADMIN`

Vous pouvez désormais vous connecter à l'application.

## Utilisation

### Admin

Au plus simple, vous vendez des **articles**, qui peuvent être regroupés en **catégories** et payés avec divers **moyens de paiement**.

Ces trois « concepts » reposent sur une structure de données commune, formé par les informations suivantes.

1. **`title`** (〰️) : sert de représentation _textuelle_ d'un concept.
2. **`label`** (🏷️) : sert de représentation _graphique_ d'un concept – c'est là que les emojis sont pratiques.
3. **`colour`** : aide à retrouver un concept dans les listes — mais n'est pas utilisé sur la liste de prix.
   Cette couleur peut aussi être correlée à celle de tickets physiques.
4. **`public`** (📖) : détermine si le concept est public, donc s'il apparaît dans la liste de prix.

#### Catégories

Avec elles, vous pouvez regrouper les articles dans la liste de prix, mais aussi sur la page de ventes pour aider les vendeurs. Les catégories peuvent être tout ce que vous voulez, à ceci près qu'elles n'ont **pas de prix**.

#### Items

Ce sont vos articles. Que vous vendiez des hot-dogs ou des boissons en bouteilles ou canettes, tout cela sera enregistré comme article dans l'application. Tout ce qui est expressément vendu sera donc un article.
Les articles contiennent donc les mêmes informations que les catégories mentionnées ci-avant, mais aussi d'autres expliquées ci-dessous.

5. **`price`** : le prix auquel l'article sera vendu.
6. **`stock`** : la quantité disponible à la vente. Vous pouvez laisser vide si vous n'avez pas l'information ou qu'elle ne vous semble pas pertinente.
7. **`ticket`** (🎟️) : à activer quand l'article n'est pas vendu là où l'on encaisse, mais qu'un ticket est délivré à faire valoir à un autre emplacement.
8. **`available`** (💸) : à comprendre comme _disponible à la vente_. De fait, si non coché, l'article ne s'affichera ni dans la liste de prix, ni sur la page de vente.
9. **`separately sellable`** (⛓️‍💥) : un élément technique dont l'utilité est expliquée ci-après.

##### Packs
Créer des packs packs est une manière d'accélérer le processus de vente. Vous avez de la vaisselle réutilisable et consignée ? Un pack vous permet de vendre une boisson avec un verre, un plat avec assiette-couteau-fourchette et comptabiliser le tout simplement.

Du point de vue technique, un pack est un article composé d'autres articles.

> [!WARNING]
> **N'imbriquez pas les packs**. Dupliquez plutôt un pack existant pour y ajouter ce qui lui manque.

##### Variantes
Un des meilleurs exemples à propos d'elles est le suivant. Imaginez que vous vendiez des T-Shirts. Il y a plusieurs tailles et plusieurs couleurs. Vous allez donc concrètement vendre des T-Shirts verts XL, bleu M, etc., et non simplement "des T-Shirts". Cependant, vous n'allez pas afficher toutes les combinaisons de taille et de couleurs dans la liste de prix, vous allez plutôt lister les tailles et les couleurs disponibles.
Une combinaison de taille et de couleur est considérée comm une **variante**.
Comme vous n'allez pas vendre simplement une taille ou une couleur, c'est là qu'intervient le paramètre _`separately sellable`_.

D'autres exemples sont:
- des hot-dogs avec mayonnaise, ketchup ou moutarde;
- des saucisses avec soit de la salade, du pain ou des frites.

Le dernier exemple peut expliquer le fait que **les variantes peuvent avoir un prix différent que l'article de base**. En effet, les frites sont plus coûteuses que de la salade, qui elle-même est moins avantageuse que du pain.

Du point de vue technique, les variantes sont similaires à des packs, mais là où les packs sont composés d'articles qui peuvent être vendus séparéments, les variantes sont composées d'articles **non** vendus séparément.