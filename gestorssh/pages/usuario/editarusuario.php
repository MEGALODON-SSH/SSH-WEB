<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if (isset($_POST["id_usuario"])) {

    #Post
    $usuario = $_POST["id_usuario"];
    $login = $_POST["login"];
    $senha = $_POST["senha"];
    $email = $_POST["email"];
    $celular = $_POST["celular"];
    $diretorio = $_POST["diretorio"];

    $SQLusuario = "select * from usuario where id_usuario = '" . $usuario . "'  ";
    $SQLusuario = $conn->prepare($SQLusuario);
    $SQLusuario->execute();

    if ($SQLusuario->rowCount() == 0) {
        echo myalertuser('error', 'Usuario não encontrado', $diretorio);
        exit;
    }

    $user = $SQLusuario->fetch();

    if ($user['id_mestre'] <> $_SESSION['usuarioID']) {
        echo myalertuser('error', 'Erro, sem permissao!', $diretorio);
        exit;
    }

    if (strlen($login) < 5) {
        echo myalertuser('warning', 'Informe mais de 5 caracteres !', $diretorio);
        exit;
    }

    if (strlen($senha) < 5) {
        echo myalertuser('warning', 'Informe mais de 5 caracteres na senha !', $diretorio);
        exit;
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = $email;
    } else {
        echo myalertuser('error', 'Email invalido !', $diretorio);
        exit;
    }

    #sucesso
    $SQLupdate = "update usuario set login='" . $login . "', senha='" . $senha . "', email='" . $email . "', celular='" . $celular . "', permitir_demo='" . $_POST["acesso"] . "' where id_usuario = '" . $usuario . "'  ";
    $SQLupdate = $conn->prepare($SQLupdate);
    $SQLupdate->execute();

    echo myalertuser('success', 'Dados alterados com sucesso !', $diretorio);
    exit;
}
