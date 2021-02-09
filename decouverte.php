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
// fetch et fetchAll peut prendre différents paramètres qui me permettent de contrôler sous quelle forme me sont retournées les résultats, la documentation : https://www.php.net/manual/fr/pdostatement.fetch.php + https://www.php.net/manual/fr/pdostatement.fetchall.php
// fetchAll ça permet de récupérer tous les résultats de ma requête tandis que fetch me récupère un résultat à la fois.

$resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

//$resultats = $pdoStatement->fetch(PDO::FETCH_ASSOC);
//var_dump($resultats);
//$resultats2 = $pdoStatement->fetch(PDO::FETCH_ASSOC);
//var_dump($resultats2);


$sql = "
    INSERT INTO `article` (`title`, `content`, `author`, `date`, `category`)
    VALUES ('Le plus beau chien du monde', 'lorem ipsum', 'Mathilde', '2020-02-10', 'Loisirs');
";

$affectedRows = $pdoDBConnexion->exec($sql);
var_dump($affectedRows);

$title = 'Les jours sans fin de mon chien';
$author = 'Grégory';
$category = 'Sortie';
$date = '2021-01-01';

$sql = "
    INSERT INTO `article` (`title`, `content`, `author`, `date`, `category`)
    VALUES ('{$title}', 'lorem ipsum', '{$author}', '{$date}', '{$category}'); 
";

$newAffectedRows = $pdoDBConnexion->exec($sql);
var_dump($newAffectedRows);



$id = 15;

$sql = "
    DELETE FROM `article` WHERE id = '{$id}';
";

$affectedRows = $pdoDBConnexion->exec($sql);

if ($affectedRows === false){
    $response = "Problème dans la requête, erreur SQL";
} else if ($affectedRows === 0){
    $response = "Aucune modification a eu lieu dans la base de données, rien à changer";
} else {
    $response = "Requête a bien fonctionné, et ". $affectedRows ." lignes ont été modifiées en base de données"; 
}

echo $response;

//var_dump($affectedRows);

// Mettre en place une requête préparée est un moyen de sécuriser son code et de ne pas subir d'injection SQL, c'est-à-dire de manipulation frauduleuse des données stockées dans notre base de données via des inputs utilisateurs (exemple, via des inputs d'un formulaire). https://fr.wikipedia.org/wiki/Injection_SQL

// Mise en place d'une requête préparée : 

$title = 'Quel est le chien le plus intelligent ?';
$author = 'Clément';

$sql = "
    INSERT INTO `article`(`title`, `content`, `author`, `date`, `category`)
    VALUES (:title, 'lorem ipsum', :author, '2020-01-01', 'Ballades'); 
";

$requetePrepared = $pdoDBConnexion->prepare($sql);
$requetePrepared->bindValue(':title', $title);
$requetePrepared->bindValue(':author', $author);
$result = $requetePrepared->execute();
var_dump($result);

// Je récapitule
// Une fois que ma classe PDO a été instanciée et que donc j'ai un objet PDO (ici stocké dans la variable $pdoDBConnextion)
// J'écris une requête SQL que je stocke dans une variable (ici $sql)
// Si je veux lire des données de ma BDD, j'utilise la méthode query() sur mon objet PDO et je lui transmet ma requête SQL. Si je veux faire n'importe quelle autre action que lire, j'utilise la méthode exec().
//J'exécute ma requête SQL et je récupère une boîte de résultats (exemple ma variable $pdoStatement)
// pour ensuite récupérer mes résultats, j'utilise la méthode fetch ou fetchAll (en fonction du nombre de résultats que je souhaite récupérer) sur ma boîte de résultats :
// exemple : ($resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);)
// Via les paramètres de fetch et fetchAll je peux préciser le format dans lequel je veux ces résultats.
// enfin je peux boucler sur mes résultats et les afficher par exemple ou bien faire un traitement sur ces résultats (= les données que j'ai récupérées en bdd)

// enfin dans le cas où j'exécute des requêtes SQL dont des valeurs sont issues d'input utilisateurs, il faut faire attention aux scripts malveillants (NEVER TRUST THE USER INPUT) et donc faire une requête préparée.