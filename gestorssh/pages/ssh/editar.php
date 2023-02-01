<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
<script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>
<?php
$dias_acesso = 0;

if (isset($_GET["id_ssh"])) {

    $diretorio = "../../home.php?page=ssh/editar&id_ssh=" . $_GET['id_ssh'];

    $SQLUsuarioSSH = "select * from usuario_ssh WHERE id_usuario_ssh = '" . $_GET['id_ssh'] . "' ";
    $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
    $SQLUsuarioSSH->execute();


    $usuario_ssh = $SQLUsuarioSSH->fetch();

    if (($SQLUsuarioSSH->rowCount()) > 0) {

        $SQLServidor = "select * from servidor WHERE id_servidor = '" . $usuario_ssh['id_servidor'] . "'  ";
        $SQLServidor = $conn->prepare($SQLServidor);
        $SQLServidor->execute();
        $ssh_srv = $SQLServidor->fetch();

        //Calcula os dias restante
        $data_atual = date("Y-m-d ");
        $data_validade = $usuario_ssh['data_validade'];
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

        $explo = explode("-", $data_validade);
        $ano = $explo[0];
        $mes = $explo[1];
        $dia = $explo[2];

        $SQLUsuario = "select * from usuario WHERE id_usuario = '" . $usuario_ssh['id_usuario'] . "'  ";
        $SQLUsuario = $conn->prepare($SQLUsuario);
        $SQLUsuario->execute();


        $usuario_sistema = $SQLUsuario->fetch();

        $owner;

        if (($SQLUsuario->rowCount()) > 0) {
            if ($usuario_ssh['id_usuario'] != $_SESSION['usuarioID']) {
                if ($usuario_sistema['id_mestre'] != $_SESSION['usuarioID']) {
                    echo '<script type="text/javascript">';
                    echo     'alert("Nao permitido!");';
                    echo    'window.location="home.php?page=ssh/contas";';
                    echo '</script>';
                }
            }
        } else {
            echo '<script type="text/javascript">';
            echo     'alert("Nao encontrado!");';
            echo    'window.location="home.php?page=ssh/contas";';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo     'alert("Nao encontrado!");';
        echo    'window.location="home.php?page=ssh/contas";';
        echo '</script>';
    }
} else {
    echo '<script type="text/javascript">';
    echo     'alert("Preencha todos os campos!");';
    echo    'window.location="home.php?page=ssh/contas";';
    echo '</script>';
}

if ($usuario_ssh['online'] >= 1) {
    $sts = 'success';
  $status = "<center><div class='alert alert-success col-6 col-md-6' role='alert'>
  <div class='alert-body d-center align-items-center'>
      <span>" . $usuario_ssh['online'] . " conexão de " . $usuario_ssh['acesso'] . "</span>
    </div>
  </div></center>";
} else {
    $sts = 'danger';
  $status = "<center><div class='alert alert-danger col-6 col-md-6' role='alert'>
  <div class='alert-body d-center align-items-center'>
      <span>OFFLINE</span>
    </div>
  </div></center>";
}
?>

<!-- Input with Icons start -->
<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <?php if ($usuario_ssh['status'] == 2) { ?>
                <center>
                    <div class='alert alert-danger col-12 col-md-12' role='alert'>
                        <div class='alert-body d-center align-items-center'>
                            <span>CONTA SUSPENSA</span>
                        </div>
                    </div>
                </center>
            <?php } ?>

            <div class="row match-height">
                <div class="col-md-6">
                    <div class="card card-transaction card border-<?php echo $sts; ?>">
                        <div class="demo-spacing-">
                            <br>
                            <?php echo $status; ?>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='shield' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Login SSH</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $usuario_ssh['login']; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='key' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Senha</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $usuario_ssh['senha']; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='calendar' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Vencimento</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $dia; ?>/<?php echo $mes; ?>/<?php echo $ano; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='clock' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Dias Restantes</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $dias_acesso . " dia(s)"; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='server' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Servidor</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $ssh_srv['nome']; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-primary rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='users' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-primary">Dono</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-warning"><?php echo $usuario_sistema['login']; ?></div>
                                    </div>
                                </li>

                                

                                <form role="form2" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                    <div class="box-footer">
                                        <input type="hidden" id="diretorio" name="diretorio" value="../../home.php?page=ssh/contas">
                                        <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $usuario_ssh['id_usuario_ssh']; ?>">
                                        <input type="hidden" id="owner" name="owner" value="<?php echo $_SESSION['usuarioID']; ?>">
                                        <br>
                                        <ul class="list-group">
                                            <li class="list-group mb-1">
                                                <button type="submit" data-toggle="tooltip" title="Remove do Servidor" class="btn btn-danger" id="op" name="op" value="deletar">Deletar conta SSH</button>
                                            </li>
                                            <li class="list-group mb-1">
                                                <?php if ($usuario_ssh['status'] == 2) { ?>
                                                    <button type="submit" data-toggle="tooltip" title="Reativa a Conta SSH" class="btn btn-success" id="op" name="op" value="ususpender">Reativar conta</button>
                                                <?php } else { ?>
                                                    <button type="submit" data-toggle="tooltip" title="Suspende Temporariamente" class="btn btn-warning" id="op" name="op" value="suspender">Suspender conta</button>
                                                <?php } ?>
                                            </li>
                                        </ul>

                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>






                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body p-b-0">
                            <h4 class="card-title"><i class="fa fa-edit"></i> Editar conta SSH</h4>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs customtab" role="tablist">

                            <?php if ($usuario['subrevenda'] <> 'sim') { ?>
                                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#dono" role="tab"><span class="hidden-sm-up"><i data-feather="users"></i></span> <span class="hidden-xs-down">Alterar Dono</span></a> </li>
                                <?php } ?>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#senha" role="tab"><span class="hidden-sm-up"><i data-feather="key"></i></span> <span class="hidden-xs-down">Senha</span></a> </li>
                                <?php if ($usuario['tipo'] == "revenda") { ?>
								<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#hist" role="tab"><span class="hidden-sm-up"><i data-feather="bar-chart-2"></i></span> <span class="hidden-xs-down">Histórico</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#vencimento" role="tab"><span class="hidden-sm-up"><i data-feather="calendar"></i></span> <span class="hidden-xs-down">Vencimento</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#acesso" role="tab"><span class="hidden-sm-up"><i data-feather="smartphone"></i></span> <span class="hidden-xs-down">Acessos</span></a> </li>
                                <?php } ?>
                            </ul>

                            <div class="tab-content">
                                <div class="active tab-pane" id="dono">
                                    <?php if ($usuario['tipo'] == "revenda") { ?>
                                        <!-- Horizontal Form -->
                                        <div class="box box-primary">
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <form role="form2" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                            <div class="form-group">
                                                <select class="form-select" style="width: 100%;" name="n_owner" id="n_owner">
                                                    <?php if ($usuario_sistema['id_usuario'] == $_SESSION['usuarioID']) {
                                                        $owner = $_SESSION['usuarioID'];
                                                    ?>
                                                        <option selected="selected" value="<?php echo $_SESSION['usuarioID']; ?>"><?php echo $usuario['login']; ?></option>
                                                    <?php
                                                    } else {
                                                        $owner = $usuario_sistema['id_usuario'];
                                                    ?>
                                                        <option selected="selected" value="<?php echo $usuario_sistema['id_usuario']; ?>"><?php echo $usuario_sistema['login']; ?></option>
                                                        <option value="<?php echo $_SESSION['usuarioID']; ?>"><?php echo $usuario['login']; ?></option>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php

                                                    $SQLUsuario = "SELECT * FROM usuario where id_mestre = '" . $_SESSION['usuarioID'] . "'";
                                                    $SQLUsuario = $conn->prepare($SQLUsuario);
                                                    $SQLUsuario->execute();

                                                    if (($SQLUsuario->rowCount()) > 0) {
                                                        // output data of each row
                                                        while ($row = $SQLUsuario->fetch()) {
                                                            if ($row['id_usuario'] != $usuario_sistema['id_usuario']) {
                                                    ?>
                                                                <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['login']; ?></option>
                                                    <?php }
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <input type="hidden" id="op" name="op" value="owner">
                                                <input type="hidden" id="diretorio" name="diretorio" value="<?php echo $diretorio; ?>">
                                                <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $usuario_ssh['id_usuario_ssh']; ?>">
                                                <input type="hidden" id="owner" name="owner" value="<?php echo $owner; ?>">
                                                <br>
                                                <center><button type="submit" class="btn btn-primary">Alterar dono da conta SSH</button> </center><br>
                                            </div>
                                            <!-- /.box-footer -->
                                        </form>

                                        <!-- /.box -->
                                    <?php } else {
                                        $owner = $_SESSION['usuarioID'];
                                    } ?>
                                </div>

                                <div class="tab-pane" id="senha">

                                    <!-- Horizontal Form  -->
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->


                                        <form role="senha" id="senha" name="senha" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input required="required" type="text" class="form-control" id="senha_ssh" name="senha_ssh" placeholder="Digite a nova senha" value="<?php echo $usuario_ssh['senha']; ?>">
                                                    </div>
                                                    <input type="hidden" id="op" name="op" value="senha">
                                                    <input type="hidden" id="id_ssh" name="id_ssh" value="<?php echo $_GET["id_ssh"]; ?>">
                                                    <input type="hidden" id="diretorio" name="diretorio" value="<?php echo $diretorio; ?>">
                                                    <input type="hidden" id="id_servidor" name="id_servidor" value="<?php echo $ssh_srv['id_servidor']; ?>">
                                                    <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $usuario_ssh['id_usuario_ssh']; ?>">
                                                    <input type="hidden" id="owner" name="owner" value="<?php echo $_SESSION['usuarioID']; ?>">
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <br>
                                                <center> <button type="submit" class="btn btn-primary">Alterar Senha</button> </center>
                                            </div>
                                    </div>
                                    <!-- /.box-footer -->
                                    </form>
                                </div>
		  <div class="tab-pane" id="hist">
		  <?php if ($usuario['tipo'] == "revenda") { ?>

				<div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Servidor</th>
                  <th>Inicio</th>
                  <th>Fim</th>
				  <th>Duração</th>


                </tr>
				 <?php

    $SQLHistSSH = "select * from hist_usuario_ssh_online where id_usuario='".$usuario_ssh['id_usuario_ssh']."'  ";
    $SQLHistSSH = $conn->prepare($SQLHistSSH);
    $SQLHistSSH->execute();

    $SQLServidor = "select * from servidor WHERE id_servidor = '".$usuario_ssh['id_servidor']."'  ";
    $SQLServidor = $conn->prepare($SQLServidor);
    $SQLServidor->execute();
    $servidor = $SQLServidor->fetch();

if (($SQLHistSSH->rowCount()) > 0) {
    // output data of each row
    while($row_user = $SQLHistSSH->fetch()   ){

		   $fim_conexao = " Online agora " ;
		   $tempo_conectado = " ";

		   if($row_user['status']== 1){
			   $tempo_conectado =  tempo_corrido($row_user['hora_conexao']);
		   }else if($row_user['status'] != 1){
			   $fim_conexao = $row_user['hora_desconexao'];
			    $tempo_conectado =  tempo_final($row_user['hora_conexao'],$fim_conexao);

		   }





		 ?>


	          <tr >
                  <td><?php echo $servidor['nome'];?></td>
                  <td><?php echo $row_user['hora_conexao'];?></td>
                  <td><?php echo $fim_conexao;?></td>
				  <td><?php echo $tempo_conectado;?></td>


                </tr>


	<?php

	}
}
?>




              </table>
            </div>
			<?php } ?>
			  </div>

                                <div class="tab-pane" id="vencimento">
                                    <?php if ($usuario['tipo'] == "revenda") { ?>
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                            </div>
                                            <!-- /.box-header -->
                                            <!-- form start -->


                                            <form role="form2" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <input required="required" type="number" class="form-control" id="dias" name="dias" placeholder="Digite a quantidade dias de acesso" value="<?php echo $dias_acesso; ?>">
                                                        </div>
                                                        <input type="hidden" id="op" name="op" value="dias">
                                                        <input type="hidden" id="id_usuarioSSH" name="id_usuarioSSH" value="<?php echo $_GET["id_ssh"]; ?>">
                                                        <input type="hidden" id="diretorio" name="diretorio" value="<?php echo $diretorio; ?>">
                                                        <input type="hidden" id="owner" name="owner" value="<?php echo $_SESSION['usuarioID']; ?>">
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer">
                                                    <br>
                                                    <center><button type="submit" class="btn btn-primary">Alterar dias de acesso</button> </center>
                                                </div>
                                                <!-- /.box-footer -->
                                            </form>
                                        </div>
                                        <!-- /.box -->
                                    <?php } ?>
                                </div>


                                <div class="tab-pane" id="acesso">
                                    <?php if ($usuario['tipo'] == "revenda") { ?>
                                        <!-- Horizontal Form -->
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                            </div>
                                            <!-- /.box-header -->
                                            <!-- form start -->

                                            <form role="form2" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <input required="required" type="number" class="form-control" id="acesso" name="acesso" placeholder="Digite a quantidade de acesso" value="<?php echo $usuario_ssh['acesso']; ?>">
                                                        </div>
                                                        <input type="hidden" id="op" name="op" value="acesso">
                                                        <input type="hidden" id="diretorio" name="diretorio" value="<?php echo $diretorio; ?>">
                                                        <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $usuario_ssh['id_usuario_ssh']; ?>">
                                                        <input type="hidden" id="owner" name="owner" value="<?php echo $owner; ?>">
                                                        <input type="hidden" id="sistema" name="sistema" value="<?php echo $_SESSION['usuarioID']; ?>">
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer">
                                                    <br>
                                                    <center><button type="submit" class="btn btn-primary">Alterar limite simultâneo</button> </center>
                                                </div>
                                                <!-- /.box-footer -->
                                            </form>
                                        </div>
                                    <?php } ?>
                                    <!-- /.box -->
                                    <div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
</section>
<!-- Input with Icons end -->