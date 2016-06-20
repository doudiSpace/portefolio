<?php

session_start();
require_once('function.php');
require_once('config.php');
/*---------------------------------------------model : initialisation db*/
try {
  $dsn = 'mysql:dbname='.$config['dbname'].';host='.$config['host'].';charset=utf8';
  $db = new PDO($dsn,$config['user'],$config['password']);
  //pour virer les indexs de l'objet(tableau) PDO
  //pour exo:faire un var_dump sur le $db
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit;
}
