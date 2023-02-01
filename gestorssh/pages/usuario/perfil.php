<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
<script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}



if (isset($_GET["id_usuario"])) {

    $id_usuario = $_GET["id_usuario"];
    $owner = $_SESSION['usuarioID'];
    $SQLUsuario = "SELECT * FROM usuario where id_usuario='" . $id_usuario . "' and id_mestre =  '" . $_SESSION['usuarioID'] . "' ";
    $SQLUsuario = $conn->prepare($SQLUsuario);
    $SQLUsuario->execute();

    $SQLUsuarioSSH = "SELECT * FROM usuario_ssh where id_usuario='" . $id_usuario . "'   ";
    $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
    $SQLUsuarioSSH->execute();

    $total_ssh = $SQLUsuarioSSH->rowCount();

    $total_acesso_ssh = 0;
    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $id_usuario . "' ";
    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
    $SQLAcessoSSH->execute();
    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

    if (($SQLUsuario->rowCount()) <= 0) {
        echo '<script type="text/javascript">';
        echo     'alert("O usuario nao existe!");';
        echo    'window.location="home.php?page=usuario/listar";';
        echo '</script>';
        exit;
    } else {
        $usuarioGET = $SQLUsuario->fetch();
    }

    $SQLV = "SELECT permitir_demo FROM usuario where id_usuario = '" . $_SESSION['usuarioID'] . "'";
    $SQLV = $conn->prepare($SQLV);
    $SQLV->execute();
    $rowv = $SQLV->fetch();
    $perm_v2 = $rowv['permitir_demo'];

} else {

    echo '<script type="text/javascript">';
    echo     'alert("Preencha todos os campos!");';
    echo    'window.location="home.php?page=usuario/listar";';
    echo '</script>';
}


$diretorio = " ";

if ($usuario['ativo'] == 2) {
    $sts = "danger";
} else {
    $sts = "info";
}

?>

<!-- Alerta de usuario suspenso -->
<?php if ($usuarioGET['ativo'] == 2) { ?>
    <center>
        <div class='alert alert-danger col-12 col-md-12' role='alert'>
            <div class='alert-body d-center align-items-center'>
                <span>CONTA SUSPENSA</span>
            </div>
        </div>
    </center>
<?php } ?>


