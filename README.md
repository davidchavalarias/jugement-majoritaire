jugement-majoritaire
====================

Etude du jugement majoritaire

##Installation (Sous Linux et OS X)

**Pré-requis :**  Apache, PHP, MySQL, un client Git et une bonne connexion à internet.

> Pour installer ces paquets, ou juste pour vérifier qu'ils sont déjà présents, tapez dans une console : `sudo apt-get install apache2 php5 mysql-server libapache2-mod-php5 php5-mysql git`

1. Ouvrez une console et, dans le dossier souhaité, tapez`git clone https://github.com/Artemis-Haven/jugement-majoritaire.git`

2. Entrez dans le dossier sources avec `cd jugement-majoritaire/sources`

3. Installez les dépendances avec `php composer.phar install` . Si vous avez une erreur de limite de mémoire, réessayez avec la commande suivante : `php -d memory_limit=-1 composer.phar install`

4. Pendant l'installation, remplissez les champs demandés (ceux de `parameters.yml`). Les seuls champs importants sont les suivants : `database_name` est le nom de la base de données dans MySQL, `database_user` et `database_password` sont vos identifiants. Vous pouvez laisser les autres champs par défaut.

5. Réglez les problèmes de cache en faisant `sudo chmod 777 -R app/logs/ app/cache/ && sudo php app/console cache:clear && sudo chmod 777 -R app/logs/ app/cache/`

6. Générez la base de données avec la commande `php app/console doctrine:database:create && php app/console doctrine:schema:update --force`

10. Si tout s'est bien passé, le site est désormais opérationnel. Sinon, appelez les pompiers.
