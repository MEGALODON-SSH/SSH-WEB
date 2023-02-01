<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if (isset($_POST['chamado'])) {

	#Posts
	$chamado = $_POST['chamado'];
	$msg = $_POST['msg'];
	$diretorio = $_POST['diretorio'];

	$buscachamado = "SELECT * FROM chamados where id='" . $chamado . "'";
	$buscachamado = $conn->prepare($buscachamado);
	$buscachamado->execute();

	if ($buscachamado->rowCount() == 0) {
		echo myalertuser('error', 'Nao encontrado !', $diretorio);
		exit;
	}
	$chama = $buscachamado->fetch();
	if ($chama['status'] == 'encerrado') {
		echo myalertuser('warning', 'Ja resolvido !', $diretorio);
		exit;
	}
	$verificausuario = "SELECT * FROM usuario where id_usuario= '" . $chama['usuario_id'] . "'";
	$verificausuario = $conn->prepare($verificausuario);
	$verificausuario->execute();
	if ($buscachamado->rowCount() == 0) {
		echo myalertuser('error', 'Nao encontrado !', $diretorio);
		exit;
	}
	//Sucesso
	$updatechamado = "UPDATE chamados set status='resposta', resposta='" . $msg . "', data='" . date('Y-m-d H:i:s') . "' where id= '" . $chama['id'] . "'";
	$updatechamado = $conn->prepare($updatechamado);
	$updatechamado->execute();

	//Insere notificacao
	$msg = "O Seu Chamado de <b><small>N°" . $chama['id'] . "</small></b> Foi Respondido pelo Administrador";
	$notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem) values ('" . $chama['usuario_id'] . "','" . date('Y-m-d H:i:s') . "','chamados','Admin','" . $msg . "')";
	$notins = $conn->prepare($notins);
	$notins->execute();

	echo myalertuser('success', 'Respondido com sucesso !', $diretorio);
}
