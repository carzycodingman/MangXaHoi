<?php 
  require_once 'init.php';
  require_once 'functions.php';
  $page = 'logout';
  unset($_SESSION['userid']);
  header('Location: index.php');
?>