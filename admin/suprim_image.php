<?php


// **********************************************************************************************************************************
// ******************************************************* model ********************************************************************
// **********************************************************************************************************************************

require_once('initAdmin.php');

// **********************************************************************************************************************************
// ***************************************************** controleur *****************************************************************
// **********************************************************************************************************************************

// ******************* vérification ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || is_numeric($_POST['id'])===false){
  header('Location: admin.php');
  exit;
}
$id = htmlspecialchars($_POST['id']);

// ******************* récupération de la ligne ID

$ligneId = recuperationDbId('images',$id);
$img = $ligneId[0]['fichier'];

// ************************************************************************* SUPPRESSION

if (isset($_POST['suppression'])){

  // ------------- 1 suppression de la base de donné
  suppressionDb('images',$id);

  // ------------- 2 suppression de l'image du dossier
  unlink('../'.$config['dossier_slide'].'/'.$img);

  // ------------- 3 redirection
  header('location:  admin.php');
  exit;

}

// *************************************************************************** FIN DE SUPPRESSION

// ******************* construction de la vue
$vue = [];
$vue['ligne'] = $id;
$vue['table'] = 'images';
$vue['affichage'] = '<img src="../'.$config['dossier_slide'].'/'.$img.'">';
$vue['retour'] = 'admin.php';

// **********************************************************************************************************************************
// ***************************************************** vue ************************************************************************
// **********************************************************************************************************************************

require_once('vue/suprim.phtml');