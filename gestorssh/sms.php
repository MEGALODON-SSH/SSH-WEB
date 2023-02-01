<?php 
include_once 'config.php';
include_once 'lib/Database/Connection.php';

$conn = Connection::getConn();

function config($name)
{
    global $conn;
    $sql = $conn->query("SELECT valor FROM configs WHERE nome='$name'")->fetch();
    return $sql['valor'];
}

?>

{"SendMessage":"<?=config('nsms')?>","MyMessage":"<?=config('msg')?>"}