<?php
session_start();
define('MYSQL_HOST', ''); // Variable global, clé : MYSQL_HOST   valeur : localhost
define('MYSQL_USER', '');
define('MYSQL_PASSWORD', '');
define('MYSQL_DB', 'boulangeries');
try 
{
    $PDO = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD); // CONNECTION A MA BASE
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // 
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // Affiche les erreurs en objets

}  
catch (PDOException $e) 
{
    $e->getMessage();
}
?>
