<?php
//------------------------------------------------------------------------------------------------ BASE DE DONNE
function recuperationDbId($nomTable,$id){
  global $db;
  $requetePDO = $db->query('SELECT *
    FROM '.$nomTable.'
    WHERE id = '.$id);
    $tabResultat = $requetePDO->fetchAll();


    return $tabResultat;
}

function suppressionDb($table,$id){
  global $db;

  $sql = 'DELETE FROM '.$table.'
          WHERE id = :id';

    $requete = $db->prepare($sql);

    $requete->execute([':id' => $id]);

}
 
function modifDb_projets($id,$nom,$categorie,$redirection,$techno,$statut,$description){
  global $db;

  $sql = 'UPDATE projets
          SET nom = :nom,
              categorie = :categorie,
              redirection = :redirection,
              techno = :techno,
              statut = :statut,
              description = :description
          WHERE id = :id
          ';

  $requete = $db->prepare($sql);

  $resultat = $requete->execute([':id' => $id,
                     ':nom' => $nom,
                     ':categorie' => $categorie,
                     ':redirection' => $redirection,
                     ':techno' => $techno,
                     ':statut' => $statut,
                     ':description' => $description]);

   if($resultat) {
       return $resultat;
   } else {
       $erreur = $requete->errorInfo();
       return $erreur[2];
   }


}

function ajoutDb_categorie($categorie){
  global $db;
  $sql = 'INSERT INTO categories (categorie)
                  VALUES (:categorie)';

  $requete = $db->prepare($sql);

  $resultat = $requete->execute([':categorie' => $categorie]);
  if($resultat) {
      return $resultat;
  } else {
      $erreur = $requete->errorInfo();
      return $erreur[2];
  }

}

function ajoutDb_image($nom,$id_projet,$fichier){
  global $db;
  $sql = 'INSERT INTO images (nom,id_projet,fichier)
                  VALUES (:nom,:id_projet,:fichier)';

  $requete = $db->prepare($sql);

  $resultat = $requete->execute([':nom' => $nom,
                                 ':id_projet' => $id_projet,
                                 ':fichier' => $fichier]);
  if($resultat) {
      return $resultat;
  } else {
      $erreur = $requete->errorInfo();
      return $erreur[2];
  }

}

function ajoutDb_projets($nom,$categorie,$redirection,$techno,$statut,$description,$fichier){
    global $db;
    $sql = 'INSERT INTO projets (nom,categorie,redirection,techno,statut,description,img_mini)
                    VALUES (:nom,:categorie,:redirection,:techno,:statut,:description,:img_mini)';

    $requete = $db->prepare($sql);

    $resultat = $requete->execute([':nom' => $nom,
                                   ':categorie' => $categorie,
                                   ':redirection' => $redirection,
                                   ':techno' => $techno,
                                   ':statut' => $statut,
                                   ':description' => $description,
                                   ':img_mini' => $fichier]);
    if($resultat) {
        return $resultat;
    } else {
        $erreur = $requete->errorInfo();
        return $erreur[2];
    }
}



//------------------------------------------------------------------------------------------------ FICHIER
/**
 * Cette fonction extrait l’extension du fichier passé en paramètre.
 * @param string $nom_fichier un nom de fichier à analyser
 * @return string l’extension du fichier en minuscules, sans le point initial
 */
function extension_fichier($nom_fichier) {
  $position_point = strrpos($nom_fichier, '.');
  if($position_point === false) {
    return '';
  }
  $extension = strtolower(substr($nom_fichier, $position_point + 1));
  // echo '<pre>On extrait l’extension de '.$nom_fichier.' : « '.$extension.' »</pre>';
  return $extension;
}

/**
 * Cette fonction verifie dans à l'adresse $dossier si il n'y a pas de doublons de la $cible
 * @param string $cible un nom de fichier à comparer
 * @param string $dossier l'adresse d'un dossier
 * @return bool true = il n'y a pas de doublon
 */

function verifyDoublon($cible,$dossier){
  $tabFichiers = scandir($dossier);
  foreach($tabFichiers as $i){
    if($i == $cible){
      return false;
    }
  }
  return true;
}


//------------------------------------------------------------------------------------------------ IMG
/**
 * Cette fonction permet d’indiquer si une chaîne de caractères est un
 * nom d’image, selon son extension.
 * @param string $value le nom de fichier
 * @return bool
 */
 function is_a_image($value) {
   $extensions_autorisees = ['jpg', 'jpeg','gif', 'png','svg'];
   // echo '<pre>On évalue le fichier '.$value.'</pre>';
   $extension_fichier = extension_fichier($value);
   $verif = false ;
   foreach($extensions_autorisees as $i){
     if($extension_fichier == $i){
       $verif = true ;
     }
   }

   return $verif;
 }

 //------------------------------------------------------------------------------------------------ FORMULAIRE

 /**
  * Cette fonction valide une donnée (typiquement soumise dans un
  * formulaire), et renvoie soit true, soit un message d’erreur, selon
  * une liste de règles (un tableau associatif dont les clés sont les
  * noms de règles, et les valeurs sont les éventuels paramètres de ces
  * règles).
  *
  * Règles possibles :
  *
  * - required : renverra true si la valeur n’est pas vide (l’argument
  *   importe peu)
  * - min : renverra true si le nombre de caractères de la valeur est
  *   supérieur ou égal à l’argument
  * - max : renverra true si le nombre de caractères de la valeur est
  *   inférieur ou égal à l’argument
  * - function : la fonction passée en argument sera appelée, avec la
  *   valeur en paramètre. Le retour de la fonction sera renvoyé (celui-ci
  *   doit donc être true ou un message d’erreur)
  *
  * @param string $value la valeur à tester
  * @param array $rules le tableau de règles (voir plus haut)
  * @return bool|string true si toutes les règles sont respectées, le
  * message d’erreur correspondant sinon
  */
 function validate_form_data($value, $rules) {
     foreach($rules as $rule_name => $arg) {
         switch($rule_name) {

         case 'required':
             if(empty($value)) return 'Cette valeur est requise.';
             break;

         case 'min':
             if(strlen($value) < $arg) return 'Cette valeur doit faire au moins '.$arg.' caractères.';
             break;

         case 'max':
             if(strlen($value) > $arg) return 'Cette valeur doit faire au maximum '.$arg.' caractères.';
             break;

         case 'function':
             if(is_array($arg)) {
                 $function_name = $arg['name'];
                 if(!function_exists($function_name)) return 'La fonction de validation '.$function_name.' n’existe pas.';
                 return $function_name($value, $arg['args']);
             } else {
                 if(!function_exists($arg)) return 'La fonction de validation '.$arg.' n’existe pas.';
                 return $arg($value);
             }
             break;

         case 'email':
             if(!filter_var($value, FILTER_VALIDATE_EMAIL)) return 'Veuillez renseigner une adresse e-mail valide.';
             break;

         case 'uploaded':
             if($value['error'] != UPLOAD_ERR_OK) {
                 switch($value['error']) {
                     case UPLOAD_ERR_INI_SIZE:
                     case UPLOAD_ERR_FORM_SIZE:
                         $message = 'Votre fichier est trop gros.';
                         break;
                     case UPLOAD_ERR_PARTIAL:
                         $message = 'Le fichier a été envoyé partiellement ; veuillez réessayer.';
                         break;
                     case UPLOAD_ERR_NO_FILE:
                         // si on fournit « false » à cette règle, on
                         // considère que cette erreur n’est pas grave.
                         if(!$arg) return true;
                         $message = 'Veuillez choisir un fichier.';
                         break;
                     default:
                         $message = 'Une erreur est survenue durant l’envoi du fichier (code '.$value['error'].').';
                         break;
                 }
                 return $message;
             }
             break;

         default:
             return 'Règle '.$rule_name.' introuvable.';
             break;
         }
     }
     return true;
 }

 /**
  * Cette fonction permet de valider intégralement un formulaire,
  * en appliquant une série de règles à chacun de ses champs.
  * Elle prend en paramètres un tableau associatif, dont les clés sont les
  * noms de champs et les valeurs sont les valeurs soumises (un tableau
  * associatif dans le cas d’un upload de fichier). Un second paramètre
  * définit les règles de validation à appliquer sur chacun des champs.
  * Elle retourne trois valeurs : un éventuel message d’erreur principal,
  * une liste de messages d’erreurs sous forme de tableau associatif dont
  * les clés correspondent aux noms de champs, et enfin une liste de valeurs
  * nettoyées correspondant au premier argument.
  *
  * @param array $values un tableau associatif, issu de $_POST ou autre (ou
  * plusieurs sources fusionnées).
  * @param array $rules un tableau associatif, dont les clés représentent
  * les champs requis, et dont les valeurs sont des tableaux de règles tels
  * qu’acceptés par la fonction validate_form_data.
  * @return array un tableau associatif contenant 3 clés : 'erreur',
  * 'erreurs_champs' et 'valeurs_nettoyees'.
  */
 function validate_form($values, $rules) {
     $erreur = '';
     $erreurs_champs = [];
     // on préremplit $valeurs_nettoyees avec les clés de $rules
     $valeurs_nettoyees = [];
     foreach($rules as $cle => $null) {
         $valeurs_nettoyees[$cle] = '';
     }
     foreach($rules as $cle => $tab_regles) {
         // est-ce que ce champ a été soumis ?
         if(isset($values[$cle])) {
             if(is_array($values[$cle])) {
                 $valeurs_nettoyees[$cle] = $values[$cle];
             } else {
                 $valeurs_nettoyees[$cle] = htmlspecialchars($values[$cle]);
             }
             $statut = validate_form_data($values[$cle], $tab_regles);
             if($statut !== true) {
                 $erreur = 'Veuillez corriger les erreurs suivantes.';
                 $erreurs_champs[$cle] = $statut;
             }
         } else {
             $erreur = 'Erreur lors de l’envoi des champs. Veuillez réessayer';
             break;
         }
     }
     return ['erreur' => $erreur,
             'erreurs_champs' => $erreurs_champs,
             'valeurs_nettoyees' => $valeurs_nettoyees];
 }
