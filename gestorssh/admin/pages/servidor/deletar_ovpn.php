<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if(isset($_GET["id_servidor"])){

$id=anti_sql_injection($_GET['id_servidor']);

$SQLservidor = "SELECT * FROM servidor where id_servidor='".$id."'";
$SQLservidor = $conn->prepare($SQLservidor);
$SQLservidor->execute();

if(($SQLservidor->rowCount()) == 0){

echo myalertuser('error', 'Servidor não foi encontrado', '../../home.php?page=servidor/listar');

}

$SQLSubSSH = "SELECT * FROM ovpn where servidor_id='".$id."'";
$SQLSubSSH = $conn->prepare($SQLSubSSH);
$SQLSubSSH->execute();
if(($SQLSubSSH->rowCount()) > 0){
$arquivo = $SQLSubSSH->fetch();


$arquivo = "../servidor/ovpn/".$arquivo['arquivo']."";
if (!unlink($arquivo))
{
echo myalertuser('error', 'Arquivo APK não foi encontrado', '../../home.php?page=servidor/servidor&id_servidor='.$id.' ');

$deletando = "DELETE FROM ovpn where servidor_id='".$id."'";
$deletando = $conn->prepare($deletando);
$deletando->execute();
}
else
{

echo myalertuser('success', 'O Arquivo APK foi Removido', '../../home.php?page=servidor/servidor&id_servidor='.$id.' ');

$deletando = "DELETE FROM ovpn where servidor_id='".$id."'";
$deletando = $conn->prepare($deletando);
$deletando->execute();
}




}else{
echo myalertuser('error', 'Nenhum Arquivo APK encontrado neste Servidor', '../../home.php?page=servidor/servidor&id_servidor='.$id.'');
}





}



?>