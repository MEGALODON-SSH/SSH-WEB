<?php
require_once('seguranca.php');
require_once('config.php');
require_once('classe.ssh.php');
require_once('funcoes.php');
$data = date("Y-m-d H:i:s");
echo $data."<br>";
$data_hora_atual = date("Y-m-d H:i:s");


//Historico
$SQLSelectSSH = "TRUNCATE TABLE hist_usuario_ssh_online";
$SQLSelectSSH = "TRUNCATE TABLE `notificacoes`";
$SQLSelectSSH = $conn->prepare($SQLSelectSSH);
$SQLSelectSSH->execute();


?>