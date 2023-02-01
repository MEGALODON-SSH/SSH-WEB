<?php
require '../../vendor/autoload.php';
use \phpseclib\Net\SFTP;
use \phpseclib\Net\SSH2;
require_once('seguranca.php');
require_once('config.php');
require_once('funcoes.php');
$data = date("Y-m-d h:i:s");
$data2 = date("Y-m-d");
set_time_limit(0);

	//Pega os dados de todos os servidores no banco de dados
    $SQLSservidor = "SELECT * FROM `servidor` LIMIT 500";
    $SQLSservidor = $conn->prepare($SQLSservidor);
	$SQLSservidor->execute();
	
	//Confirma se o número de servidores é maior que zero
     if(($SQLSservidor->rowCount()) > 0){
	//Se comunica com os servidores em loop, até que tenha sido executado em todos
	        while($row = $SQLSservidor->fetch()){
			//Abre uma conexão com o servidor e tenta autenticar	
			$ip_servidor= $row['ip_servidor'];
		    $loginSSH= $row['login_server'];
			$senhaSSH=  $row['senha'];
			$ssh = new SSH2($ip_servidor);
			$ssh->login($loginSSH,$senhaSSH);
			
			//Verifica se o servidor está online
		   $servidor_online = $ssh->isConnected($ip_servidor);
           if (!($servidor_online) ){
			    $mensagem = "O servidor ".$row['nome']." IP->".$row['ip_servidor']." não respondeu! Não foi possível criar o backup.";
			    //Coloca no banco de dados as informações caso não tenha conseguido se conectar
				$SQLNotfc = "insert into notificacoes (tipo, admin, usuario_id, mensagem, data) 
				                    VALUES ('servidores', 'sim', '1', '".$mensagem."', '".$data."')  ";
                $SQLNotfc = $conn->prepare($SQLNotfc);
                $SQLNotfc->execute();
			echo "
O servidor ".$row['nome']." está offline! Não foi possível tirar o backup.

";
		   }else{
			//Verifica se o script de backup está na máquina e o baixa, caso não esteja.
				echo "
Conexão com: ".$row['nome']."
";
				echo "Iniciando download do script de backup.
";
			   $ssh->exec('if [ -e "/root/backup.sh" ]; then
			   ./backup.sh
else
	apt-get install wget tar dos2unix -y
	wget https://raw.githubusercontent.com/JeanRocha91x/psshplus-/main/gestorssh/backup.sh -O backup.sh
fi');
			//Executa o script de backup e imprime o resultado
			echo "Iniciando execução do script...
";
			$ssh->exec(" chmod 777 *.sh && dos2unix *.sh ");
			$ssh->exec("./backup.sh");
			//Iniciando conexão SFTP com a máquina
				echo "Iniciando conexão SFTP com a máquina...
";
				$sftp = new SFTP($ip_servidor);
				if (!$sftp->login($loginSSH, $senhaSSH)) {
						echo "O Login falhou... Não foi possível concluir o backup no servidor ".$row['nome']."";
					}else{
						echo "Conectado no servidor! Iniciando download...";
			//Iniciando download do arquivo via SFTP
						$bckarq = ('/root/backup.vps');
						if($bckarq) {
							mkdir("/root/backupvps/", 0777, true);
							$destinodoarq="/root/backupvps/".$row['nome']." - ".$data2.".vps";
							$sftp->get($bckarq, $destinodoarq);
							echo "
O servidor ".$row['nome']." teve o backup feito!
";
						}else{
							echo $sftp->getSFTPLog();
							echo "Erro baixando o arquivo do servidor ".$row['nome']."...";
						}
					}
	unset ($ssh, $sftp, $loginSSH, $senhaSSH, $ip_servidor); 
		   }
			}
	 }
?>
