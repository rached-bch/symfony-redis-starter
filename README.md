# Installation

 - Fixation de tous les erreurs de php apparus via la commande : symfony check:requirements (utilisation du fichier binaire de symfony 5)
 - Installation de la version demo de symfony 5 via le binaire symfony : symfony new symfony-redis-example --demo
 - Installation de redis sur le serveur : sudo apt install redis-server
 - Installation de l'extension redis avec php7 : sudo apt-get install php-redis
 - Démarrage du serveur local : symfony server:start
 - Ajout d'un article pour s'assurer qu'il a été mis en cache
