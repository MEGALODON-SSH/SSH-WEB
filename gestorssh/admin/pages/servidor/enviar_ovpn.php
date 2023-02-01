<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");


if(isset($_POST['servidorid'])){

#Posts
$servidoridd=anti_sql_injection($_POST['servidorid']);

// diretório de destino do arquivo
define('DEST_DIR', __DIR__ . '../../../pages/servidor/ovpn');

if (isset($_FILES['arquivo']) && !empty($_FILES['arquivo']['name']))
{
    // se o "name" estiver vazio, é porque nenhum arquivo foi enviado

    // apenas para facilitar, criamos uma variável que é o array com os dados do arquivo
    $arquivo = $_FILES['arquivo'];
    $nomedoarquido = $arquivo['name'];
    $final=anti_sql_injection($nomedoarquido);

$buscasmtp = "SELECT * FROM ovpn WHERE servidor_id='".$servidoridd."'";
$buscasmtp = $conn->prepare($buscasmtp);
$buscasmtp->execute();
$conta=$buscasmtp->rowCount();
$ovpn=$buscasmtp->fetch();
if($conta>0){
echo myalertuser('error', 'Já existe um Arquivo APK neste Servidor', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['servidorid'].' ');
}


    if (!move_uploaded_file($arquivo['tmp_name'], DEST_DIR . '/' . $arquivo['name']))
    {
echo myalertuser('error', 'Ocorreu um Erro no Processo de Upload', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['servidorid'].' ');
    }
    else
    {
    $data=date('Y-m-d H:i:s');

    //Insere notificacao
    $server = "SELECT * FROM servidor where id_servidor='".$servidoridd."'";
    $server = $conn->prepare($server);
    $server->execute();
    $servidor=$server->fetch();


    //Insere notificacao
    $buscauser = "SELECT * FROM usuario_ssh where id_servidor='".$servidor['id_servidor']."'";
    $buscauser = $conn->prepare($buscauser);
    $buscauser->execute();
    if(($buscauser->rowCount()) > 0){
    while($row = $buscauser->fetch()){
    $msg="Foi adicionado um Arquivo APK no Servidor <b>".$servidor['nome']."</b> <small><a href=\"../home.php?page=downloads/ovpn\">Veja</a></small>!";
    $notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem,info_outros) values ('".$row['id_usuario']."','".date('Y-m-d H:i:s')."','outros','n/d','".$msg."','Arquivos')";
    $notins = $conn->prepare($notins);
    $notins->execute();
    }
    }

    //Insere notificacao 2
    $buscauser2 = "SELECT * FROM acesso_servidor where id_servidor='".$servidor['id_servidor']."'";
    $buscauser2 = $conn->prepare($buscauser2);
    $buscauser2->execute();
    if(($buscauser2->rowCount()) > 0){
    while($row2 = $buscauser2->fetch()){
    $msg="Revendedor Foi adicionado um Arquivo APK no Servidor <b>".$servidor['nome']."</b> <small><a href=\"../home.php?page=downloads/ovpn\">Veja</a></small>!";
    $notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem,info_outros) values ('".$row2['id_usuario']."','".date('Y-m-d H:i:s')."','outros','n/d','".$msg."','Arquivos')";
    $notins = $conn->prepare($notins);
    $notins->execute();
    }
    }

    $nome=explode('.',$nomedoarquido);


    //Envia ao banco de dados o arquivo
    $enviando = "insert into ovpn (servidor_id,nome,arquivo,data) values ('".$servidoridd."','".$nome[0]."','".$nomedoarquido."','".$data."')";
    $enviando = $conn->prepare($enviando);
    $enviando->execute();
    echo myalertuser('success', 'O Arquivo APK foi enviado', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['servidorid'].' ');
    }
}else{
echo myalertuser('error', 'Houve algum erro durante o Upload do arquivo', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['servidorid'].' ');
}









}

?>