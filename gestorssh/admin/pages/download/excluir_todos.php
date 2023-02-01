<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

if (isset($_GET["id"])) {

    $SQLSubSSH = "SELECT * FROM arquivo_download";
    $SQLSubSSH = $conn->prepare($SQLSubSSH);
    $SQLSubSSH->execute();
    if (($SQLSubSSH->rowCount()) > 0) {

        while ($arquivo = $SQLSubSSH->fetch()) {

            $arquivo2 = "" . $arquivo['nome_arquivo'] . "";
            unlink($arquivo2);

            $deletando = "DELETE FROM arquivo_download";
            $deletando = $conn->prepare($deletando);
            $deletando->execute();

            echo myalertuser('success', 'Arquivos Removidos !', '../../home.php?page=download/downloads');
        }
    } else {
        echo myalertuser('error', 'Nao foi encontrado arquivos para download !', '../../home.php?page=download/downloads');
        exit;
    }
}
