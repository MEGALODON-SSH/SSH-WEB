<?php
require_once("../pages/system/funcoes.php");
require_once("../pages/system/seguranca.php");
require_once("../pages/system/config.php");
require_once("../pages/system/classe.ssh.php");

protegePagina("admin");
if ($_SESSION['tipo'] == "user") {
    expulsaVisitante();
}


$data_atual = date("Y-m-d");

$SQLAdministrador = "SELECT * FROM admin WHERE id_administrador = '" . $_SESSION['usuarioID'] . "'";
$SQLAdministrador = $conn->prepare($SQLAdministrador);
$SQLAdministrador->execute();
$administrador = $SQLAdministrador->fetch();

//Carrega qtd contas ssh do sistema

$SQLUsuario_sshSUSP = "select * from usuario_ssh WHERE status='2' ";
$SQLUsuario_sshSUSP = $conn->prepare($SQLUsuario_sshSUSP);
$SQLUsuario_sshSUSP->execute();
$ssh_susp_qtd += $SQLUsuario_sshSUSP->rowCount();

$SQLContasSSH = "SELECT * FROM usuario_ssh ";
$SQLContasSSH = $conn->prepare($SQLContasSSH);
$SQLContasSSH->execute();
$contas_ssh = $SQLContasSSH->rowCount();


$total_acesso_ssh = 0;
$SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh  ";
$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
$SQLAcessoSSH->execute();
$SQLAcessoSSH = $SQLAcessoSSH->fetch();
$total_acesso_ssh += $SQLAcessoSSH['quantidade'];

$total_acesso_ssh_online = 0;
$SQLAcessoSSH = "SELECT sum(online) AS quantidade  FROM usuario_ssh  ";
$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
$SQLAcessoSSH->execute();
$SQLAcessoSSH = $SQLAcessoSSH->fetch();
$total_acesso_ssh_online += $SQLAcessoSSH['quantidade'];


//carrega qtd de todos os usuarios do sistema
$SQLUsuarios = "SELECT * FROM usuario ";
$SQLUsuarios = $conn->prepare($SQLUsuarios);
$SQLUsuarios->execute();
$all_usuarios_qtd = $SQLUsuarios->rowCount();

//carrega qtd de todos os usuarios do sistema SSH
$SQLVPN = "SELECT * FROM usuario  where tipo='vpn' ";
$SQLVPN = $conn->prepare($SQLVPN);
$SQLVPN->execute();
$all_usuarios_vpn_qtd = $SQLVPN->rowCount();

$SQLVPN = "SELECT * FROM usuario  where tipo='vpn' and ativo='2' ";
$SQLVPN = $conn->prepare($SQLVPN);
$SQLVPN->execute();
$all_usuarios_vpn_qtd_susp = $SQLVPN->rowCount();

//carrega qtd de todos os usuarios do sistema SSH
$SQLRevenda = "SELECT * FROM usuario  where tipo='revenda' ";
$SQLRevenda = $conn->prepare($SQLRevenda);
$SQLRevenda->execute();
$all_usuarios_revenda_qtd = $SQLRevenda->rowCount();
//carrega qtd de todos os usuarios do sistema SSH
$SQLRevenda = "SELECT * FROM usuario  where tipo='revenda' and ativo='2'";
$SQLRevenda = $conn->prepare($SQLRevenda);
$SQLRevenda->execute();
$revenda_qtd_susp = $SQLRevenda->rowCount();

//carrega qtd de servidores
$SQLServidor = "SELECT * FROM servidor ";
$SQLServidor = $conn->prepare($SQLServidor);
$SQLServidor->execute();
$servidor_qtd = $SQLServidor->rowCount();

// arquivos download
$SQLArquivos = "select * from  arquivo_download";
$SQLArquivos = $conn->prepare($SQLArquivos);
$SQLArquivos->execute();
$todosarquivos = $SQLArquivos->rowCount();
// Faturas
$SQLfaturas = "select * from  fatura where status='pendente'";
$SQLfaturas = $conn->prepare($SQLfaturas);
$SQLfaturas->execute();
$faturas = $SQLfaturas->rowCount();
// Notificações
$SQLnoti = "select * from  notificacoes where lido='nao' and admin='sim'";
$SQLnoti = $conn->prepare($SQLnoti);
$SQLnoti->execute();
$totalnoti = $SQLnoti->rowCount();
// Notificações fatura
$SQLnoti2 = "select * from  notificacoes where lido='nao' and tipo='fatura' and admin='sim'";
$SQLnoti2 = $conn->prepare($SQLnoti2);
$SQLnoti2->execute();
$totalnoti_fatura = $SQLnoti2->rowCount();
// Notificações chamados
$SQLnoti3 = "select * from  notificacoes where lido='nao' and tipo='chamados' and admin='sim'";
$SQLnoti3 = $conn->prepare($SQLnoti3);
$SQLnoti3->execute();
$totalnoti_chamados = $SQLnoti3->rowCount();

