<?php

// **********************************************************************************************************************************
// ******************************************************* model ********************************************************************
// **********************************************************************************************************************************

require_once('initAdmin.php');

// **********************************************************************************************************************************
// ***************************************************** controleur *****************************************************************
// **********************************************************************************************************************************

// ****************** VERIFICATION + L'ENVOI DU FORMULAIRE ***************************************************
if (isset($_FILES['fichier'])) {

  //vérif des champs du formulaire
  $rules = ['fichier' => ['required' => null]];
  $form = validate_form($_FILES, $rules);
  //si pas d'erreur dans la verification------------------
  if(!$form['erreur'] ){

    if($_FILES['fichier']['error']==0){
      //si c'est un pdf ------------------------------------

      if($_FILES['fichier']['type']=='application/pdf'){
          $dossier = '../img/cv';


            // -----------------------------------------------------------------------verif ok !
            // ----------------------------------------------------------- 1 - intégrer le cv dans le dossier
            move_uploaded_file($_FILES['fichier']['tmp_name'],$dossier.'/cv.pdf');
            // ---------------------------------------------------------- 2 - redirection
            header('location:  admin.php');
            exit;


      }
    }
  }
}

// ********************** CREATION DU FORMULAIRE *************************************************************


$formulaire = '
          <label>Fichier :
          <input type="file" name="fichier">
        </label><br><br>
';

// ********************** CREATION DE LA VUE *****************************************************************
$vue = [];
$vue['titre'] = 'du CV';
$vue['formulaire'] = $formulaire;
$vue['retour'] = 'admin.php';

// **********************************************************************************************************************************
// ******************************************************* vue **********************************************************************
// **********************************************************************************************************************************


require_once('vue/modif.phtml');
