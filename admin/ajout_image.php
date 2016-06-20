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
  if (isset($_POST['nom'], $_POST['id_projet'],$_FILES['fichier'])) {
    //vérif des champs du formulaire
    $rules = ['nom' => ['required' => null],
              'id_projet' => ['required' => null]];
    $form = validate_form($_POST, $rules);
    //si pas d'erreur dans la verification------------------
    if(!$form['erreur'] && isset($_FILES['fichier'])){
      if($_FILES['fichier']['error']==0){
        //si c'est une image------------------------------------
        if(substr($_FILES['fichier']['type'],0,6)=='image/'){
          if(is_a_image($_FILES['fichier']['name']) == true){
            //si le fichier nexiste pas deja------------------------
            $dossier = '../img/index-slide';
            print_r('doubon de fichier');
            if(verifyDoublon($_FILES['fichier']['name'],$dossier)){

              //-----------------------------------------------------------------------verif ok !
              //----------------------------------------------------------- 1 - intégrer l'image dans le dossier
              move_uploaded_file($_FILES['fichier']['tmp_name'],$dossier.'/'.htmlentities(basename($_FILES['fichier']['name'])));
              //---------------------------------------------------------- 2 - intégrer dans la base de donnée
              $nom = htmlspecialchars($_POST['nom']);
              $fichier = htmlspecialchars($_FILES['fichier']['name']);
              $id_projet = htmlspecialchars($_POST['id_projet']);

              ajoutDb_image($nom,$id_projet,$fichier);
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

$option_projet = '';
  $tab_option_projet = recuperationDb('projets');
  foreach ($tab_option_projet as $p) {
    $option_projet .= '<option value="'.$p['id'].'">'.$p['nom'].'</option>';
  }

$formulaire = '
        <input type="text" name="nom" placeholder="NOM"><br><br>

        <label >projet associé:
          <select name="id_projet">
            '.$option_projet.'
          </select>
        </label><br><br>

        <label>Fichier :
          <input type="file" name="fichier">
        </label><br><br>
';

// ********************** CREATION DE LA VUE *****************************************************************
$vue = [];
$vue['titre'] = 'd\'une image';
$vue['formulaire'] = $formulaire;
$vue['retour'] = 'admin.php';

// **********************************************************************************************************************************
// ******************************************************* vue **********************************************************************
// **********************************************************************************************************************************


require_once('vue/ajout.phtml');
