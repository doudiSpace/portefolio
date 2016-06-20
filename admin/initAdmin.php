<?php

require_once('../init.php');
require_once('fonctionAdmin.php');

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true ){
  $_SESSION['admin'] = false;
  header('Location: index.php');
  exit;
}
