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
                    <h4 class="card-title">Sub revendedores</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>STATUS</th>
                                <th>NOME</th>
                                <th>LOGIN</th>
                                <th>SENHA</th>
                                <th>CONTAS SSH</th>
								<th>VALIDADE</th>
                                <th>SERVIDORES</th>
                                <th>DONO</th>
                                <th>OPÇÕES</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php


                            $SQLUsuario = "SELECT * FROM usuario   where  tipo = 'revenda' and subrevenda='sim' and id_mestre='" . $usuario['id_usuario'] . "' ORDER BY ativo ";
                            $SQLUsuario = $conn->prepare($SQLUsuario);
                            $SQLUsuario->execute();


                            // output data of each row
                            if (($SQLUsuario->rowCount()) > 0) {

                                while ($row = $SQLUsuario->fetch()) {
                                    $class = "class='btn btn-danger'";
                                    $status = "";
                                    $color = "";
                                    $contas = 0;
                                    $servidores = 0;
                                    if ($row['ativo'] == 1) {
                                        $status = "Ativo";
                                        $sts = "success";
                                        $info = "info";
                                        $class = "class='btn-sm btn-primary'";
                                    } else {
                                        $status = "Desativado";
                                        $sts = "danger";
                                        $info = "danger";
                                        $color = "bgcolor='#FF6347'";
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
									
									if(($SQLServidores->rowCount()) > 0){
                                    while($roww = $SQLServidores->fetch()){
									
									//Calcula os dias restante
                                    $data_atual = date("Y-m-d");
                                    $data_validade = $roww['validade'];
                                    if($data_validade > $data_atual){
                                    $data1 = new DateTime( $data_validade );
                                    $data2 = new DateTime( $data_atual );
                                    $dias_acesso = 0;
                                    $diferenca = $data1->diff( $data2 );
                                    $ano = $diferenca->y * 364 ;
                                    $mes = $diferenca->m * 30;
                                    $dia = $diferenca->d;
                                    $dias_acesso = $ano + $mes + $dia;

                                    }else{
                                    $dias_acesso = 0;
                                    }
						            }

                                    }

                            ?>

                                    <div class="modal fade" id="squarespaceModal<?php echo $row['id_usuario']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel"> Notificar Usuário</h3>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->
                                                    <form action="pages/usuario/notifica_sub.php" method="post">
                                                        <div class="mb-1">
                                                            <input name="idsubrev" type="hidden" value="<?php echo $row['id_usuario']; ?>">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">Usuário</label>
                                                                <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row['nome']; ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="mb-1">
                                                                <label for="exampleInputEmail1">Tipo de Alerta</label>
                                                                <select size="1" name="tipo" class="form-select">
                                                                    <option value="1" selected=selected>Aviso</option>
                                                                    <option value="2">Outros</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Mensagem</label>
                                                            <textarea class="form-control" name="msg" rows=5 cols=20 wrap="off" placeholder="Digite..." required></textarea>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                    <button class="btn btn-success">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="criarfatura<?php echo $row['id_usuario']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel"><i class="fa fa-money"></i> Cria uma Fatura</h3>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->
                                                    <form name="editaserver" action="pages/subrevenda/criafatura_subrv.php" method="post">
                                                        <input name="idcontausuario" type="hidden" value="<?php echo $row['id_usuario']; ?>">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Usuário</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row['nome']; ?>" disabled="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Tipo</label>
                                                            <select size="1" name="tipo" class="form-select">
                                                                <option value="3" selected=selected>Revenda</option>
                                                                <option value="2">Outros</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Valor</label>
                                                            <input type="number" class="form-control" name="valor" value="1">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Desconto</label>
                                                            <input type="number" class="form-control" name="desconto" value="0">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Prazo</label>
                                                            <input type="number" class="form-control" name="prazo" value="1">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Descrição</label>
                                                            <textarea class="form-control" name="msg" rows="3" cols="20" wrap="off" placeholder="Digite..."></textarea>
                                                        </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success">Confirmar</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                    
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

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
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $row['senha']; ?>
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
                <?php if ($dias_acesso > 10) {
                                        $c_dias = 'info';
                                    } elseif ($dias_acesso == 0) {
                                        $c_dias = 'danger';
                                    } else {
                                        $c_dias = 'warning';
                                    }
                ?>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-<?php echo $c_dias; ?>">
                        <?php echo $dias_acesso . "  dias   "; ?>
                    </span>
                </span>
            </td>
			<td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-<?php echo $info; ?>">
                                                    <?php echo $total_acesso_ssh; ?>
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
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="home.php?page=usuario/perfil&id_usuario=<?php echo $row['id_usuario']; ?>" class="btn-sm btn-primary"><i data-feather='eye'></i></a>&nbsp;&nbsp;&nbsp;
                                                <a data-bs-toggle="modal" data-bs-target="#squarespaceModal<?php echo $row['id_usuario']; ?>" class="btn-sm btn-warning"><i data-feather='message-square'></i></a>
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