<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if (isset($_POST['adicionanoticia'])) {
    $titulo = $_POST['titu'];
    $subtitulo = $_POST['subtitu'];
    $msg = $_POST['msg'];

    $procnoticias = "select * FROM noticias where status='ativo'";
    $procnoticias = $conn->prepare($procnoticias);
    $procnoticias->execute();
    if ($procnoticias->rowCount() > 0) {
        echo myalertuser('error', 'Ja existe uma notificacao online !', '../../home.php?page=apis/gerenciar');
        exit;
    }

    if ($titulo == '') {
        echo myalertuser('warning', 'Titulo em branco !', '../../home.php?page=apis/gerenciar');
        exit;
    }
    if ($subtitulo == '') {
        echo myalertuser('warning', 'Sub titulo em branco !', '../../home.php?page=apis/gerenciar');
        exit;
    }
    if ($msg == '') {
        echo myalertuser('warning', 'Mensagem em branco !', '../../home.php?page=apis/gerenciar');
        exit;
    }


    $addnoticias = "insert into noticias (status,titulo,subtitulo,msg,data) values ('ativo','" . $titulo . "','" . $subtitulo . "','" . $msg . "','" . date('Y-m-d H:i:s') . "')";
    $addnoticias = $conn->prepare($addnoticias);
    $addnoticias->execute();

    echo myalertuser('success', 'Notificado com sucesso !', '../../home.php?page=apis/gerenciar');
}
