<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if (isset($_POST['idsubrev'])) {

    $revendedor = $_POST['idsubrev'];
    $tipo = $_POST['tipo'];
    $msg = $_POST['msg'];

    //verifica revendedor
    $SQLrev = "SELECT * FROM usuario where id_usuario='" . $revendedor . "' and subrevenda='sim' and id_mestre='" . $_SESSION['usuarioID'] . "'";
    $SQLrev = $conn->prepare($SQLrev);
    $SQLrev->execute();

    if ($SQLrev->rowCount() <= 0) {
        echo myalertuser('error', 'Nao encontrado !', '../../home.php?page=subrevenda/revendedores');
        exit;
    }
    $revenda = $SQLrev->fetch();

    if ($revenda['tipo'] <> 'revenda') {
        echo myalertuser('error', 'Usuario invalido !', '../../home.php?page=subrevenda/revendedores');
        exit;
    }

    switch ($tipo) {
        case 1:
            $tipo = 'subrevenda';
            break;
        case 2:
            $tipo = 'outros';
            break;
        default:
            $tipo = 'erro';
            break;
    }

    if ($tipo == 'erro') {
        echo myalertuser('error', 'Selecione o tipo !', '../../home.php?page=subrevenda/revendedores');
        exit;
    }


    //Insere notificacao
    $usuarion = $revenda['id_usuario'];
    $msg = $msg;
    $notins = "INSERT INTO notificacoes (usuario_id,data,tipo,mensagem) values ('" . $usuarion . "','" . date('Y-m-d H:i:s') . "','" . $tipo . "','" . $msg . "')";
    $notins = $conn->prepare($notins);
    $notins->execute();


    echo myalertuser('success', 'Notificado com sucesso !', '../../home.php?page=subrevenda/revendedores');
}
