<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
<script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="../../../app-assets/js/scripts/ui/ui-feather.js"></script>
<script src="../../../app-assets/vendors/js/vendors.min.js"></script>
<script src="../../../app-assets/js/core/app-menu.js"></script>
<script src="../../../app-assets/js/core/app.js"></script>
<?php
if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

function myalertuser($status, $msgalert, $dirt)
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


if (isset($_GET['char'])) {

    $novochar = $_GET['char'];

    switch ($novochar) {
        case 1:
            $char = 1;
            break;
        case 2:
            $char = 2;
            break;
        case 3:
            $char = 3;
            break;
        case 4:
            $char = 4;
            break;
        case 5:
            $char = 5;
            break;
        default:
            $char = 0;
            break;
    }

    if ($char == 0) {
        echo myalertuser('error', 'Erro na selecao do avatar !', '../../home.php?page=admin/dados');
        exit;
    }

    if (($char == 5) and ($usuario['tipo'] <> 'revenda')) {
        echo myalertuser('error', 'Ocorreu um erro !', '../../home.php?page=admin/dados');
        exit;
    }


    $SQLDelSSH = "update usuario set avatar='" . $char . "' WHERE id_usuario = '" . $usuario['id_usuario'] . "'  ";
    $SQLDelSSH = $conn->prepare($SQLDelSSH);
    $SQLDelSSH->execute();

    

    echo myalertuser('success', 'Dados alterados com sucesso !', '../../home.php?page=admin/dados');
}

switch ($usuario['tipo']) {
    case 'vpn':
        $tipousuario = 'Usuário VPN';
        break;
    case 'revenda':
        $tipousuario = 'Revendedor';
        break;
    default:
        $tipousuario = 'erro';
        break;
}
if (($usuario['tipo'] == 'revenda') && ($usuario['subrevenda'] == 'sim')) {
    $tipousuario = 'Sub Revendedor';
}

if (($usuario['tipo'] == 'revenda') && ($usuario['subrevenda'] == 'nao')) {
    $SQLSubrevendedores = "select * from usuario WHERE id_mestre = '" . $_SESSION['usuarioID'] . "' and tipo='revenda' and subrevenda='sim' ";
    $SQLSubrevendedores = $conn->prepare($SQLSubrevendedores);
    $SQLSubrevendedores->execute();
    $todossubrevendedores = $SQLSubrevendedores->rowCount();

    if (($SQLSubrevendedores->rowCount()) > 0) {

        while ($subrow = $SQLSubrevendedores->fetch()) {
            $quantidade_ssh_subs = 0;
            $SQLSubSSHsubs = "select * from usuario_ssh WHERE id_usuario = '" . $subrow['id_usuario'] . "'  ";
            $SQLSubSSHsubs = $conn->prepare($SQLSubSSHsubs);
            $SQLSubSSHsubs->execute();
            $quantidade_ssh_subs += $SQLSubSSHsubs->rowCount();

            $sshsubs = $SQLSubSSHsubs->rowCount();


            $SQLSubUSUARIOSsubs = "select * from usuario WHERE id_mestre = '" . $subrow['id_usuario'] . "'  ";
            $SQLSubUSUARIOSsubs = $conn->prepare($SQLSubUSUARIOSsubs);
            $SQLSubUSUARIOSsubs->execute();
            $quantidade_USUARIOS_subs += $SQLSubUSUARIOSsubs->rowCount();
            $sshsubs132 = $SQLSubUSUARIOSsubs->rowCount();
            if (($SQLSubUSUARIOSsubs->rowCount()) > 0) {
                while ($subrow2 = $SQLSubUSUARIOSsubs->fetch()) {
                    $SQLSubSSHsubs123 = "select * from usuario_ssh WHERE id_usuario = '" . $subrow2['id_usuario'] . "'  ";
                    $SQLSubSSHsubs123 = $conn->prepare($SQLSubSSHsubs123);
                    $SQLSubSSHsubs123->execute();
                    $quantidade_ssh_subs += $SQLSubSSHsubs123->rowCount();
                }
            }
        }
    }
}

?>

<?php if ($usuario['atualiza_dados'] == 0) { ?>


    <div class="demo-spacing-0 text-center mb-2">
        <div class="alert alert-primary alert-dismissible" role="alert">
            <h2 class="alert-heading text-warning"><i data-feather='alert-octagon'></i>Atenção, preencha todos os campos e salve!</h2>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php } ?>

