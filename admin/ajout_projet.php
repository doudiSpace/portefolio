<?php

// **********************************************************************************************************************************
// ******************************************************* model ********************************************************************
// **********************************************************************************************************************************

require_once('initAdmin.php');

// **********************************************************************************************************************************
// ***************************************************** controleur *****************************************************************
// **********************************************************************************************************************************



// ****************** VERIFICATION + L'ENVOI DU FORMULAIRE ***************************************************

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if (isset($_POST['nom'], $_POST['techno'],$_POST['statut'],$_POST['categorie'],$_POST['redirection'],$_POST['description'], $_FILES['fichier'])) {
    //vérif des champs du formulaire
    $rules = ['nom' => ['required' => null],
              'categorie' => ['required' => null]];
    $form = validate_form($_POST, $rules);
    //si pas d'erreur dans la verification------------------
    if(!$form['erreur'] && isset($_FILES['fichier'])){
      if($_FILES['fichier']['error']==0){
        //si c'est une image------------------------------------
        if(substr($_FILES['fichier']['type'],0,6)=='image/'){
          if(is_a_image($_FILES['fichier']['name']) == true){
            //si le fichier nexiste pas deja------------------------
            $dossier = '../img/projets';
            if(verifyDoublon($_FILES['fichier']['name'],$dossier)){

              //-----------------------------------------------------------------------verif ok !
              //----------------------------------------------------------- 1 - intégrer l'image dans le dossier
              move_uploaded_file($_FILES['fichier']['tmp_name'],$dossier.'/'.htmlentities(basename($_FILES['fichier']['name'])));
              //---------------------------------------------------------- 2 - intégrer dans la base de donnée
              $nom = htmlspecialchars($_POST['nom']);
              $categorie = htmlspecialchars($_POST['categorie']);
              $redirection = htmlspecialchars($_POST['redirection']);
              $techno = htmlspecialchars($_POST['techno']);
              $statut = htmlspecialchars($_POST['statut']);
              $fichier = htmlspecialchars($_FILES['fichier']['name']);
              $description = htmlspecialchars($_POST['description']);

              ajoutDb_projets($nom,$categorie,$redirection,$techno,$statut,$description,$fichier);
              //---------------------------------------------------------- 3 - redirection
              header('location:  admin.php');
              exit;

            }
          }
        }
      }
    }
  }
}

// ********************** CREATION DU FORMULAIRE *************************************************************

$categories = '';
  $tab_categories = recuperationDb('categories');
  foreach ($tab_categories as $c) {
    $categories .= '<option value="'.$c['categorie'].'">'.$c['categorie'].'</option>';
  }

$formulaire = '
        <input type="text" name="nom" placeholder="NOM"><br><br>

        <label >Catégorie :
          <select name="categorie">
            '.$categories.'
          </select>
        </label><br><br>

        <input type="text" name="redirection" placeholder="redirection"><br><br>

        <label>Image mini :
          <input type="file" name="fichier">
        </label><br><br>

        <input type="text" name="statut" placeholder="statut"><br><br>

        <textarea name="description" rows="8" cols="40" placeholder="descritpion*..."></textarea><br> <br>

        <textarea name="techno" rows="8" cols="40" placeholder="techno*..."></textarea><br> <br>

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


require_once('vue/ajout.phtml');
