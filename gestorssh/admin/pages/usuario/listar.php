<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
?>
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
                    <h4 class="card-title">Revendedoores e Sub revendedores</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>STATUS</th>
                                <th>NOME</th>
                                <th>LOGIN</th>
                                <th>É SUB REVENDA</th>
                                <th>CONTAS SSH</th>
                                <th>SERVIDORES</th>
                                <th>DONO</th>
                                <th>OPCOES</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php

                            $SQLUsuario = "SELECT * FROM usuario   where  tipo = 'revenda' ORDER BY ativo ";
                            $SQLUsuario = $conn->prepare($SQLUsuario);
                            $SQLUsuario->execute();


                            // output data of each row
                            if (($SQLUsuario->rowCount()) > 0) {

                                while ($row = $SQLUsuario->fetch()) {
                                    $class = "class='btn-sm btn-danger waves-effect waves-float waves-light'";
                                    $status = "";
                                    $color = "";
                                    $contas = 0;
                                    $servidores = 0;
                                    if ($row['ativo'] == 1) {
                                        $status = "Ativo";
                                        $info = "info";
                                        $class = "class='btn-sm btn-primary waves-effect waves-float waves-light'";
                                        $sts = "success";
                                    } else {
                                        $status = "Desativado";
                                        $color = "bgcolor='#FF6347'";
                                        $info = "danger";
                                        $sts = "danger";
                                    }

                                    $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $row['id_usuario'] . "'";
                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                    $SQLContasSSH->execute();
                                    $contas += $SQLContasSSH->rowCount();

                                    $SQLServidores = "select * from acesso_servidor WHERE id_usuario = '" . $row['id_usuario'] . "'";
                                    $SQLServidores = $conn->prepare($SQLServidores);
                                    $SQLServidores->execute();
                                    $servidores += $SQLServidores->rowCount();

                                    $total_acesso_ssh = 0;
                                    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $row['id_usuario'] . "' ";
                                    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                    $SQLAcessoSSH->execute();
                                    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

                                    $SQLUserSub = "select * from usuario WHERE id_mestre = '" . $row['id_usuario'] . "'";
                                    $SQLUserSub = $conn->prepare($SQLUserSub);
                                    $SQLUserSub->execute();

                                    if (($SQLUserSub->rowCount()) > 0) {

                                        while ($rowS = $SQLUserSub->fetch()) {
                                            $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $rowS['id_usuario'] . "'";
                                            $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                            $SQLContasSSH->execute();
                                            $contas += $SQLContasSSH->rowCount();

                                            $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $rowS['id_usuario'] . "' ";
                                            $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                            $SQLAcessoSSH->execute();
                                            $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                            $total_acesso_ssh += $SQLAcessoSSH['quantidade'];
                                        }
                                    }

                                    if ($row['subrevenda'] == 'sim') {
                                        $subrev = 'Sim';
                                    } else {
                                        $subrev = 'Não';
                                    }

                                    $SQLRevendedor = "select * from usuario WHERE id_usuario = '".$row['id_mestre']."'  ";
                                    $SQLRevendedor = $conn->prepare($SQLRevendedor);
                                    $SQLRevendedor->execute();
                                    $revendedor =  $SQLRevendedor->fetch();
                                    $owner = $revendedor['login'];
                                   
                            ?>

                                    <tr>
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
                                                    <?php echo $row['nome']; ?>
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
                                                <?php if ($subrev == 'Sim') { ?>
                                                    <span class="badge badge-light-success">
                                                        <?php echo $subrev; ?>
                                                    <?php } else { ?>
                                                        <span class="badge badge-light-danger">
                                                            <?php echo $subrev; ?>
                                                        <?php } ?>
                                                        </span>
                                                    </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $contas; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $servidores; ?>
                                                </span>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-warning">
                                                <?php if ($owner != '') { ?>
                                                    <?php echo $owner; ?>
                                                <?php } else { ?>
                                                    Admin
                                                <?php } ?>
                                                </span>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="home.php?page=usuario/perfil&id_usuario=<?php echo $row['id_usuario']; ?>" <?php echo $class; ?>><i data-feather='edit'></i></a>
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
</section>
