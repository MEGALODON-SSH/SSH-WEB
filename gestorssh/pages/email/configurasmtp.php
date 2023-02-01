<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if(isset($_POST['servidor'])){

    $servidor=anti_sql_injection($_POST['servidor']);
    $empresa=anti_sql_injection($_POST['nomeempresa']);
    $porta=anti_sql_injection($_POST['porta']);
    $email=anti_sql_injection($_POST['email']);
    $senha=anti_sql_injection($_POST['senha']);
    $ssl=anti_sql_injection($_POST['ssl']);

    if(!is_numeric($porta)){
        echo myalertuser('error', 'Defina a porta corretamente!', '../../home.php?page=email/enviaremail');
        exit;
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $email=$email;
    }
    else
    {
        echo myalertuser('error', 'Email invalido!', '../../home.php?page=email/enviaremail');
        exit;
    }

    $buscasmtp = "SELECT * FROM smtp_usuarios WHERE usuario_id='".$_SESSION['usuarioID']."'";
    $buscasmtp = $conn->prepare($buscasmtp);
    $buscasmtp->execute();
    $conta=$buscasmtp->rowCount();
    if($conta>0){
        $smtp = $buscasmtp->fetch();
        $updatesmtp = "update smtp_usuarios set servidor='".$servidor."', empresa='".$empresa."', porta='".$porta."', email='".$email."', senha='".$senha."', ssl_secure='".$ssl."' WHERE usuario_id='".$smtp['usuario_id']."'";
        $updatesmtp = $conn->prepare($updatesmtp);
        $updatesmtp->execute();

        echo myalertuser('success', 'SMTP Atualizado !', '../../home.php?page=email/enviaremail');
        exit;

    }else{
        $updatesmtp = "insert into smtp_usuarios (usuario_id,servidor,empresa,porta,email,senha) values ('".$_SESSION['usuarioID']."','".$servidor."','".$empresa."','".$porta."','".$email."','".$senha."')";
        $updatesmtp = $conn->prepare($updatesmtp);
        $updatesmtp->execute();
        echo myalertuser('success', 'SMTP Configurado !', '../../home.php?page=email/enviaremail');
        exit;
    }
}


?>