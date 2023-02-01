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

protegePagina("admin");

	    if(isset($_POST["idusuario"])){
		     if(strlen($_POST["senha"])<6){
				echo alertinfo('warning', 'Senha muito curta!', '../../home.php?page=usuario/perfil&id_usuario='.$_POST["idusuario"].'');
			    exit;
		     }

			$SQLUsuario = "select * from usuario where id_usuario = '".$_POST['idusuario']."' ";
            $SQLUsuario = $conn->prepare($SQLUsuario);
            $SQLUsuario->execute();

		    if(($SQLUsuario->rowCount())>0){
				$conta_ssh = $SQLUsuario->fetch();
				$SQLUPUsuario = "update usuario set nome='".$_POST["nome"]."', email='".$_POST["email"]."', senha='".$_POST["senha"]."', celular='".$_POST["celular"]."', permitir_demo='".$_POST["acesso"]."' WHERE id_usuario = '".$_POST["idusuario"]."'";
                $SQLUPUsuario = $conn->prepare($SQLUPUsuario);
                $SQLUPUsuario->execute();
				echo alertinfo('success', 'Alterado com sucesso!', '../../home.php?page=usuario/perfil&id_usuario='.$conta_ssh['id_usuario'] .'');

			}else{
				echo alertinfo('error', 'Invalido', '../../home.php?page=usuario/listar');
			}

		}else{
			echo alertinfo('warning', 'Preencha todos os campos!', '../../home.php?page=usuario/listar');
		}

?>
</body>
</html>