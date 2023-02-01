<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
<script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
?>
<?php

  $SQLUsuarioSSH = "SELECT * FROM usuario_ssh , servidor  where usuario_ssh.id_servidor = servidor.id_servidor and  usuario_ssh.status >= '2' ORDER BY  usuario_ssh.status  ";
  $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
  $SQLUsuarioSSH->execute();


  $usuario_ssh = $SQLUsuarioSSH->fetch();

?>
<script type="text/javascript" src="../../app-assets/plugins/sort-table.js"></script>
<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<style>
    .card-datatable {
        padding-left: 5px;
        padding-right: 5px;
    }
</style>
<div class="active"><a class="d-flex align-items-center" href="home.php"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a>
</div>
<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Contas SSH suspensas</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="col-12"><br>
                        <div class="form-responsive">
						<form role="form2" action="../pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
          <div class="box-footer">
            <input type="hidden" id="diretorio" name="diretorio" value="../../admin/home.php?page=ssh/suspenso">
            <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $usuario_ssh['id_usuario_ssh']; ?>">
            <input type="hidden" id="owner" name="owner" value="<?php echo $accessKEY; ?>">
            <br><ul class="list-group">
              <li class="list-group mb-1">
			  <center><button type="submit" class="btn btn-danger" id="op" name="op" value="deletarsusp"><i data-feather='trash'></i> Deletar Contas Suspensas</button></center>
			  </li>
			  </ul>
            
          </div>
        </form>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SERVIDOR</th>
                                <th>IP</th>
                                <th>STATUS</th>
                                <th>LOGIN</th>
                                <th>VALIDADE</th>
                                <th>DONO</th>
                                <th>OPÇÕES</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            //lista
                            $SQLSSH = "SELECT * FROM usuario_ssh , servidor  where usuario_ssh.id_servidor = servidor.id_servidor and  usuario_ssh.status >= '2' ORDER BY  usuario_ssh.status  ";
                            $SQLSSH = $conn->prepare($SQLSSH);
                            $SQLSSH->execute();


                            // output data of each row
                            if (($SQLSSH->rowCount()) > 0) {

                                while ($row = $SQLSSH->fetch()) {
                                    $class = "class='btn-sm btn-primary waves-effect waves-float waves-light'";
                                    $info = "info";
                                    $status = "";
                                    $owner = "";
                                    $color = "";
                                    if ($row['status'] == 1) {
                                        $status = "Ativo";
                                        $sts = "success";
                                    } else if ($row['status'] == 2) {
                                        $status = "Suspenso";
                                        $sts = "danger";
                                        $color = "bgcolor=''";
                                    }
									if ($row['status'] == 3) {
                                        $status = "Excluindo";
                                        $sts = "danger";
                                        $color = "bgcolor=''";
                                    }
                                    if ($row['id_usuario'] == 0) {
                                        $owner = "Sistema";
                                    } else {

                                        $SQLRevendedor = "select * from usuario WHERE id_usuario = '" . $row['id_usuario'] . "'";
                                        $SQLRevendedor = $conn->prepare($SQLRevendedor);
                                        $SQLRevendedor->execute();
                                        $revendedor = $SQLRevendedor->fetch();

                                        $owner = $revendedor['login'];
                                    }
                                    //Calcula os dias restante
                                    $data_atual = date("Y-m-d ");
                                    $data_validade = $row['data_validade'];
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
									<tr>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $row['nome']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $row['ip_servidor']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-glow bg-<?php echo $sts; ?>">
                                                    <?php echo $status; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $row['login']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $dias_acesso . "  dias   "; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-warning">
                                                    <?php echo $owner; ?>
                                                </span>
                                            </span>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="home.php?page=ssh/editar&id_ssh=<?php echo $row['id_usuario_ssh']; ?>" <?php echo $class; ?>><i data-feather='edit'></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            }


                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>