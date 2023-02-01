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
                    <h4 class="card-title">Servidores</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>NOME</th>
                                <th>REGIAO</th>
                                <th>ENDERECO IP</th>
								<th>ONLINE</th>
								<th>APLICATIVO</th>
                                <th>SSH CRIADAS</th>
                                <th>ACESSOS</th>
                                <th>EDITAR</th>
								<th>SINC.CONTAS</th>
								<th>DELETAR</th>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            $SQLServidor = "select * from servidor";
                            $SQLServidor = $conn->prepare($SQLServidor);
                            $SQLServidor->execute();
							

                            // output data of each row
                            if (($SQLServidor->rowCount()) > 0) {

                                while ($row = $SQLServidor->fetch()) {
                                    $acessos = 0;
									$usuarios_online = 0;

                                    if ($row['tipo'] == 'premium') {
                                        $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor='" . $row['id_servidor'] . "' ";
                                        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                        $SQLAcessoSSH->execute();
                                        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                        $acessos += $SQLAcessoSSH['quantidade'];

                                        $SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '" . $row['id_servidor'] . "' ";
                                        $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                                        $SQLUsuarioSSH->execute();
										
										$SQLContasSSH = "SELECT sum(online) AS soma  FROM usuario_ssh where id_servidor = '" . $row['id_servidor'] . "'   ";
                                        $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                        $SQLContasSSH->execute();
                                        $SQLContasSSH = $SQLContasSSH->fetch();
										$usuarios_online += $SQLContasSSH['soma'];
										
                                    } else {
                                        $SQLUsuarioSSH = "select * from usuario_ssh_free WHERE servidor = '" . $row['id_servidor'] . "' ";
                                        $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                                        $SQLUsuarioSSH->execute();
                                    }

                                    $qtd_ssh = $SQLUsuarioSSH->rowCount();

                                    switch ($row['tipo']) {
                                        case 'premium':
                                            $tipo = 'Premium';
                                            break;
                                        case 'free':
                                            $tipo = 'Free';
                                            break;
                                        default:
                                            $tipo = 'erro';
                                            break;
                                    }

                                    $SQLopen = "select * from ovpn WHERE servidor_id = '" . $row['id_servidor'] . "' ";
                                    $SQLopen = $conn->prepare($SQLopen);
                                    $SQLopen->execute();
                                    if ($SQLopen->rowCount() > 0) {
                                        $openvpn = $SQLopen->fetch();
                                        $texto = "<a href='../admin/pages/servidor/baixar_ovpn.php?id=" . $openvpn['id'] . "' class=\"label label-info\">Baixar</a>";
                                    } else {
                                        $texto = "<span class=\"label label-danger\">Indisponivel</span>";
                                    }
                            ?>
                                    <tr>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $row['nome']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo ucfirst($row['regiao']); ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $row['ip_servidor']; ?>
                                                </span>
                                            </span>
                                        </td>
										<td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
												    <?php echo $usuarios_online; ?>
                                                </span>
                                            </span>
                                        </td>
										 <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $texto; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $qtd_ssh; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $acessos; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="home.php?page=servidor/servidor&id_servidor=<?php echo $row['id_servidor']; ?>" class="btn-sm btn-primary"><i data-feather='edit'></i></a>
											</td>
											<td>
											<a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $row['id_servidor']; ?>&op=sincronizar" class="btn-sm btn-success"><i data-feather='repeat'></i></a>
                                        </td>
											<td>
											<a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $row['id_servidor']; ?>&op=deletarGeral" class="btn-sm btn-danger"><i data-feather='trash-2'></i></a>
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