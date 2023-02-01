<?php
require_once('seguranca.php');
require_once('config.php');
require_once('classe.ssh.php');
require_once('funcoes.php');
$data = date("Y-m-d");


$SQLSservidor = "select * from servidor  ";
$SQLSservidor = $conn->prepare($SQLSservidor);
$SQLSservidor->execute();

if(($SQLSservidor->rowCount()) > 0){
    while($row = $SQLSservidor->fetch()){
        //Realiza a comunicacao com o servidor
        $ip_servidor= $row['ip_servidor'];
        $loginSSH= $row['login_server'];
        $senhaSSH=  $row['senha'];
        $ssh = new SSH2($ip_servidor);

        //Verifica se o servidor esta online
        $servidor_online = $ssh->online($ip_servidor);
        if (!($servidor_online) ){
            $mensagem = "O Servidor ".$row['nome']." IP->".$row['ip_servidor']." não respondeu a comunicacao SSH!!";
            echo "Servidor ".$row['nome']." esta off!<br>";
        }else{
            echo "Servidor online!";
        }
    }
}

?>