<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
?>

<script type="text/javascript">
    function deleta(id) {
        decisao = confirm("Tem certeza que deseja deletar essa Conta?!");
        if (decisao) {
            window.location.href = 'home.php?page=ssh/online_free&deletar=' + id;
        }

    }
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
                                <th>LIMITE</th>
                                <th>DONO</th>
                                <th>OPCÕES</th>
								<th>DERRUBAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $SQLSub = "SELECT * FROM usuario_ssh";
                            $SQLSub = $conn->prepare($SQLSub);
                            $SQLSub->execute();

                            if (($SQLSub->rowCount()) > 0) {
                                while ($rowSub = $SQLSub->fetch()) {

                                    $SQLSSH = "SELECT * FROM servidor where tipo='premium' and id_servidor='" . $rowSub['id_servidor'] . "' ORDER BY id_servidor";
                                    $SQLSSH = $conn->prepare($SQLSSH);
                                    $SQLSSH->execute();
                                    $row = $SQLSSH->fetch();

                                    //Calcula os dias restante
                                    $dias_acesso = 0;

                                    $data_atual = date("Y-m-d ");
                                    $data_validade = $rowSub['data_validade'];
                                    if ($data_validade > $data_atual) {
                                        $data1 = new DateTime($data_validade);
                                        $data2 = new DateTime($data_atual);
                                        $dias_acesso = 0;
                                        $diferenca = $data1->diff($data2);
                                        $ano = $diferenca->y * 364;
                                        $mes = $diferenca->m * 30;
                                        $dia = $diferenca->d;
                                        $dias_acesso = $ano + $mes + $dia;
                                    }

                                    $SQLSubowner = "SELECT * FROM usuario where id_usuario='" . $rowSub['id_usuario'] . "'";
                                    $SQLSubowner = $conn->prepare($SQLSubowner);
                                    $SQLSubowner->execute();
                                    $own = $SQLSubowner->fetch();

                                    $status = "";
                                    if ($rowSub['status'] == 1) {
                                        $status = "Ativo";
                                        $class = "class='btn-sm btn-primary waves-effect waves-float waves-light'";
                                    } else {
                                        $status = "Desativado";
                                    }
                                    if ($rowSub['online'] != 0) {
                            ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    <?php echo $row['nome']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    <?php echo $rowSub['login']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    <?php echo $dias_acesso . "  dias   "; ?>
                                                </span>
                                            </td>
                                            <td>
                                            <span class="badge badge-light-info">
                                                <?php echo tempo_corrido($rowSub['online_start']); ?>
                                            </span>
                                            </td>
                                            <td><?php
                                                if ($rowSub['online'] > 0) { ?>
                                                    <div class="badge badge-glow bg-success">Online</div>
                                                <?php } else {
                                                    echo $rowSub['online'];
                                                } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($rowSub['online'] > $rowSub['acesso']) { ?>
                                                    <span class="badge badge-light-danger">
                                                        <?php echo $rowSub['online']; ?>
                                                    </span>
                                                <?php } else {
                                                    echo '<span class="badge badge-light-info">';
                                                    echo $rowSub['online'];
                                                    echo '</span>';
                                                } ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    <?php echo $rowSub['acesso']; ?>
                                            </td>
                                            </span>
                                            <td>
                                                <span class="badge badge-light-warning">
                                                    <?php echo $own['nome']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a class="btn-sm btn-primary waves-effect waves-float waves-light" href="home.php?page=ssh/editar&id_ssh=<?php echo $rowSub['id_usuario_ssh']; ?>" <?php echo $class; ?>><i data-feather='edit'></i></a>
                                            </td>
											<td>
													<form role="form2" action="../pages/system/funcoes.conta.ssh.php" method="post" class="form-horizontal">
                                                    <input type="hidden" id="diretorio" name="diretorio" value="../../home.php?page=ssh/contas">
                                                    <input type="hidden" id="id_usuario_ssh" name="id_usuario_ssh" value="<?php echo $rowSub['id_usuario_ssh']; ?>">
										            <input type="hidden" id="owner" name="owner" value="<?php echo $accessKEY; ?>">
                                                    <button type="submit" class="btn-sm btn-danger" id="op" name="op" value="kill"><i data-feather='user-minus'></i></button>
                                                    </form>
                                                </tr>
                                        </tr>
                            <?php
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