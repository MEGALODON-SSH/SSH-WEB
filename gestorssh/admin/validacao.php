<?php
require_once("../pages/system/funcoes.php");
require_once("../pages/system/seguranca.php");

$usuario = $_POST['username'];
$senha = $_POST['password'];

if (empty($usuario)) {
  echo '0';
} elseif (empty($senha)) {
  echo '0';
} else {
  // Verifica se um formulário foi enviado
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = (isset($usuario)) ? $usuario : '';
    $senha = (isset($senha)) ? $senha : '';
    // Utiliza uma função criada no seguranca.php pra validar os dados digitados
    if (validaUsuario($usuario, $senha, "admin") == true) {
      echo '1';
    } else {
      echo '0';
    }
  }
}
