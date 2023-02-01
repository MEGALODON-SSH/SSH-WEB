<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");
protegePagina("admin");

if (isset($_GET["id"])) {

    $id = anti_sql_injection($_GET['id']);

    $SQLSubSSH = "SELECT * FROM arquivo_download where id='" . $id . "'";
    $SQLSubSSH = $conn->prepare($SQLSubSSH);
    $SQLSubSSH->execute();
    if (($SQLSubSSH->rowCount()) > 0) {
        $arquivo = $SQLSubSSH->fetch();

        $arquivo = "" . $arquivo['nome_arquivo'] . "";
        if (!unlink($arquivo)) {
            echo myalertuser('error', 'Erro ao localizar arquivo !', '../../home.php?page=download/downloads');
            exit;
        } else {

            $deletando = "DELETE FROM arquivo_download where id='" . $id . "'";
            $deletando = $conn->prepare($deletando);
            $deletando->execute();

            echo myalertuser('success', 'Removido com sucesso !', '../../home.php?page=download/downloads');
        }
    } else {
        echo myalertuser('error', 'Erro ao localizar arquivo !', '../../home.php?page=download/downloads');
        exit;
    }
}
