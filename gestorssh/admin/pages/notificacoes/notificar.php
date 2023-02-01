<?php

	if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}


?>

<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notificar Revendedor</h4>
                </div>
                <div class="card-body">
                    <form role="form" name="form" id="form" action="pages/notificacoes/notificar_revendedor.php" method="post">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-0">
                                        Selecione o Revendedor
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control select2" name="revendedor">
                                            <option selected=selected>Selecione</option>
                                            <?php

                                            $SQLUsuario = "SELECT * FROM usuario where tipo='revenda' and subrevenda='nao'";
                                            $SQLUsuario = $conn->prepare($SQLUsuario);
                                            $SQLUsuario->execute();

                                            if (($SQLUsuario->rowCount()) > 0) {
                                                // output data of each row
                                                while($row = $SQLUsuario->fetch()) {
                                                    if($row['id_usuario'] != $usuario_sistema['id_usuario']){

                                                        $SQLserv = "SELECT * FROM acesso_servidor where id_usuario='".$row['id_usuario']."'";
                                                        $SQLserv = $conn->prepare($SQLserv);
                                                        $SQLserv->execute();
                                                        $sv=$SQLserv->rowCount();
                                                        ?>
                                                        <option value="<?php echo $row['id_usuario'];?>" ><?php echo ucfirst($row['nome']);?> - Servidores: <?php echo $sv;?> </option>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6 col-12">
                                <div class="mb-1">
                                    <div class="mb-0">
                                        Selecione o Tipo
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control select2" name="tipo">
                                            <option selected=selected>Selecione</option>
                                            <option value="1">Fatura</option>
                                            <option value="2">Outros/Servidores</option>
                                        </select>
                                    </fieldset>
                                </div>
                                </div>
                                <div class="col-12 text-center">
                                <div class="mb-2">
                                    <div class="mb-0">
                                        Mensagem
                                    </div>
                                    <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                        <textarea class="form-control"  name="msg" rows="5" placeholder="Digite ..."></textarea>
                                        <div class="form-control-position">
                                            <i class="fad fa-at"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                </div>
                                <div class="col-sm-12 col-12 text-center">
                                    <button type="submit" class="btn btn-success">Notificar</button>
                                    <button type="reset" class="btn btn-danger">Limpar</button>
                                </div>
                            </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</section>

<!-- Input with Icons start -->
<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title font-medium-2"><i class="fad fa-bell text-warning font-large-1"></i> Notificar Todos</h1>
                </div>
                <div class="card-content">
                    <form role="form" name="form" id="form" action="pages/notificacoes/notificar_todos.php" method="post">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" class="form-control" name="clientes" value="1">
                                <input type="hidden" class="form-control" name="tipo" value="2">
                                <div class="col-12 text-center">
                                <div class="mb-2">
                                    <div class="mb-0">
                                        Mensagem
                                    </div>
                                    <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                        <textarea class="form-control"  name="msg" rows="5" placeholder="Digite ..."></textarea>
                                        <div class="form-control-position">
                                            <i class="fad fa-at"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                </div>
                                <div class="col-sm-12 col-12 text-center">
                                    <button type="submit" class="btn btn-success">Notificar</button>
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
