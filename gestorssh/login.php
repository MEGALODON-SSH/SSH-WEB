<!DOCTYPE html>
<html class="loading bordered-layout" lang="pt-br" data-layout="bordered-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Painel de gerenciamento vpn">
    <meta name="keywords" content="sshplus, painel, vpn, ssh, user, servidor">
    <meta name="author" content="crazy">
    <title>EMPRESA 🚀</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <!-- END: Custom CSS-->
</head>
<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">

                <body class="fundodapagina vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static  menu-collapsed blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="lineModalLabel"><i class="fas fa-envelope-open-text"></i>Recuperar
                                        Acesso</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- content goes here -->
                                    <form name="recupera" action="recuperando.php" method="post">

                                        <div class="col-md-12 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="first-name-icon">Informe o E-mail</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather="mail"></i></span>
                                                    <input type="text" class="form-control" name="email" placeholder="Digite..." require="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button class="btn btn-success">Confirmar</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="auth-wrapper auth-basic px-2">
                        <div class="auth-inner my-2">
                            <!-- Login basic -->
                            <div class="card mb-0">
                                div class="card mb-0">
                            <div class="card-body">
                                        <h2 class="text-primary text-center"><a class="nav-link nav-link-style"><b>EMPRESA 🚀</b></h2></a>
                                    <br>
                                    <h4 class="card-title mb-1 text-center text-primary">👋 Olá, Seja Bem vindoª!</h4>
                                    <p class="card-text mb-2 text-center">Entre com seu usuário e senha!</p>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-icon">Usuário</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                                <circle cx="12" cy="7" r="4"></circle>
                                                            </svg></span>
                                                        <input type="text" class="form-control" id="login" name="login" placeholder="usuário de acesso" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1">
                                                    <div class="d-flex justify-content-between">
                                                        <label class="form-label" for="password-icon">Senha</label>
                                                        <a class="text-primary" data-bs-toggle="modal" data-bs-target="#myModal" id="to-recover">
                                                            <small>Esqueceu a senha?</small>
                                                        </a>
                                                    </div>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                            </svg></span>
                                                        <input type="password" class="form-control" id="senha" name="senha" placeholder="senha de acesso" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="customCheck4">
                                                        <label class="form-check-label" for="customCheck4">Lembrar</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="button" id="mybtn" class="btn btn-primary w-100" tabindex="4">Entrar</button>
                                            </div>
                                        </div>
                                    <div class="divider my-2">
                                        <div class="divider-text">ou</div>
                                    </div>
                                    <div class="auth-footer-btn d-flex justify-content-center">
                                        <a class="text-primary text-center" href="./admin">ENTRAR ADM</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Login basic -->
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Vendor JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../../../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/pages/auth-login.js"></script>
    <!-- END: Page JS-->

    <script>
        $(document).ready(function() {
            $("#mybtn").click(function() {
                var username = $("#login").val().trim();
                var password = $("#senha").val().trim();

                if (username != "" && password != "") {
                    $.ajax({
                        url: 'validacao.php',
                        type: 'post',
                        data: {
                            username: username,
                            password: password
                        },
                        success: function(response) {
                            var msg = "";
                            if (response == 1) {
                                window.location = "home.php";
                            } else {
                                Swal.fire({
                                    title: 'Usuario ou senha incorreto !',
                                    icon: 'error',
                                    confirmButtonColor: '#7367f0',
                                    confirmButtonText: 'Ok'
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }else{
                                        location.reload();
                                    }
                                })                                
                            }
                        }
                    });
                }
            });
        });
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>