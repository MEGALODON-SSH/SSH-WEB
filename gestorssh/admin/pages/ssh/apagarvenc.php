<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.php");
require_once("../../../pages/system/classe.ssh.php");

if(isset($_POST['servidor'])){

$serverid=sql_injector($_POST['servidor']);
$dias=$_POST['tiradias'];

if(!is_numeric($dias)){
	                    echo '<script type="text/javascript">';
		                echo 	'alert("Numeros de dias invalidos!");';
		            	echo	'window.location="../../home.php?page=ssh/contas";';
		             	echo '</script>';
		            	exit;
}

// procura servidor
$SQLUsuarioserver = "select * from servidor WHERE tipo='premium' and id_servidor = '".$serverid."'";
$SQLUsuarioserver = $conn->prepare($SQLUsuarioserver);
$SQLUsuarioserver->execute();
$server=$SQLUsuarioserver->fetch();

  if($SQLUsuarioserver->rowCount()==0){
						echo '<script type="text/javascript">';
		                echo 	'alert("Servidor não encontrado!");';
		            	echo	'window.location="../../home.php?page=ssh/contas";';
		             	echo '</script>';
		            	exit;

  }
                // verifica se tem usuarios e faz loop
                $SQLUsuarioSSH = "select * from usuario_ssh WHERE servidor = '".$serverid."'";
                $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                $SQLUsuarioSSH->execute();

                if($SQLUsuarioSSH->rowCount()==0){
						echo '<script type="text/javascript">';
		                echo 	'alert("Servidor não encontrado!");';
		            	echo	'window.location="../../home.php?page=ssh/contas";';
		             	echo '</script>';
		            	exit;

               }

               // Faz loop
               $i=0;
               while($usuario=$SQLUsuarioSSH->fetch()){
               $dataatual=date('Y-m-d H:i:s');
               $resultado=$usuario['validade'];

               if($dias>0){
               $datauser=$usuario['validade'];
               $novadata=date('Y-m-d H:i:s', strtotime('- '.$dias.' days', strtotime($datauser)));
               $resultado=$novadata;
               }

               // Verifica se Venceu

               if($dataatual>=$resultado){
               $i++;

               $SQLSSHfinal = "update usuario_ssh set status='3', apagar='3', id_usuario='0' WHERE id_usuario_ssh = '".$usuario['id']."'  ";
               $SQLSSHfinal = $conn->prepare($SQLSSHfinal);
               $SQLSSHfinal->execute();

                        echo '<script type="text/javascript">';
		                echo 	'alert("Contas Vencidas Excluidas : '.$i.'!");';
		            	echo	'window.location="../../home.php?page=ssh/contas";';
		             	echo '</script>';


               }




               }









}






?>