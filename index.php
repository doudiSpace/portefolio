<?php
// --------------------------------------------------------------------------------------
// --------------------------------------------------- model ----------------------------
// --------------------------------------------------------------------------------------

require_once('init.php');

// --------------------------------------------------------------------------------------
// --------------------------------------------------- controleur -----------------------
// --------------------------------------------------------------------------------------


// ------------------- formulaire de Contact
$error = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['nom'],$_POST['email'],$_POST['objet'],$_POST['message'])){
  //vérification qu'il n'y a pas de champs vide
  foreach ($_POST as $key => $value) {
    if ($value == '') {
      $error = 'Veuillez remplir tous les champs du formulaire.';
    }
  }

  //-------------------------- ENVOI PAR MAIL ------------------------------------

  if($error == ''){
  $nom = str_replace("\n.", "\n..", htmlspecialchars($_POST['nom']));
  $mail = str_replace("\n.", "\n..", htmlspecialchars($_POST['email']));
  $obj = str_replace("\n.", "\n..", htmlspecialchars($_POST['objet']));
  $msg = str_replace("\n.", "\n..", htmlspecialchars($_POST['message']));

  $from = 'MIME-Version: 1.0' . "\r\n";
  $from .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  //$from .= "$mail";

  $subject = "Contact VLH.space : ".$obj;
  $to = $config['adresse_mail'];

  $message = "<strong>Objet : </strong>".$obj."</br>
              <strong>Mail : </strong>".$mail."</br>
              <strong>Nom :</strong>".$nom."</br>
              <strong>Message :</strong>".$msg;

  $message = wordwrap($message, 70, "\r\n");

  $mailSend = mail($to, $subject, $message, $from);
  }
};

// ----------------- générer les variables de la vue
$nav = '
<ul>
  <li><a class="scroll-ancre" id="lien_moi" href="#ancre-moi"><img src="img/presentation.png" alt="" /><h2>Vincent LE HENAFF</h2></a></li>
  <li><a class="scroll-ancre" id="lien_comp" href="#ancre-competences"><img src="img/comp.png" alt="" /><h2>Compétences</h2></h2></a></li>
  <li><a class="scroll-ancre" id="lien_proj" href="#ancre-projets"><img src="img/projets.png" alt="" /><h2>Réalisations</h2></a></li>
  <li><a class="scroll-ancre" id="lien_cont" href="#ancre-contact"><img src="img/contact.png" alt="" /><h2>Contact</h2></a></li>
</ul>

';

$vignettes_projets =  '';
$tab_projets = recuperationDb('projets');

foreach ($tab_projets as $projet) {
  $vignettes_projets .=  '
  <a href="projets.php?projet='.$projet['id'].'" >
    <figure>
      <img src="img/projets/'.$projet['img_mini'].'" alt="'.$projet['nom'].'" />
      <figcaption>
        <h3>'.$projet['nom'].'</h3>
        <br>
        <h4>'.$projet['categorie'].'</h4>
      </figcaption>
    </figure>
  </a>

  ';
}

// -----------------construction de la vue
$vue = [];
$vue['title'] = '- Vincent LE HENAFF';
$vue['nav'] = $nav;
$vue['projets'] = $vignettes_projets;




// --------------------------------------------------------------------------------------
// --------------------------------------------------- vue ------------------------------
// --------------------------------------------------------------------------------------


require_once('vue/index.phtml');
