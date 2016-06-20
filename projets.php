<?php

// --------------------------------------------------- model ----------------------------

require_once('init.php');


// --------------------------------------------------- controleur ----------------------------
if (!isset($_GET['projet']) || !is_numeric($_GET['projet'])) {
  header('Location: index.php');
  exit;
}

//------------------récupérer le projet
$tab_projets = recuperationDb('projets');

foreach ($tab_projets as $projets) {
  if ($projets['id'] == $_GET['projet']) {
    $projet = $projets;
  }
}

// si projet inconu
if (!isset($projet)) {
  header('Location: index.php');
  exit;
}

// si un lien de redirection existe en base
if ($projet['redirection'] != '') {
  header('Location: '.$projet['redirection']);
  exit;
}

//------------------récupérer les images et générer le slider en HTML
$img_slider = '';
$tab_img = recuperationDb('images');
foreach ($tab_img as $images) {
  if ($projet['id'] == $images['id_projet']) {
    $img_slider .= '<li><img src="img/index-slide/'.$images['fichier'].'" alt="" /></li>';
  }
}



// -----------------construction de la vue
$vue = [];
$vue['title'] = '- projet';
$vue['nav'] = '';
$vue['h2'] = $projet['nom'];
$vue['categorie'] = $projet['categorie'];
$vue['description'] = textArrange($projet['description']);
$vue['techno'] = $projet['techno'];
$vue['statut'] = $projet['statut'];
$vue['slider'] = $img_slider;

// --------------------------------------------------- vue ----------------------------

require_once('vue/projets.phtml');
