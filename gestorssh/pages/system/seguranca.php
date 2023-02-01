<?php
require_once("funcoes.php");
require_once('pass.php');

$_SG['conectaServidor'] = true;
// Abre uma conexão com o servidor MySQL?
$_SG['abreSessao'] = true;
// Inicia a sessão com um session_start()?
$_SG['caseSensitive'] = true;
// Usar case-sensitive?
$_SG['validaSempre'] = true;
// Deseja validar o usuário e a senha a cada carregamento de página?

// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.
$_SG['servidor'] = 'localhost';
// Servidor MySQL
$_SG['usuario'] = 'root';
// Usuário MySQL
$_SG['senha'] = $pass;
// Senha MySQL
$_SG['banco'] = 'sshplus';
// Banco de dados MySQL
$_SG['paginaLogin'] = 'login.php';
// Página de login
$_SG['paginaBloquear'] = 'tela-bloqueada.php';
//Página de Bloqueio

// ======================================
//   ~ Nao edite a partir deste ponto ~
// ======================================
// Verifica se precisa fazer a conexao com o MySQL
if ($_SG['conectaServidor'] == true) {
    try {
        $conn = new PDO('mysql:host='.$_SG['servidor'].';dbname='.$_SG['banco'].';charset=utf8', $_SG['usuario'], $_SG['senha'],
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}

function my_Sql_regcase($str)
{
    $res = "";
    $chars = str_split($str);
    foreach ($chars as $char) {
        if (preg_match("/[A-Za-z]/", $char)) {
            $res .= "[" . mb_strtoupper($char, 'UTF-8') . mb_strtolower($char, 'UTF-8') . "]";
        } else {
            $res .= $char;
        }
    }
    return $res;
}

// ======================================
//  Anti SQL Injector
// ======================================

function sql_injector($sql)
{
    $seg = preg_replace(my_Sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql); //remove palavras que contenham a sintaxe sql
    $seg = trim($seg); //limpa espaaos vazios
    $seg = strip_tags($seg); // tira tags html e php
    $seg = addslashes($seg); //adiciona barras invertidas a uma string
    return $seg;
}

// ======================================
//  Pegar IP
// ======================================

function pega_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}


function validaUsuario($usuario, $senha,$tipo) {

    global $_SG;
    /* Inicia a sessao */
    session_start();

    $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
    // Usa a funco addslashes para escapar as aspas
    $login_usuario = addslashes($usuario);
    $senha_usuario = addslashes($senha);

    if($tipo=="admin"){
        // Monta uma consulta SQL (query) para procurar um usuario
        $sql = "SELECT * FROM admin WHERE login = '".$login_usuario."' AND senha = '".$senha_usuario."' LIMIT 1";

    }else{
        // Monta uma consulta SQL (query) para procurar um usuario
        $sql = "SELECT * FROM usuario WHERE login = '".$login_usuario."' AND senha = '".$senha_usuario."' LIMIT 1";
    }

    global $conn;
    $sql = $conn->prepare($sql);
    $sql->execute();
    $resultado = $sql->fetch();

    // Verifica se encontrou algum registro
    if (empty($resultado)) {
        // Nenhum registro foi encontrado => o usuario e invalido
        return false;
    } else {

        if($tipo=="admin"){
            // Definimos dois valores na sessao com os dados do usuurio
            $_SESSION['usuarioID'] = $resultado['id_administrador']; // Pega o valor da coluna 'id do registro encontrado no MySQL
            $_SESSION['usuarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
            $_SESSION['tipo'] = 'admin';
            $_SESSION['usuarioLogin'] = $resultado['login'];
            $_SESSION['usuarioSenha'] = $resultado['senha'];

        }else{
            // Definimos dois valores na sessao com os dados do usuurio
            $_SESSION['usuarioID'] = $resultado['id_usuario']; // Pega o valor da coluna 'id do registro encontrado no MySQL
            $_SESSION['usuarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
            $_SESSION['usuarioLogin'] = $resultado['login'];
            $_SESSION['usuarioSenha'] = $resultado['senha'];
            $_SESSION['tipo'] = 'user'; // Pega o valor da coluna 'id do registro encontrado no MySQL

        }
        return true;
    }
}

function protegePagina($tipo) {
    global $_SG;

    /* Inicia a sessao */
    session_start();
    if (!isset($_SESSION['usuarioID']) or !isset($_SESSION['usuarioNome'])) {
        // Nao ha usuurio logado, manda pra pagina de login
        expulsaVisitante();
    } else if (!isset($_SESSION['usuarioID']) or !isset($_SESSION['usuarioNome'])) {

        // Ha usuurio logado, verifica se precisa validar o login novamente
        if ($_SG['validaSempre'] == true) {

            if($_SESSION['tipo']=="admin"){
                // Verifica se os dados salvos na sessao batem com os dados do banco de dados
                if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'], "admin")) {
                    // Os dados n�o batem, manda pra tela de login
                    expulsaVisitante();
                }

            }else{
                // Verifica se os dados salvos na sessao batem com os dados do banco de dados
                if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'], "user")) {
                    // Os dados nao batem, manda pra tela de login
                    expulsaVisitante();
                }
            }

        }
    }
}


function expulsaVisitante() {
    global $_SG;
    /* Inicia a sessao */
    session_start();
    // Remove as variaveis da sessao (caso elas existam)
    unset($_SESSION['usuarioID'], $_SESSION['usuarioNome'], $_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
    // Manda pra tela de login
    header("Location: index.php");
}

function expulsaSair() {
    session_start();
    global $_SG;

    // Remove as variaveis da sessao (caso elas existam)
    unset($_SESSION['usuarioID'], $_SESSION['usuarioNome'], $_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
    // Finally, destroy the session.
    session_destroy();
    // Manda pra tela de login
    header("Location: ../index.php");
}
?>