<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'TlgTools.php';


echo $geral->install($_GET['install']);

?>