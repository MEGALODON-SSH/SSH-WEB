<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if (isset($_POST['msg'])) {

    $clientes = anti_sql_injection($_POST['clientes']);
    $msg = anti_sql_injection($_POST['msg']);


    $SQLeu = "SELECT * FROM usuario where id_usuario='" . $_SESSION['usuarioID'] . "'";
    $SQLeu = $conn->prepare($SQLeu);
    $SQLeu->execute();
    $eu = $SQLeu->fetch();

    if ($eu['tipo'] == 'vpn') {
        echo myalertuser('error', 'Sem autorizacao !', '../../home.php');
        exit;
    }

    if ($eu['subrevenda'] == 'sim') {
        $clientes = 3;
    }

    switch ($clientes) {
        case 1:
            $cliente = 'todos';
            break;
        case 2:
            $cliente = 'revenda';
            break;
        case 3:
            $cliente = 'vpn';
            break;
        default:
            $cliente = 'erro';
            break;
    }

    $tipo = 'outros';

    if ($cliente == 'erro') {
        echo myalertuser('error', 'Erro no tipo de cliente !', '../../home.php');
        exit;
    }

    //verifica clientes
    if ($cliente == 'todos') {
        $SQLcli = "SELECT * FROM usuario where id_mestre='" . $_SESSION['usuarioID'] . "'";
    } else {
        $SQLcli = "SELECT * FROM usuario where tipo='" . $cliente . "' and id_mestre='" . $_SESSION['usuarioID'] . "'";
    }
    $SQLcli = $conn->prepare($SQLcli);
    $SQLcli->execute();

    if ($SQLcli->rowCount() > 0) {

        while ($row = $SQLcli->fetch()) {
            //Insere notificacao
            $msg = $msg;
            $notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem) values ('" . $row['id_usuario'] . "','" . date('Y-m-d H:i:s') . "','" . $tipo . "','Admin','" . $msg . "')";
            $notins = $conn->prepare($notins);
            $notins->execute();
        }
    }

    echo myalertuser('success', 'Notificado com sucesso !', '../../home.php');
} else {
    echo "teste";
}