//Todos os chamados subRevendedores e usuarios do revendedor
$SQLchamadoscli2 = "select * from  chamados where status='resposta' and id_mestre=0";
$SQLchamadoscli2 = $conn->prepare($SQLchamadoscli2);
$SQLchamadoscli2->execute();
$all_chamados += $SQLchamadoscli2->rowCount();
//Todos os chamados subRevendedores e usuarios do revendedor
$SQLchamadoscli = "select * from  chamados where status='aberto' and id_mestre=0";
$SQLchamadoscli = $conn->prepare($SQLchamadoscli);
$SQLchamadoscli->execute();
$all_chamados += $SQLchamadoscli->rowCount();



?>
<!DOCTYPE html>
<html class="loading bordered-layout" lang="pt" data-layout="bordered-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Gerenciador de conexôes SSH">
    <meta name="keywords" content="VPN EMPRESA 🚀, VPN, SSH, Gratis, Registrar">
    <meta name="author" content="Crazy">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="theme-color" content="#FFFFFF">
    <title>EMPRESA 🚀 - PAINEL</title>
    <link rel="apple-touch-icon" href="../app-assets/images/ico/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/pages/app-invoice-list.css">


    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!-- END: Custom CSS-->
    <!--<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="lineModalLabel">Notificar Geral !</h3>
                </div>
                <div class="modal-body">
                    <form name="editaserver" action="pages/notificacoes/notificar_home.php" method="post">
                        <div class="mb-1">
                            <input type="hidden" class="form-control" name="clientes" value="1">
                            <label for="message-text" class="col-form-label">Mensagem:</label>
                            <textarea class="form-control" name="msg" rows="5" cols="20" wrap="off" placeholder="Digita sua mensagem..."></textarea>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button class="btn btn-success" data-bs-dismiss="modal">Confirmar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function usuariosonline() {
            // Requisição
            $.post('pages/ssh/online_home.php?requisicao=1',
                function(resposta) {
                    //Adiciona Efeito Fade
                    $("#usuarioson").fadeOut("slow").fadeIn('slow');
                    // Limpa
                    $('#usuarioson').empty();
                    // Exibe
                    $('#usuarioson').append(resposta);
                });
        }
        // Intervalo para cada Chamada
        setInterval("usuariosonline()", 10000);
        // Após carregar a Pagina Primeiro Requisito
        $(function() {
            // Requisita Função acima
            usuariosonline();
        });
    </script>

    <script>
        function atualizar() {
            // Fazendo requisição AJAX
            $.post('pages/ssh/online_home.php?requisicao=2',
                function(online) {
                    // Exibindo frase
                    $('#online_nav').html(online);
                    $('#online').html(online);

                }, 'JSON');
        }
        // Definindo intervalo que a função será chamada
        setInterval("atualizar()", 10000);
        // Quando carregar a página
        $(function() {
            // Faz a primeira atualização
            atualizar();
        });
    </script>

    <script>
        $(window).load(function() {
            $(".se-pre-con").fadeOut('slow');
        });
    </script>

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a>
                </li>
                <li class="nav-item"><a type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="ficon" data-feather='message-square'></i></a></li>
                <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="zap"></i><span class="badge rounded-pill bg-success badge-up badge-glow cart-item-count" id="online_nav"><?php echo $total_acesso_ssh_online; ?></span></a>

                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Usuários Online</h4>
                                <div class="badge rounded-pill badge-light-success" id="online">
                                    <?php echo $total_acesso_ssh_online; ?>
                                </div>
                            </div>
                        </li>
                        <?php if ($total_acesso_ssh_online > 0) { ?>
                            <li class="scrollable-container media-list" id="usuarioson">
                            </li>
                        <?php } else { ?>
                            <li class="scrollable-container media-list">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content"><i data-feather='zap-off'></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Sem usuários online</span></p>
                                        <small class="notification-text">Ninguém conectado no momento</small>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                        <li class="dropdown-menu-footer">
                            <div class="d-flex justify-content-between mb-0">
                            </div><a class="btn btn-primary w-100" href="home.php?page=ssh/online">Ver Todos</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge rounded-pill bg-danger badge-up badge-glow"><?php echo $totalnoti; ?></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Notificações</h4>
                                <div class="badge rounded-pill badge-light-danger"><?php echo $totalnoti; ?></div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list"><a class="d-flex" href="#">
                                <?php if ($totalnoti == 0) { ?></h2>
                                    <div class="list-item d-flex align-items-start">
                                        <div class="me-1">
                                            <div class="avatar bg-light-danger">
                                                <div class="avatar-content"><i class="avatar-icon" data-feather="x"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-item-body flex-grow-1">
                                            <p class="media-heading"><span class="fw-bolder">Sem notificações</span></p>
                                            <small class="notification-text">Você não possui novas notificações</small>
                                        </div>
                                    </div>
                                <?php } else { ?>
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder"><?php echo $totalnoti; ?>Nova
                                                notificação</span></p><small class="notification-text">Vc possue novas
                                            notificações</small>
                                    </div>
                                </div>
                            <?php } ?>
                            </a>
                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="?page=notificacoes/notificacoes&ler=all">Ver Notificações</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder text-primary"><?php echo ucfirst($administrador['nome']); ?></span><span class="user-status">Administrador</span></div><span class="avatar"><img class="round" src="../app-assets/images/avatars/avatar6.png" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user"><a class="dropdown-item" href="home.php?page=admin/dados"><i class="me-50" data-feather="user"></i>Meus Dados</a>
                        <a class="dropdown-item" href="home.php?page=apis/gerenciar"><i class="me-50" data-feather="settings"></i>Configurações</a>
                        <a class="dropdown-item" href="sair.php"><i class="me-50" data-feather="power"></i>Sair</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="false">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><a class="navbar-brand" href="/admin/home.php"><span class="brand-logo">
                            <img class="round" src="../app-assets/images/logo/logo.png" alt="avatar" height="35" width="28" />
                        </span>
                        <h2 class="brand-text">EMPRESA </h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>

        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <li class="active"><a class="d-flex align-items-center" href="home.php"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a></li>
                <li class=" navigation-header"><span>MENU</span>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i class="text-success" data-feather="shield"></i><span class="menu-title text-truncate" data-i18n="Area VPN">CONTAS SSH</span><span class="badge badge-light-success rounded-pill ms-auto me-1"><?php echo $contas_ssh; ?></span></a>
                    <ul class="menu-content">
                        <li><a href="?page=ssh/adicionar"><i data-feather="user-plus"></i><span class="menu-item" data-i18n="Criar VPN">Criar Conta SSH</span></a>
                        </li>
                        <li><a href="?page=ssh/add_teste"><i data-feather="clock"></i><span class="menu-item" data-i18n="Criar Teste">Criar Teste SSH</span></a>
                        </li>
                        <li><a href="?page=ssh/online"><i data-feather="zap"></i><span class="menu-item" data-i18n="SSH Online">SSH Online</span></a></li>
                        <li><a href="?page=ssh/contas"><i data-feather='list'></i><span class="menu-item" data-i18n="Listar VPN">Listar SSH</span></a>
						<li><a href="?page=ssh/suspenso"><i data-feather='list'></i><span class="menu-item" data-i18n="Listar VPN">Contas Susp</span></a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i class="text-info" data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Area de Cliente">REVENDAS</span><span class="badge badge-light-info rounded-pill ms-auto me-1"><?php echo $all_usuarios_qtd; ?></span></a>
                    <ul class="menu-content">
                        <li><a href="?page=usuario/1-etapa"><i data-feather='user-plus'></i><span class="menu-item" data-i18n="Novo Usuário">Novo Revendedor</span></a>
                        </li>
                        <li><a href="?page=usuario/revenda"><i data-feather='users'></i><span class="menu-item" data-i18n="Revendedores">Revendedores</span></a>
                        </li>
                        <li><a href="?page=usuario/addservidor"><i data-feather='user-check'></i><span class="menu-item" data-i18n="Add. Sevidor">Adicionar Servidor</span></a>
                        </li>
                        <li><a href="?page=servidor/alocados"><i data-feather='edit'></i><span class="menu-item" data-i18n="Alterar">Alterar Revenda</span></a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i class="text-warning" data-feather="server"></i><span class="menu-title text-truncate" data-i18n="Alterar">SERVIDORES</span><span class="badge badge-light-warning rounded-pill ms-auto me-1"><?php echo $servidor_qtd; ?></span></a>
                    <ul class="menu-content">
                        <li><a href="?page=servidor/adicionar"><i data-feather='hard-drive'></i><span class="menu-item" data-i18n="Alterar">Novo servidor</span></a>
                        </li>
                        <li><a href="?page=servidor/listar"><i data-feather='list'></i><span class="menu-item" data-i18n="Alterar">Listar servidores</span></a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i class="text-danger" data-feather='life-buoy'></i></i><span class="menu-title text-truncate" data-i18n="Chamados">TICKETS</span><span class="badge badge-light-danger rounded-pill ms-auto me-1"><?php echo $all_chamados; ?></span></a>
                    <ul class="menu-content">
                        <li><a href="?page=chamados/abertas"><i data-feather='bell'></i><span class="menu-item" data-i18n="Abertos">Abertos</span></a>
                        </li>
                        <li><a href="?page=chamados/respondidos"><i data-feather='bell-off'></i><span class="menu-item" data-i18n="Respondidos">Respondidos</span></a>
                        </li>
                        <li><a href="?page=chamados/encerrados"><i data-feather='slash'></i><span class="menu-item" data-i18n="Encerrados">Encerrados</span></a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i class="text-primary" data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Configurações">CONFIGURAÇÕES</span></a>
                    <ul class="menu-content">
					    <li><a href="?page=conecta"><i data-feather='github'></i><span class="menu-item" data-i18n="Conecta">Conecta4g</span></a>
                        </li>
                         <li><a href="?page=apps"><i data-feather='gitlab'></i><span class="menu-item" data-i18n="Apps">Gerenciar Apps</span></a>
                        </li>
                         <li><a href="?page=download/downloads"><i data-feather='file'></i><span class="menu-item" data-i18n="Arquivos">Arquivos</span></a>
                        </li>
                         <li><a href="?page=perso"><i data-feather='star'></i><span class="menu-item" data-i18n="Apps">Personalizar</span></a>
                        </li>
						<li><a href="?page=admin/dados"><i data-feather='edit'></i><span class="menu-item" data-i18n="Minhas Informações">Meus Dados</span></a>
                        </li>
                        <li><a href="?page=apis/gerenciar"><i data-feather='code'></i><span class="menu-item" data-i18n="Gerenciar APIS">Gerenciar API</span></a>
                        </li>
                        <li><a href="?page=notificacoes/notificar"><i data-feather='message-square'></i><span class="menu-item" data-i18n="Notificações">Notificações</span></a>
                        </li>
                        <li><a href="?page=email/enviaremail"><i data-feather='mail'></i><span class="menu-item" data-i18n="Email">Email</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="sair.php"><i class="text-danger" data-feather='log-out'></i><span class="menu-title" data-i18n="Raise Support">SAIR</span></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">

                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </section>
                <!-- Dashboard Analytics end -->
                <?php

                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                    if ($page and file_exists("pages/" . $page . ".php")) {
                        include("pages/" . $page . ".php");
                    } else {
                        include("./pages/inicial.php");
                    }
                } else {
                    include("./pages/inicial.php");
                }
                ?>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <center>
            <p class="clearfix blue-grey lighten-2 mb-0">
                <span class="center">
                    &copy; <script>
                        document.write(new Date().getFullYear())
                    </script>
                    <a class="text-bold-800 grey darken-2" href="https://coutyssh.com.br" target="_blank">EMPRESA 🚀</a></span>
                </span>
            </p>
        </center>
    </footer>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->

    <script src="../app-assets/vendors/js/vendors.min.js"></script>

    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->

    <script src="../app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <!--<script src="../app-assets/vendors/js/extensions/toastr.min.js"></script>-->
    <script src="../app-assets/vendors/js/extensions/moment.min.js"></script>



    <script src="../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>


    <script src="../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js"></script>
    <script src="../app-assets/js/scripts/tables/table-datatables-basic.js"></script>

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../app-assets/js/core/app-menu.js"></script>
    <script src="../app-assets/js/core/app.js"></script>


    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../app-assets/js/scripts/pages/dashboard-analytics.js"></script>
    <script src="../app-assets/js/scripts/pages/app-invoice-list.js"></script>
    <script src="../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../app-assets/js/scripts/forms/form-select2.js"></script>
    <script src="../app-assets/js/scripts/extensions/ext-component-blockui.js"></script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });

        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "lengthMenu": "_MENU_ Registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "Não há registros",
                    "infoFiltered": "(filtrando em _MAX_ registros)",
                    "loadingRecords": "Aguarde...",
                    "processing": "Processando...",
                    "search": "Pesquisar:",
                    "zeroRecords": "Nenhum registro encontrado",
                    "paginate": {
                        "first": "Primeiro",
                        "last": "último",
                        "next": "Próximo",
                        "previous": "Anterior"
                    }
                },
                "scrollX": true,
                order: [
                    [3, 'desc'],
                    [0, 'asc']
                ]
            });
        });
    </script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>