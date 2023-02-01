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
                    <h4 class="card-title">Tickets Encerrados</h4>
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
                                <th>INFORMACOES</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php




                            $SQLUsuario = "SELECT * FROM chamados   where  status = 'encerrado' and usuario_id='" . $_SESSION['usuarioID'] . "' ORDER BY id desc";
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
                                            $tipo = 'OUTROS';
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

                                    <div class="modal fade" id="squarespaceModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel"><i class="fa fa-info"></i> Informações do Ticket</h3>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->
                                                    <div class="form-group mb-1">
                                                        <label for="exampleInputEmail1">Motivo</label>
                                                        <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row['motivo']; ?>" disabled>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label for="exampleInputEmail1">Mensagem</label>
                                                        <textarea class="form-control" rows=5 cols=20 wrap="off" disabled><?php echo $row['mensagem']; ?></textarea>
                                                    </div>

                                                    <div class="form-group mb-1">
                                                        <label for="exampleInputEmail1">Tipo</label>
                                                        <select size="1" class="form-select" disabled>
                                                            <option selected=selectes><?php echo $tipo; ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label for="exampleInputPassword1">Resposta da Administração</label>
                                                        <textarea class="form-control" rows=5 cols=20 wrap="off" placeholder="Deixe uma resposta para ele visualizar" required disabled><?php echo $row['resposta']; ?></textarea>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-default btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Sair</button>
                                                            </form>
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

                                        <a data-bs-toggle="modal"  data-bs-target="#squarespaceModal<?php echo $row['id'];?>" class="btn-sm btn-primary"><i data-feather='eye'></i></a> 
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