<style>
    #container {
        width: 100%;
        border-color: green;
        text-align: center;
        vertical-align: middle;
    }

    .box {
        display: inline-block;
    }

    #box-1 {
        background: rgba(255, 175, 75, 1);
        background: -moz-linear-gradient(left, rgba(255, 175, 75, 1) 0%, rgba(255, 146, 10, 1) 10%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(255, 175, 75, 1)), color-stop(100%, rgba(255, 146, 10, 1)));
        background: -webkit-linear-gradient(left, rgba(255, 175, 75, 1) 0%, rgba(255, 146, 10, 1) 100%);
        background: -o-linear-gradient(left, rgba(255, 175, 75, 1) 0%, rgba(255, 146, 10, 1) 100%);
        background: -ms-linear-gradient(left, rgba(255, 175, 75, 1) 0%, rgba(255, 146, 10, 1) 100%);
        background: linear-gradient(to right, rgba(255, 175, 75, 1) 0%, rgba(255, 146, 10, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffaf4b', endColorstr='#ff920a', GradientType=1);
    }

    #box-2 {
        background: rgba(98, 125, 77, 1);
        background: -moz-linear-gradient(left, rgba(98, 125, 77, 1) 0%, rgba(31, 59, 8, 1) 100%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(98, 125, 77, 1)), color-stop(100%, rgba(31, 59, 8, 1)));
        background: -webkit-linear-gradient(left, rgba(98, 125, 77, 1) 0%, rgba(31, 59, 8, 1) 100%);
        background: -o-linear-gradient(left, rgba(98, 125, 77, 1) 0%, rgba(31, 59, 8, 1) 100%);
        background: -ms-linear-gradient(left, rgba(98, 125, 77, 1) 0%, rgba(31, 59, 8, 1) 100%);
        background: linear-gradient(to right, rgba(98, 125, 77, 1) 0%, rgba(31, 59, 8, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#627d4d', endColorstr='#1f3b08', GradientType=1);
    }

    #box-3 {
        background: rgba(203, 96, 179, 1);
        background: -moz-linear-gradient(left, rgba(203, 96, 179, 1) 0%, rgba(193, 70, 161, 1) 43%, rgba(168, 0, 119, 1) 64%, rgba(219, 54, 164, 1) 100%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(203, 96, 179, 1)), color-stop(43%, rgba(193, 70, 161, 1)), color-stop(64%, rgba(168, 0, 119, 1)), color-stop(100%, rgba(219, 54, 164, 1)));
        background: -webkit-linear-gradient(left, rgba(203, 96, 179, 1) 0%, rgba(193, 70, 161, 1) 43%, rgba(168, 0, 119, 1) 64%, rgba(219, 54, 164, 1) 100%);
        background: -o-linear-gradient(left, rgba(203, 96, 179, 1) 0%, rgba(193, 70, 161, 1) 43%, rgba(168, 0, 119, 1) 64%, rgba(219, 54, 164, 1) 100%);
        background: -ms-linear-gradient(left, rgba(203, 96, 179, 1) 0%, rgba(193, 70, 161, 1) 43%, rgba(168, 0, 119, 1) 64%, rgba(219, 54, 164, 1) 100%);
        background: linear-gradient(to right, rgba(203, 96, 179, 1) 0%, rgba(193, 70, 161, 1) 43%, rgba(168, 0, 119, 1) 64%, rgba(219, 54, 164, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#cb60b3', endColorstr='#db36a4', GradientType=1);
    }

    #box-4 {
        background: rgba(252, 236, 252, 1);
        background: -moz-linear-gradient(left, rgba(252, 236, 252, 1) 0%, rgba(251, 166, 225, 1) 43%, rgba(253, 137, 215, 1) 62%, rgba(255, 124, 216, 1) 100%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(252, 236, 252, 1)), color-stop(43%, rgba(251, 166, 225, 1)), color-stop(62%, rgba(253, 137, 215, 1)), color-stop(100%, rgba(255, 124, 216, 1)));
        background: -webkit-linear-gradient(left, rgba(252, 236, 252, 1) 0%, rgba(251, 166, 225, 1) 43%, rgba(253, 137, 215, 1) 62%, rgba(255, 124, 216, 1) 100%);
        background: -o-linear-gradient(left, rgba(252, 236, 252, 1) 0%, rgba(251, 166, 225, 1) 43%, rgba(253, 137, 215, 1) 62%, rgba(255, 124, 216, 1) 100%);
        background: -ms-linear-gradient(left, rgba(252, 236, 252, 1) 0%, rgba(251, 166, 225, 1) 43%, rgba(253, 137, 215, 1) 62%, rgba(255, 124, 216, 1) 100%);
        background: linear-gradient(to right, rgba(252, 236, 252, 1) 0%, rgba(251, 166, 225, 1) 43%, rgba(253, 137, 215, 1) 62%, rgba(255, 124, 216, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fcecfc', endColorstr='#ff7cd8', GradientType=1);
    }

    #box-5 {
        background: rgba(183, 222, 237, 1);
        background: -moz-linear-gradient(left, rgba(183, 222, 237, 1) 0%, rgba(113, 206, 239, 1) 43%, rgba(33, 180, 226, 1) 59%, rgba(183, 222, 237, 1) 100%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(183, 222, 237, 1)), color-stop(43%, rgba(113, 206, 239, 1)), color-stop(59%, rgba(33, 180, 226, 1)), color-stop(100%, rgba(183, 222, 237, 1)));
        background: -webkit-linear-gradient(left, rgba(183, 222, 237, 1) 0%, rgba(113, 206, 239, 1) 43%, rgba(33, 180, 226, 1) 59%, rgba(183, 222, 237, 1) 100%);
        background: -o-linear-gradient(left, rgba(183, 222, 237, 1) 0%, rgba(113, 206, 239, 1) 43%, rgba(33, 180, 226, 1) 59%, rgba(183, 222, 237, 1) 100%);
        background: -ms-linear-gradient(left, rgba(183, 222, 237, 1) 0%, rgba(113, 206, 239, 1) 43%, rgba(33, 180, 226, 1) 59%, rgba(183, 222, 237, 1) 100%);
        background: linear-gradient(to right, rgba(183, 222, 237, 1) 0%, rgba(113, 206, 239, 1) 43%, rgba(33, 180, 226, 1) 59%, rgba(183, 222, 237, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b7deed', endColorstr='#b7deed', GradientType=1);
    }


    #box-1:hover {
        box-shadow: 2px 2px 20px rgba(0, 0, 0, .3);
    }

    #box-2:hover {
        box-shadow: 5px 5px 25px rgba(0, 0, 0, .3);
    }

    #box-3:hover {
        box-shadow: 5px 5px 25px rgba(0, 0, 0, .3);
    }

    #box-4:hover {
        box-shadow: 5px 5px 25px rgba(0, 0, 0, .3);
    }

    #box-5:hover {
        box-shadow: 5px 5px 25px rgba(0, 0, 0, .3);
    }
