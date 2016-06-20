$(function(){

// *********************** fonctions **********************
function clicMenu(){
  if(menuCacher){
    menuCacher = false;
    $('.menu').slideDown("slow");
  }else{
    fermerMenu()
  }
}

function fermerMenu(){
  menuCacher = true;
  $('.menu').slideUp("slow");
}
function recupScrollHaut(){
    var haut = $(window).scrollTop();
  return haut;
}

function recupLarg(){
  if (document.body){
    var larg = (document.body.clientWidth);
  }else{
    var larg = (window.innerWidth);
  }
  return larg;
}

function anim_carte_visite(){
  var larg = recupLarg();
  var logo_width = $('#ancre-logo h1')[0].clientWidth;
  var milieu = (larg / 2 ) - (logo_width / 2) + "px";

  $('#ancre-logo h1').delay( 800 ).animate({
    left : milieu
  },3000,'easeOutElastic');
}
// *********************** variables **********************
var menuCacher = true;

var larg = recupLarg();

var carte_visite = 0;

var mini_carte = 0;


// ------ vérifie la largeur de l'écran
setInterval(function(ev){
  // revérifie la largeur
  var larg = recupLarg();
  var scroll_haut = recupScrollHaut();
  //console.log(scroll_haut);

  //afficher ou pas la mini carte de visite
  if (larg >= 1024 && scroll_haut < 10 ) {
    $('#mini_logo').hide('explode',500);
  }else{
  }



  // si tablette ou + grand on affiche le menu
  if (larg >= 1024) {
    $('.menu').show();
    // si carte de visite = 0, on effectue l'anim de la carte de visite
    if (carte_visite == 0) {
      carte_visite = 1;
      anim_carte_visite();
    }
    //adapter la carte de visite en fonction des test de taille d'écran
    if (carte_visite == 1 ) {
      var larg = recupLarg();
      var logo_width = $('#ancre-logo h1')[0].clientWidth ;
      var milieu = (larg / 2 ) - (logo_width / 2) + "px";
      $('#ancre-logo h1').animate({
        left : milieu
      },800);
    }
  }
},500);

// *********************** evenements **********************


// si on click sur le bouton menu
$('#bouton_menu').on('click',clicMenu);

// si on click sur le bouton qui remonte en haut du site
$('.scroll-haut').on('click',function(ev){
  ev.preventDefault();
  if (larg < 1024) {
    fermerMenu();
  }

  $('html, body').animate({
		scrollTop: 0
	}, 1500);

});

// si on click sur le bouton qui scroll jusqu'a une ancre

$('.scroll-ancre').on('click',function(ev){
  ev.preventDefault();
  var larg = recupLarg();

  // si mobile = fermer le menu
  if (larg < 1024) {
    fermerMenu();
  }else{
    // si tablette et +  = afficher la mini carte de visite

    $('#mini_logo').delay(1500).show('explode',500);
  }

	var le_id = $(this).attr("href");

	$('html, body').animate({
		scrollTop:$(le_id).offset().top - 80
	}, 1500);

});

// si carte de visite = 0 en tablette ou + grand, on effectue l'anim de la carte de visite

if (larg >= 1024 && carte_visite == 0) {
    carte_visite = 1;
    anim_carte_visite();
}


  //----------------------------------------fin du script
});
