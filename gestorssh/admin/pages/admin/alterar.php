<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if ((isset($_POST["nome"])) and (isset($_POST["email"]))) {

	//Posts
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$site = $_POST["site"];
	$senhaat = $_POST["senhaantiga"];
	$senhanew = $_POST["novasenha"];

	$SQLUser = "select * from admin where id_administrador = '" . $_SESSION['usuarioID'] . "'   ";
	$SQLUser = $conn->prepare($SQLUser);
	$SQLUser->execute();
	if (($SQLUser->rowCount()) > 0) {

		$euadm = $SQLUser->fetch();

		if ($senhaat <> "") {
			if ($senhaat <> $euadm['senha']) {
				echo myalertuser('error', 'Senha muito curta!', '../../home.php?page=admin/dados');
				exit;
			} elseif ($senhaat == $senhanew) {
				echo myalertuser('info', 'As senha devem ser diferentes!', '../../home.php?page=admin/dados');
				exit;
			}
			if (strlen($senhanew) < 5) {
				echo myalertuser('error', 'Senha muito curta!', '../../home.php?page=admin/dados');
				exit;
			}
			$updatesenha = "senha='" . $senhanew . "',";
		}
		$SQLUPUser = "update admin set " . $updatesenha . " nome='" . $_POST['nome'] . "', email='" . $_POST['email'] . "',site='" . $_POST['site'] . "' WHERE id_administrador = '" . $_SESSION['usuarioID'] . "' ";
		$SQLUPUser = $conn->prepare($SQLUPUser);
		$SQLUPUser->execute();
		echo myalertuser('success', 'Alterado com sucesso !', '../../home.php?page=admin/dados');
	} else {
		echo myalertuser('error', 'erro !', '../../home.php?page=admin/dados');
		exit;
	}
} else {
	echo myalertuser('error', 'Erro !', '../../home.php?page=admin/dados');
	exit;
}
//senha