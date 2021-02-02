<?php 

// Documentation PDO :https://www.php.net/manual/fr/book.pdo.php

// Etape 1, je dois gérer la connection à ma base de données via l'instanciation d'une classe PD0 pour PHP DATA OBJECT

// Le dsn contient les informations requises pour se connecter => mysql c'est le driver ou pilote qui permet la commication avec le logiciel de base de données
// Le host : c'est l'adresse du serveur sur lequel est la base de données
// dbname : c'est le nom de la base de données avec laquelle le souhaite me connecter


$dsn = 'mysql:host=localhost;dbname=blogchien';
$user = 'blogChien';
$mdp = 'blogChien';

// là je crée un objet à partir de ma classe PDO pour faire la connexion. Le tableau transmis est un moyen d'afficher les erreurs de connexion à la bdd si il y en as

try {
    $pdoDBConnexion = new PDO(
        $dsn, 
        $user, 
        $mdp, 
        array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING)
    );
} catch(PDOException $e) {
    echo 'Connexion échouée :'.$e->getMessage();
}

// J'ai transmis à l'instanciation de PDO les paramètres nécessaires définis plus haut + un tableau pour ajouter une option d'affichage et de gestion des erreurs, cf la document ici : https://www.php.net/manual/fr/pdo.error-handling.php 

var_dump($pdoDBConnexion);

// Etape 2 je veux pouvoir récupérer mes données ou bien faire des traitements de type CRUD, je vais donc utiliser des requêtes SQL.

$sql = "
SELECT * from `article`
";

//var_dump($sql);

// Etape 3 j'ai besoin d'éxecuter ma requête 
$pdoStatement = $pdoDBConnexion->query($sql);

var_dump($pdoStatement);

// Etape 4, je récupère les résultats de la requête
// fetchAll peut prendre différents paramètres qui me permettent de contrôler sous quelle forme me sont retournées les résultats, la documentation : https://www.php.net/manual/fr/pdostatement.fetch.php + https://www.php.net/manual/fr/pdostatement.fetchall.php

$resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

var_dump($resultats);
