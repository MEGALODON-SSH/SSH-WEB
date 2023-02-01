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
                    <h4 class="card-title">Tickets Abertos</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>N° DE CHAMADO</th>
                                <th>STATUS</th>
                                <th>ABERTO POR</th>
                                <th>TIPO</th>
                                <th>MOTIVO</th>
                                <th>ULTIMA ATUALIZACAO</th>
                                <th>ACAO</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            $SQLUsuario = "SELECT * FROM chamados   where  status = 'aberto' and usuario_id='" . $_SESSION['usuarioID'] . "' ORDER BY id desc";
                            $SQLUsuario = $conn->prepare($SQLUsuario);
                            $SQLUsuario->execute();


                            // output data of each row
                            if (($SQLUsuario->rowCount()) > 0) {

                                while ($row = $SQLUsuario->fetch()) {

                                    $SQLUsuario2 = "SELECT * FROM usuario   where  id_usuario = '" . $row['usuario_id'] . "'";
                                    $SQLUsuario2 = $conn->prepare($SQLUsuario2);
                                    $SQLUsuario2->execute();
                                    $user2 = $SQLUsuario2->fetch();

                                    switch ($row['tipo']) {
                                        case 'contassh':
                                            $tipo = 'SSH';
                                            break;
                                        case 'revendassh':
                                            $tipo = 'REVENDA SSH';
                                            break;
                                        case 'usuariossh':
                                            $tipo = 'USUÁRIO SSH';
                                            break;
                                        case 'servidor':
                                            $tipo = 'SERVIDOR';
                                            break;
                                        case 'outros':
                                            $tipo = 'SUPORTE';
                                            break;
                                        default:
                                            $tipo = 'Erro';
                                            break;
                                    }

                                    $data1 = explode(' ', $row['data']);
                                    $data2 = explode('-', $data1[0]);
                                    $dia = $data2[2];
                                    $mes = $data2[1];
                                    $ano = $data2[0];


                            ?>


                                    <div class="modal fade" id="squarespaceModal2<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel">Encerrar Ticket</h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->
                                                    <form name="editaserver" action="pages/chamados/encerrando_chamado.php" method="post">
                                                        <input name="chamado" type="hidden" value="<?php echo $row['id']; ?>">
                                                        <input name="diretorio" type="hidden" value="../../home.php?page=chamados/abertas">
                                                        <div class="form-group">
                                                            <p>Tem certeza que deseja encerrar o Ticket?</p>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-danger">Encerrar Ticket</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="squarespaceModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel">Ticket suporte</h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->

                                                    <input name="chamado" type="hidden" value="<?php echo $row['id']; ?>">
                                                    <input name="diretorio" type="hidden" value="../../home.php?page=chamados/abertas">
                                                    <div class="mb-1">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Motivo</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row['motivo']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="mb-1">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Mensagem</label>
                                                            <textarea class="form-control" rows=5 cols=20 wrap="off" disabled><?php echo $row['mensagem']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Aguardando Resposta</label>
                                                        <p>Você precisa aguardar uma Resposta do Administrador para Responder</p>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-success" data-dismiss="modal">Confirmar</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <tr>
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td><small class="label label-success"><?php echo ucfirst($row['status']); ?></small></td>
                                        <td><?php echo $row['login']; ?></td>
                                        <td><?php echo $tipo; ?></td>
                                        <td><?php echo $row['motivo']; ?></td>
                                        <td><?php echo $dia; ?>/<?php echo $mes; ?> - <?php echo $ano; ?></td>

                                        <td>
                                        <div class="btn-group mr-2" role="group" aria-label="First group">
                                            <a data-bs-toggle="modal" data-bs-target="#squarespaceModal<?php echo $row['id']; ?>" class="btn-sm btn-primary"><i data-feather='edit-2'></i></a>&nbsp;&nbsp;&nbsp;
                                            <a data-bs-toggle="modal" data-bs-target="#squarespaceModal2<?php echo $row['id']; ?>" class="btn-sm btn-danger"><i data-feather='trash'></i></a>
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