</style>
<script>
    function selecionachar(id) {
        Swal.fire({
            title: 'Definir o avatar ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Nao'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../home.php?page=admin/dados&char=' + id;
            }
        })
    }
</script>
<!-- Modal -->
<div class="col-md-4">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Avatares Disponíveis:</h4>
                </div>
                <div class="modal-body" id="container">
                    <div id="box-" class="box"><img onclick="selecionachar(1);" style="width:100%;max-width:100px" class="profile-user-img img-responsive img-circle" src="../../../app-assets/images/avatars/avatar1.png" alt="User profile picture"></div>
                    <div id="box-" class="box"><img onclick="selecionachar(2);" style="width:100%;max-width:100px" class="profile-user-img img-responsive img-circle" src="../../../app-assets/images/avatars/avatar2.png" alt="User profile picture"></div>
                    <div id="box-" class="box"><img onclick="selecionachar(3);" style="width:100%;max-width:100px" class="profile-user-img img-responsive img-circle" src="../../../app-assets/images/avatars/avatar3.png" alt="User profile picture"></div>
                    <div id="box-" class="box"><img onclick="selecionachar(4);" style="width:100%;max-width:100px" class="profile-user-img img-responsive img-circle" src="../../../app-assets/images/avatars/avatar4.png" alt="User profile picture"></div>
                    <div id="box-" class="box"><img onclick="selecionachar(5);" style="width:100%;max-width:100px" class="profile-user-img img-responsive img-circle" src="../../../app-assets/images/avatars/avatar5.png" alt="User profile picture"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-6 col-12">
        <!-- Profile Image -->
        <div class="card border-primary">
            <div class="card-body little-profile text-center">
                <div class="pro-img"><img class="img-circle" src="../../../app-assets/images/avatars/<?php echo $avatarusu; ?>" height="80" width="80" alt="user" /></div>
                <br>
                <h3 class="profile-username text-center"><?php echo $usuario['nome']; ?></h3>
                <p class="text-muted text-center"><span class="badge badge-light-primary profile-badge"><?php echo $tipousuario; ?></span></p>
                <br>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Contas SSH</b> <a class="pull-right"><span class="badge badge-light-info"><?php echo $quantidade_ssh; ?></span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Contas SSH Susp.</b> <a class="pull-right"><span class="badge badge-light-warning"><?php echo $all_ssh_susp_qtd; ?></span></a>
                    </li>
                    <!--<li class="list-group-item">
                        <b>Acessos SSH </b> <a class="pull-right"><?php echo $total_acesso_ssh; ?></a>
                    </li>-->
                    <!--<?php if ($usuario['tipo'] == 'revenda') { ?>
                        <li class="list-group-item">
                            <b>Usuários SSH</b> <a class="pull-right"><?php echo $quantidade_sub; ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Contas SSH Usuários</b> <a class="pull-right"><?php echo $sshsub; ?></a>
                        </li>
                    <?php } ?>-->
                    <?php if (($usuario['tipo'] == 'revenda') and ($usuario['subrevenda'] == 'nao')) { ?>
                        <li class="list-group-item">
                            <b>SubRevendedores</b> <a class="pull-right"><span class="badge badge-light-primary"><?php echo $todossubrevendedores; ?></span></a>
                        </li>
                        <!--<li class="list-group-item">
                            <b>Usuários dos Sub</b> <a class="pull-right"><?php echo $quantidade_USUARIOS_subs; ?></a>
                        </li>-->
                        <li class="list-group-item">
                            <b>Contas SSH dos Sub</b> <a class="pull-right"><span class="badge badge-light-danger"><?php echo $quantidade_ssh_subs; ?></span></a>
                        </li>
                    <?php } ?>
                </ul>
                <br><a data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-primary btn-block"><b>Alterar Avatar</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body p-b-0">
                <h4 class="card-title"><i class="fa fa-edit"></i> Gerenciar</h4>
                <form class="form-horizontal" role="form" id="form" name="form" action="pages/admin/alterar.php" method="post">
                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">Login</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="user"></i></span>
                            <input disabled type="text" class="form-control" minlength="4" value="<?php echo $usuario['login']; ?>" placeholder="Login" required="">
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">Cliente desde</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                            <input disabled type="text" class="form-control" minlength="4" value="<?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?>" placeholder="Data de Cadastro" required="">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">Nome</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="user"></i></span>
                            <input type="text" class="form-control" minlength="4" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>" placeholder="Nome" required="required">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">E-MAIL</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="mail"></i></span>
                            <input type="email" class="form-control" minlength="4" id="email" name="email" value="<?php echo $usuario['email']; ?>" placeholder="E-Mail" required="required">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">Celular</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="smartphone"></i></span>
                            <input type="text" class="form-control" minlength="4" id="celular" name="celular" value="<?php echo $usuario['celular']; ?>" placeholder="Celular" required="required">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="first-name-icon">Senha</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i data-feather="key"></i></span>
                            <input type="password" min="6" max="32" class="form-control" name="senha" data-minlength="6" placeholder="Digite a Senha" required="required" value="<?php echo $usuario['senha']; ?>">
                        </div>
                    </div>
                    <!--<?php if ($usuario['tipo'] == 'revenda') { ?>
                        <div class="form-group">
                            <label>ID Cliente</label>
                            <input type="text" class="form-control" placeholder="Do Mercado Pago..." name="idcliente" value="<?php echo $usuario['idcliente_mp']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Token Secreto</label>
                            <input type="text" class="form-control" name="tokensecreto" placeholder="Do Mercado Pago..." value="<?php echo $usuario['tokensecret_mp']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Dados Bancários</label>
                            <textarea class="form-control" rows="5" name="bancarios" class="form-control" placeholder="Exemplo: Conta Bradesco Para Deposito/Trânsferencia Agencia: 0000 Conta: 00000 Nome: Crazy vpn" cols=20 wrap="off"><?php echo $usuario['dadosdeposito']; ?></textarea>
                        </div>-->
                <?php } ?>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Salvar Dados</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>