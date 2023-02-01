<!-- Input with Icons start -->
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Adicionar servidor ao cliente</h4>
                </div>
                <div class="card-content">
                    <form action="pages/subrevenda/addservidor_exe.php" method="POST" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="demo-spacing-0 mb-3">
                                        <div class="alert alert-warning" role="alert">
                                            <div class="alert-body d-flex align-items-center">
                                                <i data-feather="info" class="me-50"></i>
                                                <span> Logo abaixo você entrega parte de seu limite de um dos seus servidores ao seu Subrevendedor não é possivel entregar mais que o seu limite disponivel!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="basicSelect">Servidor</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="server"></i></span>
                                            <select class="form-select" name="servidor" id="servidor">
                                                <option selected="selected">Selecione um Servidor</option>
                                                <?php
                                                $SQLAcesso = "select * from acesso_servidor where id_usuario='" . $usuario['id_usuario'] . "'  ";
                                                $SQLAcesso = $conn->prepare($SQLAcesso);
                                                $SQLAcesso->execute();
                                                if (($SQLAcesso->rowCount()) > 0) {
                                                    // output data of each row
                                                    while ($row_srv = $SQLAcesso->fetch()) {
                                                        $contas_ssh_criadas = 0;

                                                        $SQLServidor = "select * from servidor WHERE id_servidor = '" . $row_srv['id_servidor'] . "' ";
                                                        $SQLServidor = $conn->prepare($SQLServidor);
                                                        $SQLServidor->execute();
                                                        $servidor = $SQLServidor->fetch();


                                                        //Carrega contas SSH criadas
                                                        $SQLContasSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row_srv['id_servidor'] . "' and id_usuario='" . $usuario['id_usuario'] . "' ";
                                                        $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                                        $SQLContasSSH->execute();
                                                        $SQLContasSSH = $SQLContasSSH->fetch();
                                                        $contas_ssh_criadas += $SQLContasSSH['quantidade'];

                                                        //Carrega usuario sub
                                                        $SQLUsuarioSub = "SELECT * FROM usuario WHERE id_mestre ='" . $usuario['id_usuario'] . "' and subrevenda='nao'";
                                                        $SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
                                                        $SQLUsuarioSub->execute();


                                                        if (($SQLUsuarioSub->rowCount()) > 0) {
                                                            while ($row = $SQLUsuarioSub->fetch()) {
                                                                $SQLSubSSH = "select sum(acesso) AS quantidade  from usuario_ssh WHERE id_usuario = '" . $row['id_usuario'] . "' and id_servidor='" . $row_srv['id_servidor'] . "' ";
                                                                $SQLSubSSH = $conn->prepare($SQLSubSSH);
                                                                $SQLSubSSH->execute();
                                                                $SQLSubSSH = $SQLSubSSH->fetch();
                                                                $contas_ssh_criadas += $SQLSubSSH['quantidade'];
                                                            }
                                                        }

                                                        $resta = $row_srv['qtd'] - $contas_ssh_criadas;
                                                        //echo $resultado;

                                                ?>

                                                        <option value="<?php echo $row_srv['id_acesso_servidor']; ?>"> <?php echo $servidor['nome']; ?> - Limite: <?php echo $resta; ?> </option>

                                                    <?php }
                                                } else { ?>
                                                    <option value="nada">Nenhum Servidor</option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        </dev>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="basicSelect">Sub Revendedor</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="users"></i></span>
                                            <select class="form-select" name="subusuario" id="subusuario">
                                                <option selected="selected">Selecione</option>
                                                <?php
                                                $SQL = "SELECT * FROM usuario where id_mestre='" . $usuario['id_usuario'] . "' and subrevenda='sim'";
                                                $SQL = $conn->prepare($SQL);
                                                $SQL->execute();
                                                if (($SQL->rowCount()) > 0) {
                                                    // output data of each row
                                                    while ($row = $SQL->fetch()) {

                                                        $SQLServidor = "select * from acesso_servidor  WHERE id_usuario = '" . $row['id_usuario'] . "' ";
                                                        $SQLServidor = $conn->prepare($SQLServidor);
                                                        $SQLServidor->execute();
                                                        $acesso_server = $SQLServidor->rowCount();
                                                ?>
                                                        <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['login']; ?> - Servidores Alocados: <?php echo $acesso_server; ?> </option>

                                                    <?php }
                                                } else { ?>
                                                    <option value="nada">Nenhum Subrevendedor</option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        </dev>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Validade em Dias</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='calendar'></i></span>
                                            <input type="number" min="1" max="366" name="dias" id="dias" class="form-control" placeholder="31" value="31" required="required">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="mb-2">
                                        <label class="form-label" for="country-floating">Limite</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='smartphone'></i></span>
                                            <input type="number" min="5" max="999" name="limite" id="limite" class="form-control" placeholder="5" value="5" required="required">
                                        </div>
                                    </div>
                                </div>
                                
                            
                                <input type="hidden" class="form-control" id="diretorio" name="diretorio" value="../../admin/home.php?page=ssh/adicionar">
                                <input type="hidden" class="form-control" id="owner" name="owner" value="<?php echo $accessKEY; ?>">

                                <div class="col-sm-12 col-12	text-center">
                                    <button type="submit" class="btn btn-success">Adicionar</button>
                                    <button type="reset" class="btn btn-danger">Limpar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Input with Icons end -->