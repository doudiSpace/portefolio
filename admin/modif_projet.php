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

$ligneId = recuperationDbId('projets',$id)[0];



// ****************** VERIFICATION + L'ENVOI DU FORMULAIRE ***************************************************

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if (isset($_POST['nom'], $_POST['techno'],$_POST['statut'],$_POST['categorie'],$_POST['redirection'],$_POST['description'])) {
    //vérif des champs du formulaire
    $rules = ['nom' => ['required' => null],
              'categorie' => ['required' => null]];
    $form = validate_form($_POST, $rules);
    //si pas d'erreur dans la verification------------------
    if(!$form['erreur']){

      //-----------------------------------------------------------------------verif ok !
      //---------------------------------------------------------- 1 - intégrer dans la base de donnée
      $nom = htmlspecialchars($_POST['nom']);
      $categorie = htmlspecialchars($_POST['categorie']);
      $redirection = htmlspecialchars($_POST['redirection']);
      $techno = htmlspecialchars($_POST['techno']);
      $statut = htmlspecialchars($_POST['statut']);
      $description = htmlspecialchars($_POST['description']);

      modifDb_projets($id,$nom,$categorie,$redirection,$techno,$statut,$description);
      //---------------------------------------------------------- 2 - redirection
      header('location:  admin.php');
      exit;

    }
  }
}

// ********************** CREATION DU FORMULAIRE *************************************************************


$categories = '';
  $tab_categories = recuperationDb('categories');
  foreach ($tab_categories as $c) {
    if ($c['categorie'] == $ligneId['categorie']) {
      $categories .= '<option value="'.$c['categorie'].'" selected="selected">'.$c['categorie'].'</option>';
    }else{
      $categories .= '<option value="'.$c['categorie'].'">'.$c['categorie'].'</option>';

    }
  }

$formulaire = '
        <input type="hidden" name="id" value="'.$id.'">

        <input type="text" name="nom" placeholder="NOM" value="'.$ligneId['nom'].'"><br><br>

        <label >Catégorie :
          <select name="categorie" >
            '.$categories.'
          </select>
        </label><br><br>

        <input type="text" name="redirection" placeholder="redirection" value="'.$ligneId['redirection'].'"><br><br>

        <input type="text" name="statut" placeholder="statut" value="'.$ligneId['statut'].'"><br><br>

        <textarea name="description" rows="8" cols="40" placeholder="descritpion*...">'.$ligneId['description'].'</textarea><br> <br>

        <textarea name="techno" rows="8" cols="40" placeholder="techno*...">'.$ligneId['techno'].'</textarea><br> <br>

        <p>* utiliser le caractère spécial | pour passer à la ligne.</p><br><br><br>
';

// ********************** CREATION DE LA VUE *****************************************************************
$vue = [];
$vue['titre'] = 'd\'un projet';
$vue['formulaire'] = $formulaire;
$vue['retour'] = 'admin.php';

// **********************************************************************************************************************************
// ******************************************************* vue **********************************************************************
// **********************************************************************************************************************************


require_once('vue/modif.phtml');
