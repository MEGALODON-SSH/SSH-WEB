<!DOCTYPE html>
<html class="loading bordered-layout" lang="pt" data-layout="bordered-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/ui-feather.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'> 
    
</head>

<body class="vertical-layout vertical-menu-modern">
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../../../app-assets/js/scripts/ui/ui-feather.js"></script>
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/funcoes.system.php");

protegePagina("admin");

$SQLAdministrador = "SELECT * FROM admin";
$SQLAdministrador = $conn->prepare($SQLAdministrador);
$SQLAdministrador->execute();
$administrador = $SQLAdministrador->fetch();

function geraToken(){
	$salt = "123456ABCDER";
	srand((double)microtime()*1000000); 
	$i = 0;
	$pass = 0;
	while($i <= 7){
		$num = rand() % 10;
		$tmp = substr($salt, $num, 1);
		$pass = $pass . $tmp;
		$i++;

	}
	return $pass;
}
$data =  date("Y-m-d H:i:s");
$token = geraToken();

function alertinfo($status, $msgalert, $dirt)
{
    $myalert = "
        let timerInterval
        Swal.fire({
        icon: '" . $status . "',
        title: '" . $msgalert . "',
        timer: 2000,
        timerProgressBar: true,
        willClose: () => {
            clearInterval(timerInterval)
        }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location='" . $dirt . "';
            } else {
                window.location='" . $dirt . "';
            }
        })
        ";
    $alert = '<script type="text/javascript">' . $myalert . '</script>';
    return $alert;
};

if((isset($_POST["login"])) and (isset($_POST["nome"])) and (isset($_POST["senha"]))  and (isset($_POST["tipo"]))  and (isset($_POST["celular"])) ){
	$acesso = $_POST["acesso"];

	$SQLUsuario = "select * from usuario WHERE login = '".$_POST['login']."' ";
	$SQLUsuario = $conn->prepare($SQLUsuario);
	$SQLUsuario->execute();

	if(($SQLUsuario->rowCount()) > 0){
		echo alertinfo('error', 'O usuário '.$_POST['login'].' já existe', '../../home.php?page=usuario/1-etapa');
	}else{	
		
		$SQLUsuario = "INSERT INTO usuario (login, senha, data_cadastro, tipo, nome, celular, token_user, permitir_demo)
		VALUES ('".$_POST['login']."', '".$_POST['senha']."',  '$data', '".$_POST['tipo']."', '".$_POST['nome']."', '".$_POST['celular']."', '{$token}', '{$acesso}' )";
		$SQLUsuario = $conn->prepare($SQLUsuario);
			$SQLUsuario->execute();

		$SQLUsuario = "SELECT LAST_INSERT_ID() AS last_id ";
		$SQLUsuario = $conn->prepare($SQLUsuario);
		$SQLUsuario->execute();
			$id = $SQLUsuario->fetch();
		
		echo info_alert_user($administrador['site'] .'', ''.$_POST['login'] .'', ''.$_POST['senha'] .'', '../../home.php?page=usuario/perfil&id_usuario='.$id['last_id'] .' ');
	}
	
}else{
	echo alertinfo('warning', 'Preencha todos os campos!', '../../home.php?page=usuario/1-etapa');
}
?>
</body>
</html>
