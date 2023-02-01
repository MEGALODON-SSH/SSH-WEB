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

<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Contas SSH Online</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SERVIDOR</th>
                                <th>LOGIN</th>
                                <th>VALIDADE</th>
                                <th>TEMPO</th>
                                <th>STATUS</th>
                                <th>CONEXÃO</th>
                                <th>ACESSOS</th>
                                <th>OPÇÕES</th>
								<th>DERRUBAR</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php

                            $SQLSub = "SELECT * FROM usuario where id_usuario= '" . $_SESSION['usuarioID'] . "'";
                            $SQLSub = $conn->prepare($SQLSub);
                            $SQLSub->execute();



                            if (($SQLSub->rowCount()) > 0) {
                                while ($rowSub = $SQLSub->fetch()) {
                                    $SQLSubSSH = "SELECT * FROM usuario_ssh, servidor  where usuario_ssh.id_servidor = servidor.id_servidor and usuario_ssh.id_usuario = '" . $_SESSION['usuarioID'] . "'";
                                    $SQLSubSSH = $conn->prepare($SQLSubSSH);
                                    $SQLSubSSH->execute();


                                    if (($SQLSubSSH->rowCount()) > 0) {
                                        while ($row = $SQLSubSSH->fetch()) {
                                            $status = "";
                                            if ($row['status'] == 1) {
                                                $status = "Ativo";
                                                $class = "class='btn-sm btn-primary waves-effect waves-float waves-light'";
                                            } else {
                                                $status = "Desativado";
                                            }
                                            if ($row['online'] != 0) {
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
                                                        <span class="badge badge-light-info">
                                                            <?php echo $row['nome']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info">
                                                            <?php echo $row['login']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info">
                                                            <?php echo $dias_acesso . "  dias   "; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info">
                                                            <?php echo tempo_corrido($row['online_start']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-glow bg-success">Online</div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info">
                                                            <?php echo $row['online']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info">
                                                            <?php echo $row['acesso']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="home.php?page=ssh/editar&id_ssh=<?php echo $row['id_usuario_ssh']; ?>" class="btn-sm btn-primary"><i data-feather='edit'></i></a>
                                                    </td>
													<td>
													<form role="form2" action="pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                                    <input type="hidden" id="diretorio" name="diretorio" value="../../home.php?page=ssh/contas">
                                                    <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $row['id_usuario_ssh']; ?>">
										            <input type="hidden" id="owner" name="owner" value="<?php echo $_SESSION['usuarioID']; ?>">
                                                    <button type="submit" class="btn-sm btn-danger" id="op" name="op" value="kill2"><i data-feather='user-minus'></i></button>
                                                    </form>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }



                            if ($usuario['tipo'] == "revenda") {



                                $SQLSub = "SELECT * FROM usuario where id_mestre= '" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
                                $SQLSub = $conn->prepare($SQLSub);
                                $SQLSub->execute();


                                if (($SQLSub->rowCount()) > 0) {
                                    while ($rowSub = $SQLSub->fetch()) {
                                        $SQLSSH = "SELECT * FROM usuario_ssh, servidor  where usuario_ssh.id_servidor = servidor.id_servidor and usuario_ssh.id_usuario = '" . $rowSub['id_usuario'] . "'";
                                        $SQLSSH = $conn->prepare($SQLSSH);
                                        $SQLSSH->execute();


                                        if (($SQLSSH->rowCount()) > 0) {
                                            while ($row = $SQLSSH->fetch()) {
                                                $status = "";
                                                if ($row['status'] == 1) {
                                                    $status = "Ativo";
                                                } else {
                                                    $status = "Desativado";
                                                }
                                                if ($row['online'] != 0) {


                                                ?>
                                                    <tr>

                                                        <td>
                                                            <span class="badge badge-light-info">
                                                                <?php echo $row['nome']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-info">
                                                                <?php echo $row['ip_servidor']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-info">
                                                                <?php echo $row['login']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-info">
                                                                <?php echo $dias_acesso . "  dias   "; ?>
                                                            </span>
                                                        </td>

                                                        <td><?php echo tempo_corrido($row['online_start']); ?> </td>
                                                        <td>
                                                            <div class="badge badge-pill badge-glow badge-success">Online</div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-info"><?php echo $row['online']; ?></span>
                                                        </td>
                                                        <td><span class="badge badge-light-info"><?php echo $row['acesso']; ?></span></td>
                                                        <td><span class="badge badge-light-info"><?php echo $rowSub['login']; ?></span></td>

                                                        <td>
                                                            <a href="home.php?page=ssh/editar&id_ssh=<?php echo $row['id_usuario_ssh']; ?>" class="btn-sm btn-primary"><i data-feather='edit'></i></a>
                                                        </td>
                                                    </tr>
                            <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            }



                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>