<?php


// ********************** model **************************


require_once('initAdmin.php');

// ********************** controleur **************************

// section projets

$recupDBprojet = recuperationDb('projets');

$trProjet = '';

foreach ($recupDBprojet as $key => $value) {

  $trProjet .= '<tr>';

  $trProjet .= '<td>';
  $trProjet .= '<p>'.$value['id'].'</p>';
  $trProjet .= '</td>';

  $trProjet .= '<td>';
  $trProjet .= '<img class="img-mini" src="../img/projets/'.$value['img_mini'].'">';
  $trProjet .= '</td>';


  $trProjet .= '<td>';
  $trProjet .= '<p>'.$value['nom'].'</p>';
  $trProjet .= '</td>';

  $trProjet .= '<td>';
  $trProjet .= '<p>'.$value['categorie'].'</p>';
  $trProjet .= '</td>';

  $trProjet .= '<td>';

  $redirection = ($value['redirection'] == '')?'<span class="attention">non</span>':'<span class="bien">oui</span>';

  $trProjet .= '<p>'.$redirection.'</p>';
  $trProjet .= '</td>';

  $trProjet .= '<td>';
  $trProjet .= '<form action="modif_projet.php" method="post">';
  $trProjet .= '<input type="hidden" name="id" value="'.$value['id'].'">';
  $trProjet .= '<input type="submit" value="modifier">';
  $trProjet .= '</form>';
  $trProjet .= '</td>';

  $trProjet .= '<td>';
  $trProjet .= '<form action="suprim_projet.php" method="post">';
  $trProjet .= '<input type="hidden" name="id" value="'.$value['id'].'">';
  $trProjet .= '<input type="submit" value="supprimer">';
  $trProjet .= '</form>';
  $trProjet .= '</td>';

  $trProjet .= '</tr>';

}

// section catégories

$recupDBCategorie = recuperationDb('categories');

$trCategorie = '';

foreach ($recupDBCategorie as $key => $value) {

  $trCategorie .= '<tr>';

  $trCategorie .= '<td>';
  $trCategorie .= '<p>'.$value['categorie'].'</p>';
  $trCategorie .= '</td>';

  // $trCategorie .= '<td>';
  // $trCategorie .= '<form action="modif_categorie.php" method="post">';
  // $trCategorie .= '<input type="hidden" name="id" value="'.$value['categorie'].'">';
  // $trCategorie .= '<input type="submit" value="modifier">';
  // $trCategorie .= '</form>';
  // $trCategorie .= '</td>';
  //
  // $trCategorie .= '<td>';
  // $trCategorie .= '<form action="suprim_categorie.php" method="post">';
  // $trCategorie .= '<input type="hidden" name="id" value="'.$value['categorie'].'">';
  // $trCategorie .= '<input type="submit" value="supprimer">';
  // $trCategorie .= '</form>';
  // $trCategorie .= '</td>';

  $trCategorie .= '</tr>';

}

// section IMG

$recupDBImages = recuperationDb('images');

$trImages = '';

foreach ($recupDBImages as $key => $value) {

  $trImages .= '<tr>';

  $trImages .= '<td>';
  $trImages .= '<p>'.$value['id'].'</p>';
  $trImages .= '</td>';

  $trImages .= '<td>';
  $trImages .= '<img class="img-mini" src="../img/index-slide/'.$value['fichier'].'">';
  $trImages .= '</td>';

  $trImages .= '<td>';
  $trImages .= '<p>'.$value['nom'].'</p>';
  $trImages .= '</td>';

  foreach ($recupDBprojet as $p) {
    if ($p['id'] == $value['id_projet']) {
      $projetImg = $p['nom'];
    }
  }

  $trImages .= '<td>';
  $trImages .= '<p>'.$projetImg.'</p>';
  $trImages .= '</td>';


  $trImages .= '<td>';
  $trImages .= '<form action="suprim_image.php" method="post">';
  $trImages .= '<input type="hidden" name="id" value="'.$value['id'].'">';
  $trImages .= '<input type="submit" value="supprimer">';
  $trImages .= '</form>';
  $trImages .= '</td>';

  $trImages .= '</tr>';

}




// ***************************** construction de la vue
$vue = [];
$vue['projets'] = $trProjet;
$vue['categories'] = $trCategorie;
$vue['images'] = $trImages;


// ********************** vue **************************

require_once('vue/admin.phtml');
