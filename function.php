<?php
//------------------------------------------------------------------------------------------------ BASE DE DONNE

function recuperationDb($nomTable){
  global $db;
  $requetePDO = $db->query('SELECT *
                            FROM '.$nomTable);
  $tabResultat = $requetePDO->fetchAll();


  return $tabResultat;
}
// ----------------------------------------------------------------------------------------------------- TEXT

function textArrange($text){
  return str_replace('|', '<br>', $text);
}
