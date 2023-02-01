<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if(isset($_POST["servidor"])){

#Post
    $servidor=anti_sql_injection($_POST["servidor"]);
    $subrevendedor=anti_sql_injection($_POST["subusuario"]);
    $dias=anti_sql_injection($_POST["dias"]);
    $limite=anti_sql_injection($_POST["limite"]);

    if(!ctype_digit($limite)){
        echo myalertuser('error', 'Informe um numero valido', '../../home.php?page=subrevenda/adicionar');
        exit;
    }
    if(!ctype_digit($dias)){
        echo myalertuser('error', 'Informe um numero valido', '../../home.php?page=subrevenda/adicionar');
        exit;
    }
    if($dias<=0){
        echo myalertuser('error', 'Informe um numero valido', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    $buscaeu = "SELECT * FROM usuario where id_usuario= '".$_SESSION['usuarioID']."'";
    $buscaeu = $conn->prepare($buscaeu);
    $buscaeu->execute();

    if($buscaeu->rowCount()==0){
        echo myalertuser('error', 'Erro', '../../home.php?page=subrevenda/adicionar');
        exit;
    }
    $eu=$buscaeu->fetch();

    $buscasubusuario = "SELECT * FROM usuario where id_usuario= '".$subrevendedor."' and id_mestre='".$_SESSION['usuarioID']."' and subrevenda='sim'";
    $buscasubusuario = $conn->prepare($buscasubusuario);
    $buscasubusuario->execute();

    if($buscasubusuario->rowCount()==0){
        echo myalertuser('error', 'Subrevendedor nao encontrado', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    if($eu['subrevenda']=='sim'){
        echo myalertuser('error', 'Sem permissao', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    $buscaserver = "SELECT * FROM acesso_servidor where id_acesso_servidor= '".$servidor."' and id_usuario='".$eu['id_usuario']."'";
    $buscaserver = $conn->prepare($buscaserver);
    $buscaserver->execute();

    if($buscaserver->rowCount()==0){
        echo myalertuser('error', 'Sem acessos', '../../home.php?page=subrevenda/adicionar');
        exit;
    }
    $meuservidor=$buscaserver->fetch();

    $buscaserver2 = "SELECT * FROM servidor where id_servidor='".$meuservidor['id_servidor']."'";
    $buscaserver2 = $conn->prepare($buscaserver2);
    $buscaserver2->execute();

    if($buscaserver2->rowCount()==0){
        echo myalertuser('error', 'Servidor nao encontrado !', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    $buscaserver3 = "SELECT * FROM acesso_servidor where id_servidor='".$meuservidor['id_servidor']."' and id_usuario='".$subrevendedor."' and id_mestre='".$_SESSION['usuarioID']."'";
    $buscaserver3 = $conn->prepare($buscaserver3);
    $buscaserver3->execute();

    if($buscaserver3->rowCount()>0){
        echo myalertuser('error', 'Ele já possui acesso neste servidor vc so pode editar o servidor dele', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

//Carrega contas SSH criadas
    $SQLContasSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '".$meuservidor['id_servidor']."' and id_usuario='".$eu['id_usuario']."' ";
    $SQLContasSSH = $conn->prepare($SQLContasSSH);
    $SQLContasSSH->execute();
    $SQLContasSSH = $SQLContasSSH->fetch();
    $contas_ssh_criadas += $SQLContasSSH['quantidade'];

    //Carrega usuario sub
    $SQLUsuarioSub = "SELECT * FROM usuario WHERE id_mestre ='".$eu['id_usuario']."' and subrevenda='nao'";
    $SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
    $SQLUsuarioSub->execute();


    if (($SQLUsuarioSub->rowCount()) > 0) {
        while($row = $SQLUsuarioSub->fetch()) {
            $SQLSubSSH= "select sum(acesso) AS quantidade  from usuario_ssh WHERE id_usuario = '".$row['id_usuario']."' and id_servidor='".$meuservidor['id_servidor']."' ";
            $SQLSubSSH = $conn->prepare($SQLSubSSH);
            $SQLSubSSH->execute();
            $SQLSubSSH = $SQLSubSSH->fetch();
            $contas_ssh_criadas += $SQLSubSSH['quantidade'];

        }

    }



    $limiteservidor=$meuservidor['qtd'];

    $soma=$limiteservidor-$contas_ssh_criadas;
    if($soma<=0){
        $soma=0;
    }

    if($limiteservidor==0){
        echo myalertuser('warning', 'Você não possui limites disponivel', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    if( $limiteservidor < ($contas_ssh_criadas+$limite)  ){
        echo myalertuser('warning', 'Você não possui esse limite, Quantidade Permitida: '.$soma.' !', '../../home.php?page=subrevenda/adicionar');
        exit;
    }

    $add=date('Y-m-d',strtotime('+ '.$dias.' days'));

//Sucesso
    $SQLSucesso = "insert into acesso_servidor (id_servidor,id_usuario,id_mestre,id_servidor_mestre,qtd,validade) values ('".$meuservidor['id_servidor']."','".$subrevendedor."','".$_SESSION['usuarioID']."','".$meuservidor['id_acesso_servidor']."','".$limite."','".$add."')";
    $SQLSucesso = $conn->prepare($SQLSucesso);
    $SQLSucesso->execute();

//Sucesso
    $SQLSucesso2 = "update acesso_servidor set qtd=qtd-'".$limite."' where id_acesso_servidor='".$meuservidor['id_acesso_servidor']."'";
    $SQLSucesso2 = $conn->prepare($SQLSucesso2);
    $SQLSucesso2->execute();

    echo myalertuser('success', 'Adicionado com sucesso !', '../../home.php?page=subrevenda/alocados');
    exit;

}
