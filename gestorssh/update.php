<?php
include_once 'config.php';
include_once 'lib/Database/Connection.php';

$conn = Connection::getConn();

function config($name)
{
    global $conn;
    $sql = $conn->query("SELECT valor FROM configs WHERE nome='$name'")->fetch();
    return $sql['valor'];
}

header('Content-Type: application/json; charset=utf-8');

$servidores = array();
$payloads = array();
$portas = array();

$sql = $conn->query("SELECT Name, TYPE, FLAG, ServerIP, CheckUser, ServerPort, SSLPort, USER, PASS FROM servidores");

while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    $servidores[] = $row;
}

$sql = $conn->query("SELECT Name, FLAG, Payload, SNI, TlsIP, ProxyIP, ProxyPort, Info FROM payloads");

while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    $payloads[] = $row;
}

$sql = $conn->query("SELECT Porta FROM portas");

while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    $portas[] = $row;
}

$dados = array(
    'Version' => config('versao'),
    'ReleaseNotes' => config('notas'),
    'Sms' => config('sms'),
    'UrlUpdate' => config('update'),
    'EmailFeedback' => config('email'),
    'UrlContato' => config('contato'),
    'UrlTermos' => config('termos'),
    'CheckUser' => config('checkuser'),
    'Udp' => $portas,
    'Servers' => $servidores,
    'Networks' => $payloads,
);


$dados = json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$pronto = str_replace('\\', '', $dados);
echo $pronto;

if(isset($_GET['download'])){

    if(!$_SESSION['login']){
        header('location: /');
    }

    $config = 'config.json';

    //verifica se já tem
    if(file_exists($config)){
        unlink($config);
    }

    file_put_contents($config, $pronto);

    header('Content-disposition: attachment; filename=config.json');
    header('Content-type: application/json');

    
}