<div class="row">
    <div class="col-lg-6 col-xlg-6 col-md-6">
        <div class="card border-<?php echo $sts; ?>">
            <div class="card-body little-profile text-center">
                <div class="pro-img"><img class="img-circle" src="../app-assets/images/avatars/<?php echo $avatarusu; ?>" height="60" width="60" alt="user" /></div>

                <h3 class="profile-username text-center"><?php echo $usuarioGET['nome']; ?></h3>
                <?php if ($usuarioGET['tipo'] == "vpn") { ?>
                    <p class="text-primary text-center">Usuário SSH</p>

                    <?php } elseif ($usuarioGET['tipo'] == "revenda") {
                    if ($usuarioGET['subrevenda'] == 'sim') {
                    ?>
                        <p class="text-primary text-center">Sub-Revendedor</p>
                    <?php } else {
                    ?>
                        <p class="text-primary text-center">Revendedor</p>
                <?php
                    }
                } ?>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Contas SSH</b> <a class="pull-right badge badge-pill bg-warning float-right text-white"><?php echo $total_ssh; ?></a><br>
                    </li>
                </ul>
                <br>
                <ul class="list-group">
                    <li class="list-group mb-1">
                        <div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
                            <a onclick="excluir_usuario()" class="btn btn-danger waves-effect waves-float waves-light"><i data-feather='trash'></i><b> DELETAR TUDO</b></a>
                        </div>
                    </li>
                    <li class="list-group mb-1">
                        <div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
                            <?php if ($usuarioGET['ativo'] == 1) { ?>
                                <a onclick="suspende_usuario()" class="btn btn-warning text-white"><i data-feather='user-x'></i><b> SUSPENDER TUDO</b></a>
                            <?php } else { ?>
                                <a href="pages/system/funcoes.usuario.php?&id_usuario=<?php echo $usuarioGET['id_usuario']; ?>&diretorio=../../home.php?page=subrevenda/revendedores&owner=<?php echo $owner; ?>&op=ususpender" class="btn btn-success text-white"><i data-feather='user-check'></i><b> REATIVAR TUDO</b></a>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
                <script type="text/javascript">
                    function excluir_usuario() {
                        Swal.fire({
                            title: 'Excluir',
                            text: "Realmente deseja excluir ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28c76f',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Nao'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'pages/system/funcoes.usuario.php?&id_usuario=<?php echo $usuarioGET['id_usuario']; ?>&diretorio=../../home.php?page=subrevenda/revendedores&owner=<?php echo $owner; ?>&op=deletar'
                            }
                        })

                    }

                    function suspende_usuario() {
                        Swal.fire({
                            title: 'Suspender',
                            text: "Realmente deseja suspender ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28c76f',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Nao'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'pages/system/funcoes.usuario.php?&id_usuario=<?php echo $id_usuario; ?>&diretorio=../../home.php?page=subrevenda/revendedores&owner=<?php echo $owner; ?>&op=suspender'
                            }
                        })
                    }
                </script>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body p-b-0">
                <h4 class="card-title"> Gerenciar Revenda</i></h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#activity" role="tab"><span class="hidden-sm-up"><i data-feather="info"></i></span> <span class="hidden-xs-down"> Editar</span></a> </li>
                    <?php if ($usuarioGET['subrevenda'] == 'sim') {  ?>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#ssh" role="tab"><span class="hidden-sm-up"><i data-feather="server"></i></span> <span class="hidden-xs-down">Servidores Alocados</span></a> </li>
                    <?php } else { ?>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#ssh" role="tab"><span class="hidden-sm-up"><i class="ti-loop"></i></span> <span class="hidden-xs-down">Contas SSH</span></a> </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        <form class="form-horizontal" role="perfil" name="perfil" id="perfil" action="pages/usuario/editarusuario.php" method="post">
                            <div class="mb-1">
								<label class="form-label" for="first-name-icon">Login</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="user"></i></span>
								<input type="text" name="login" id="login" class="form-control" minlength="4" value="<?php echo $usuarioGET['login']; ?>" required="">
                                <input type="hidden" class="form-control" id="id_usuario" name="id_usuario" value="<?php echo $usuarioGET['id_usuario']; ?>">
                                <input type="hidden" class="form-control" id="diretorio" name="diretorio" value="../../home.php?page=usuario/perfil&id_usuario=<?php echo $usuarioGET['id_usuario']; ?>">
								</div>
							</div>

							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Senha</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="key"></i></span>
                                    <input type="password" name="senha" id="senha" class="form-control" minlength="4" value="<?php echo $usuarioGET['senha']; ?>" required="">
								</div>
							</div>


							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Email</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="mail"></i></span>
									<input type="text" name="email" id="email" class="form-control" minlength="4" value="<?php echo $usuarioGET['email']; ?>" required="">
								</div>
							</div>

                            <div class="mb-1">
								<label class="form-label" for="first-name-icon">Celular</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="smartphone"></i></span>
									<input type="text" name="celular" id="celular" class="form-control" minlength="4" value="<?php echo $usuarioGET['celular']; ?>" required="">
								</div>
							</div>

							<div class="mb-2">
								<label class="form-label" for="first-name-icon">Data de cadastro</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="calendar"></i></span>
									<input type="text" class="form-control" disabled value="<?php echo $usuarioGET['data_cadastro']; ?>">
								</div>
							</div>
                            
                            <?php if($usuarioGET['permitir_demo'] == 0) { 
								$checkssh = 'checked';
							} ?>
							<?php if($usuarioGET['permitir_demo'] == 1) { 
								$checkv2 = 'checked';
							} ?>

                            <?php if($perm_v2 == 1) { ?>
                                <div class="mb-2">
                                    <div class="row custom-options-checkable g-2">
                                        <div class="col-md-6">
                                            <input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon2" value="0" <?php echo $checkssh; ?> />
                                            <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                                                <i data-feather="shield" class="font-large-1 mb-75"></i>
                                                <span class="custom-option-item-title h4 d-block">ACESSO SSH</span>
                                                <small>Ele e os revendedores poderá criar apenas contas ssh</small>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon3" value="1" <?php echo $checkv2; ?> />
                                            <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
                                                <i data-feather="link" class="font-large-1 mb-75"></i>
                                                <span class="custom-option-item-title h4 d-block">ACESSO SSH E V2RAY</span>
                                                <small>Ele e os revendedores poderá criar contas ssh e v2ray</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php }else{ ?>
                                <input type="hidden" class="form-control" name="acesso" id="acesso" value="0">
                            <?php } ?>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">Alterar Dados</button>
                            </div>                            
                        </form>

                    </div>

                    <div class=" tab-pane" id="ssh">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <?php if ($usuarioGET['subrevenda'] == 'sim') { ?>
                                            <h3 class="box-title">Servidores Alocados</h3>
                                        <?php } else { ?>
                                            <h3 class="box-title">Contas SSH</h3>
                                        <?php } ?>
                                    </div>
                                    <!-- /.box-header -->
                                    <?php if ($usuarioGET['subrevenda'] == 'sim') { ?>
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th>Servidor</th>
                                                    <th>Limite Acessos</th>
                                                    <th>Validade</th>
                                                    <th>Contas SSH</th>
                                                    <th>Acao</th>
                                                </tr>
                                                <?php
                                                $SQLAcessoServidor = "select * from acesso_servidor where id_usuario='" . $usuarioGET['id_usuario'] . "'  ";
                                                $SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
                                                $SQLAcessoServidor->execute();
                                                if (($SQLAcessoServidor->rowCount()) > 0) {
                                                    while ($row2 = $SQLAcessoServidor->fetch()) {

                                                        $SQLTotalUser = "select * from usuario WHERE id_usuario = '" . $_GET['id_usuario'] . "' ";
                                                        $SQLTotalUser = $conn->prepare($SQLTotalUser);
                                                        $SQLTotalUser->execute();
                                                        $total_user = $SQLTotalUser->rowCount();

                                                        $SQLServidor = "select * from servidor where id_servidor = '" . $row2['id_servidor'] . "'";
                                                        $SQLServidor = $conn->prepare($SQLServidor);
                                                        $SQLServidor->execute();

                                                        $contas = 0;
                                                        $acessos = 0;


                                                        $SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '" . $row2['id_servidor'] . "'
                 and id_usuario='" . $_GET['id_usuario'] . "'  ";
                                                        $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                                                        $SQLUsuarioSSH->execute();
                                                        $contas += $SQLUsuarioSSH->rowCount();

                                                        $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row2['id_servidor'] . "'  and id_usuario='" . $_GET['id_usuario'] . "' ";
                                                        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                                        $SQLAcessoSSH->execute();
                                                        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                                        $acessos += $SQLAcessoSSH['quantidade'];

                                                        $SQLUsuarioSub = "select * from usuario WHERE id_mestre = '" . $_GET['id_usuario'] . "'  ";
                                                        $SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
                                                        $SQLUsuarioSub->execute();

                                                        if (($SQLUsuarioSub->rowCount()) > 0) {
                                                            while ($row3 = $SQLUsuarioSub->fetch()) {

                                                                $SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '" . $row2['id_servidor'] . "'
                    and id_usuario='" . $row3['id_usuario'] . "'  ";
                                                                $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                                                                $SQLUsuarioSSH->execute();
                                                                $contas += $SQLUsuarioSSH->rowCount();

                                                                $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row2['id_servidor'] . "'  and id_usuario='" . $row3['id_usuario'] . "' ";
                                                                $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                                                $SQLAcessoSSH->execute();
                                                                $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                                                $acessos += $SQLAcessoSSH['quantidade'];
                                                            }
                                                        }

                                                        if (($SQLServidor->rowCount()) > 0) {
                                                            while ($row3 = $SQLServidor->fetch()) {

                                                                $qtd_srv = 0;

                                                                //Calcula os dias restante
                                                                $data_atual = date("Y-m-d");
                                                                $data_validade = $row2['validade'];
                                                                if ($data_validade > $data_atual) {
                                                                    $data1 = new DateTime($data_validade);
                                                                    $data2 = new DateTime($data_atual);
                                                                    $dias_acesso = 0;
                                                                    $diferenca = $data1->diff($data2);
                                                                    $ano = $diferenca->y * 364;
                                                                    $mes = $diferenca->m * 30;
                                                                    $dia = $diferenca->d;
                                                                    $dias_acesso = $ano + $mes + $dia;
                                                                } else {
                                                                    $dias_acesso = 0;
                                                                }

                                                ?>

                                                                <div id="myModal<?php echo $row2['id_acesso_servidor']; ?>" class="modal fade" aria-labelledby="modalLabel" aria-hidden="true" style="display: none;">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <form name="deletarserver" action="pages/subrevenda/deletarservidor_exe.php" method="post">
                                                                                <input name="servidor" type="hidden" value="<?php echo $row2['id_acesso_servidor']; ?>">
                                                                                <input name="cliente" type="hidden" value="<?php echo $usuarioGET['id_usuario']; ?>">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title" id="lineModalLabel">Apagar Tudo de <?php echo $usuarioGET['nome']; ?></h4>
                                                                                </div>
                                                                                <div class="modal-body text-center">
                                                                                    <h4>Tem certeza?</h4><br>
                                                                                    <p>Todos os clientes deles irão ter a conta SSH Deletada.<br>Você recebe os Acessos de Volta !</p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                <div class="col-12 text-center">
                                                                                    <button class="btn btn-success">Confirmar</button>
                                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                        </div>


                                        <tr>
                                            <td><?php echo $row3['nome']; ?></td>
                                            <td><?php echo $row2['qtd']; ?></td>
                                            <td>
                                                <span class="pull-left-container" style="margin-right: 5px;">
                                                    <span class="label label-primary pull-left">
                                                        <?php echo $dias_acesso . "  dias   "; ?>
                                                    </span>
                                                </span>
                                            </td>
                                            <td><?php echo $contas; ?></td>

                                            <td>

                                                <!-- <a href="#" class="btn btn-warning">Editar Acesso</a> -->
                                                <a data-bs-toggle="modal" data-bs-target="#myModal<?php echo $row2['id_acesso_servidor']; ?>" class="btn btn-sm btn-danger"><i data-feather='trash'></i></a>

                                            </td>
                                        </tr>
                        <?php
                                                            }
                                                        }
                                                    }
                                                }
                        ?>
                        </table>
                                </div>
                            <?php } else { ?>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Login</th>
                                            <th>Servidor</th>
                                            <th>Validade</th>
                                            <th></th>
                                        </tr>
                                        <?php
                                        $SQLUsuario = "SELECT * FROM usuario_ssh where id_usuario =  '" . $usuarioGET['id_usuario'] . "' and status <= '2' ";
                                        $SQLUsuario = $conn->prepare($SQLUsuario);
                                        $SQLUsuario->execute();

                                        if (($SQLUsuario->rowCount()) > 0) {

                                            // output data of each row
                                            while ($row_user = $SQLUsuario->fetch()) {

                                                $SQLServidor = "SELECT * FROM servidor where id_servidor =  '" . $row_user['id_servidor'] . "' ";
                                                $SQLServidor = $conn->prepare($SQLServidor);
                                                $SQLServidor->execute();
                                                $servidor = $SQLServidor->fetch();
                                                $color = "";


                                                if ($row_user['status'] == 2) {

                                                    $color = "bgcolor='#FF6347'";
                                                }




                                        ?>


                                                <tr <?php echo $color; ?>>
                                                    <td><?php echo $row_user['login']; ?></td>
                                                    <td><?php echo $servidor['nome']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($row_user['data_validade'])); ?></td>
                                                    <td>

                                                        <a href="home.php?page=ssh/editar&id_ssh=<?php echo $row_user['id_usuario_ssh']; ?>" class="btn btn-primary">Detalhes</a></center>

                                                    </td>

                                                </tr>


                                        <?php

                                            }
                                        }

                                        ?>

                                    </table>
                                </div>
                                
                            <?php } ?>
                            <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->