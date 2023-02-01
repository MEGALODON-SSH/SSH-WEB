<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if(isset($_POST["servidor"])){
	#Post
	$servidor=anti_sql_injection($_POST["servidor"]);
	$revendedor=anti_sql_injection($_POST["revendedor"]);
	$dias=anti_sql_injection($_POST["dias"]);
	$limite=anti_sql_injection($_POST["limite"]);

	if(!is_numeric($limite)){
		echo myalertuser('warning', 'Digite um número!', '../../home.php?page=usuario/addservidor');
		exit;
	}
	if(!is_numeric($dias)){
		echo myalertuser('warning', 'Digite um número valido!', '../../home.php?page=usuario/addservidor');
		exit;
	}
	if($dias<=0){
		echo myalertuser('warning', 'Digite um número valido!', '../../home.php?page=usuario/addservidor');
		exit;
	}

	$buscaeu = "SELECT * FROM usuario where id_usuario= '".$revendedor."'";
	$buscaeu = $conn->prepare($buscaeu);
	$buscaeu->execute();

	if($buscaeu->rowCount()==0){
		echo myalertuser('error', 'Conta nao encontrada !', '../../home.php?page=usuario/addservidor');
		exit;
	}
	$ele=$buscaeu->fetch();


	if($ele['subrevenda']=='sim'){
		echo myalertuser('error', 'Sem permissao de edicao em subrevenda!', '../../home.php?page=usuario/addservidor');
		exit;
	}

	$buscaserver2 = "SELECT * FROM servidor where id_servidor='".$servidor."'";
	$buscaserver2 = $conn->prepare($buscaserver2);
	$buscaserver2->execute();

	if($buscaserver2->rowCount()==0){
		echo myalertuser('error', 'Servidor nao encontrado !', '../../home.php?page=usuario/addservidor');
		exit;
	}

	$served=$buscaserver2->fetch();

	$buscaserver3 = "SELECT * FROM acesso_servidor where id_servidor='".$servidor."' and id_usuario='".$revendedor."'";
	$buscaserver3 = $conn->prepare($buscaserver3);
	$buscaserver3->execute();

	if($buscaserver3->rowCount()>0){
		echo myalertuser('warning', 'Ele já possui esse servidor, Você pode adicionar um outro servidor para Ele!', '../../home.php?page=usuario/addservidor');
		exit;
	}

	$add=date('Y-m-d',strtotime('+ '.$dias.' days'));

	//Sucesso
	$SQLSucesso = "insert into acesso_servidor (id_servidor,id_usuario,qtd,validade) values ('".$servidor."','".$revendedor."','".$limite."','".$add."')";
	$SQLSucesso = $conn->prepare($SQLSucesso);
	$SQLSucesso->execute();


	//Insere notificacao
	$msg="O Admin Adicionou um Servidor a sua conta <b>".$served['nome']."</b> Limite: ".$limite." Validade: ".$dias." dias";
	$notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem) values ('".$revendedor."','".date('Y-m-d H:i:s')."','revenda','Admin','".$msg."')";
	$notins = $conn->prepare($notins);
	$notins->execute();

	echo myalertuser('success', 'Adicionado com sucesso !', '../../home.php?page=usuario/addservidor');

}
