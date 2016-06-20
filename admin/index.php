<?php


// ********************** model **************************

require_once('../init.php');

// ********************** controleur **************************

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['identifiant']) && isset($_POST['motDePasse'])){
    $identifiant = htmlspecialchars($_POST['identifiant']);
    $motDePasse = htmlspecialchars($_POST['motDePasse']);
    if($identifiant === $config['admin_id'] && password_verify($motDePasse,$config['admin_pass'])){
      $_SESSION['admin'] = true;
      header('Location:admin.php');
      exit;
    }
    $_SESSION['admin'] = false;
  }
}

// ********************** vue **************************

require_once('vue/index.phtml');
