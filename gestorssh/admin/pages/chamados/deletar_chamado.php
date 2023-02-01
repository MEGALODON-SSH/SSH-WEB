<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if (isset($_POST['chamado'])) {

	#Posts
	$chamado = $_POST['chamado'];
	$diretorio = $_POST['diretorio'];

	$buscachamado = "SELECT * FROM chamados where id='" . $chamado . "'";
	$buscachamado = $conn->prepare($buscachamado);
	$buscachamado->execute();

	if ($buscachamado->rowCount() == 0) {
		echo myalertuser('error', 'Nao encontrado !', $diretorio);
		exit;
	}
	$chama = $buscachamado->fetch();
	if ($chama['status'] <> 'encerrado') {
		echo myalertuser('warning', 'Encerre-o prmeiro !', $diretorio);
		exit;
	}

	//Sucesso
	$updatechamado = "DELETE FROM chamados where id='" . $chama['id'] . "'";
	$updatechamado = $conn->prepare($updatechamado);
	$updatechamado->execute();

	echo myalertuser('success', 'Deletado com sucesso !', $diretorio);
}
