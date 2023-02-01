<?php

require_once('seguranca.php');
require_once('config.php');
require_once('funcoes.php');
require_once('classe.ssh.php');
require_once('funcoes.system.php');

$diretorio = "../../index.php";

if (
    (((isset($_POST["owner"])) && (isset($_POST["servidor"])))  ||
        ((isset($_POST["owner"])) && (isset($_POST["acesso_servidor"])))) and

    (isset($_POST["login_ssh"])) and
    (isset($_POST["senha_ssh"])) and
    (isset($_POST["dias"]) && is_numeric($_POST["dias"])) and
    (isset($_POST["acessos"]) && is_numeric($_POST["acessos"]))  and
    (isset($_POST["diretorio"])) and
    (isset($_POST["usuario"]))
) {

    $quantidade_ssh = 0;
    $valida = 0;
    $acesso_servidor = $_POST['acesso_servidor'];
    $limite_servidor = "";
    $owner = $_POST['owner'];
    $usuario_id = $_POST['usuario'];
    $login_ssh = $_POST['login_ssh'];
    $senha_ssh = $_POST['senha_ssh'];
    $dias = $_POST['dias'];
    $acessos = $_POST['acessos'];
    $diretorio =  $_POST['diretorio'];
    $tempo =  $_POST['tempuser'];
    $tipoconta = $_POST['tipoconta'];

    if ($owner != $accessKEY) {
        protegePagina("user");

        $contas_ssh_criadas = 0;
        //Carrega acesso ao servidor
        $SQLAcessoServidor = "SELECT * FROM acesso_servidor WHERE id_acesso_servidor = '" . $acesso_servidor . "' and id_usuario = '" . $owner . "' ";
        $SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
        $SQLAcessoServidor->execute();
        $servidor_usuario = $SQLAcessoServidor->fetch();
        $limite_servidor = $servidor_usuario['qtd'];

        //Carrega servidor
        $SQLServidor = "SELECT * FROM servidor WHERE id_servidor = '" . $servidor_usuario['id_servidor'] . "'  ";
        $SQLServidor = $conn->prepare($SQLServidor);
        $SQLServidor->execute();
        $servidor = $SQLServidor->fetch();

        //Verifica se está em manutenção
        if ($servidor['manutencao'] == 'sim') {
            echo myalertuser('warning', 'Servidor em manutenção no momento', $diretorio);
            exit;
        }

        //Carrega usuario que chamou
        $SQLUsuario = "SELECT * FROM usuario WHERE id_usuario = '" . $owner . "'";
        $SQLUsuario = $conn->prepare($SQLUsuario);
        $SQLUsuario->execute();
        $usuario = $SQLUsuario->fetch();
        //Carrega contas SSH dele "acessos"
        $SQLContasSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $servidor['id_servidor'] . "' and id_usuario='" . $usuario['id_usuario'] . "' ";
        $SQLContasSSH = $conn->prepare($SQLContasSSH);
        $SQLContasSSH->execute();
        $SQLContasSSH = $SQLContasSSH->fetch();
        $contas_ssh_criadas += $SQLContasSSH['quantidade'];

        //Carrega usuario sub
        $SQLUsuarioSub = "SELECT * FROM usuario WHERE id_mestre ='" . $usuario['id_usuario'] . "'";
        $SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
        $SQLUsuarioSub->execute();

        //soma
        $resta = $limite_servidor - $contas_ssh_criadas;

        if ($limite_servidor < ($contas_ssh_criadas + $acessos)) {
            echo myalertuser('info', 'Voce nao tem limites no servidor selecionado!\n Resta: ' . $resta . ' acessos', $diretorio);
            exit;
        }
    } else if ($owner == $accessKEY) {
        protegePagina("admin");
        $owner = 0;

        //Carrega servidor
        $SQLServidor = "SELECT * FROM servidor WHERE id_servidor = '" . $_POST["servidor"] . "'  ";
        $SQLServidor = $conn->prepare($SQLServidor);
        $SQLServidor->execute();
        $servidor = $SQLServidor->fetch();

        //Verifica se está em manutenção
        if ($servidor['manutencao'] == 'sim') {
            echo myalertuser('warning', 'Servidor em manutenção no momento', $diretorio);
            exit;
        }
    }

    if (!empty($tempo)) {
        $diasex = '2';
    } else {
        $diasex = $dias;
    }

    $ip_servidorSSH = $servidor['ip_servidor'];
    $loginSSH = $servidor['login_server'];
    $senhaSSH =  $servidor['senha'];
	$nome_servidorSSH = $servidor['nome'];

    $ssh = new SSH2($ip_servidorSSH);
    $ssh->auth($loginSSH, $senhaSSH);
    if($tipoconta == 'v2ray') {
        $ssh->exec("/opt/sshplus/plugin-sync --create_user " . $login_ssh . " " . $senha_ssh . " " . $acessos . " " . $diasex . " 'v2ray'");
        $msg = $ssh->output();
        $myar = explode(' ', $msg);
        $mensagem = $myar[0];
        $vlink = $myar[1];
        if($msg == 16){
            echo myalertuser('error', 'V2Ray off no servidor !', $diretorio);
            exit();
        }
        //exit($vlink);
    }else{
        $ssh->exec('[[ -f "/opt/sshplus/sshplus" ]] && /opt/sshplus/plugin-sync --create_user '. $login_ssh . ' ' . $senha_ssh . ' ' . $acessos . ' ' . $diasex . ' || ./criarusuario.sh '. $login_ssh . ' ' . $senha_ssh . ' ' . $acessos . ' ' . $diasex . '');
        $mensagem = $ssh->output();
    }
	
    //echo $mensagem;


    if ($mensagem == 13 or $mensagem == 15) {
        $dias_acesso = $dias;
        $expira = date('Y-m-d', strtotime(' + ' . $dias_acesso . '  days'));
        $SQLContaSSH = "INSERT INTO usuario_ssh (status, id_usuario, id_servidor, login, senha,  data_validade, acesso)
		VALUES ('1', '" . $usuario_id . "', '" . $servidor['id_servidor'] . "', '" . $login_ssh . "', '" . $senha_ssh . "', '" . $expira . "', '" . $acessos . "' )";

        $SQLContaSSH = $conn->prepare($SQLContaSSH);
        $SQLContaSSH->execute();

        //Insere notificacao

        $msg = "Conta criada <small><b>" . $login_ssh . "</b></small> Validade <small><i><b>" . $_POST["dias"] . " Dias</b></i></small>  !";
        $notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem,info_outros) values ('" . $usuario_id . "','" . date('Y-m-d H:i:s') . "','conta','n/d','" . $msg . "','Conta Criada')";
        $notins = $conn->prepare($notins);
        $notins->execute();

        if (!empty($tempo)) {
            $SQLUserSSH = "SELECT * FROM usuario_ssh WHERE login = '" . $login_ssh . "'";
            $SQLUserSSH = $conn->prepare($SQLUserSSH);
            $SQLUserSSH->execute();
            $Userssh = $SQLUserSSH->fetch();
            $iduserssh = $Userssh['id_usuario_ssh'];
            $mytime = strtotime("+$tempo minutes");
            exec("echo $mytime:$iduserssh > /var/tmp/$login_ssh.painel");
        }
    }

    switch ($mensagem) {
        case 0:  
            echo myalertuser('error', 'Este login ja existe!', $diretorio);
            break;

        case 1:
            echo myalertuser('error', 'Este login ssh e invalido!', $diretorio);
            break;

        case 2:
            echo myalertuser('error', 'Nome de usuario muito curto!', $diretorio);
            break;

        case 3:
            echo myalertuser('error', 'Nome de usuario muito grande!', $diretorio);
            break;

        case 4:
            echo myalertuser('error', 'Campo Login esta vazio!', $diretorio);
            break;

        case 5:
            echo myalertuser('error', 'Campo Senha esta vazio!', $diretorio);
            break;

        case 6:
            echo myalertuser('error', 'Senha muito curta!', $diretorio);
            break;

        case 7:
            echo myalertuser('error', 'Dias invalido!', $diretorio);
            break;

        case 8:
            echo myalertuser('error', 'Dias vazio!', $diretorio);
            break;

        case 9:
            echo myalertuser('error', 'Dias deve ser maior que zero!', $diretorio);
            break;

        case 10:
            echo myalertuser('error', 'Acessos invalido!', $diretorio);
            break;

        case 11:
            echo myalertuser('error', 'Campo limite vazio', $diretorio);
            break;

        case 12:
            echo myalertuser('error', 'Campo limite deve ser maior que zero ', $diretorio);
            break;

        case 13:
            if(empty($tempo)){
                echo info_alert($nome_servidorSSH, $login_ssh, $senha_ssh, $acessos, "$diasex dias", $diretorio);
            } else {
                echo info_alert($nome_servidorSSH, $login_ssh, $senha_ssh, $acessos, 'Alguns instantes', $diretorio);
            }
            break;

        case 14:
            echo myalertuser('error', 'Erro ao criar usuario!', $diretorio);
            break;

        case 15:
		    if(empty($tempo)){
            echo info_alertv2($nome_servidorSSH, $login_ssh, $senha_ssh, $acessos, "$diasex dias", $vlink, $diretorio);
			} else {
			echo info_alertv2($nome_servidorSSH, $login_ssh, $senha_ssh, $acessos, 'Alguns instantes', $vlink, $diretorio);
			}
            break;

        default:
            echo myalertuser('info', 'Houve um erro inesperado, tente novamente!', $diretorio);
            break;
    }
} else {
    echo myalertuser('info', 'Preencha todos os campos corretamente!', $diretorio);
    exit;
}

?>
