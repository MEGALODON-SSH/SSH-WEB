<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>


<!-- Input with Icons start -->
<div class="active"><a class="d-flex align-items-center" href="home.php"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a>
</div>
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Adicionar servidor ao cliente</h4>
                </div>
                <div class="card-content">
                    <form action="pages/usuario/addservidor_exe.php" method="POST" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="demo-spacing-0 mb-2">
                                    <div class="alert alert-info" role="alert">
                                        <div class="alert-body d-flex align-items-center">
                                            <i data-feather="info" class="me-50"></i>
                                            <span> Logo Abaixo vc pode adicionar um servidor para um cliente com validade e limite!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="basicSelect">Servidor</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="server"></i></span>
                                            <select class="form-select" name="servidor" id="servidor">
                                                <?php
                                                $SQLAcesso = "select * from servidor where tipo='premium'";
                                                $SQLAcesso = $conn->prepare($SQLAcesso);
                                                $SQLAcesso->execute();


                                                if (($SQLAcesso->rowCount()) > 0) {
                                                    while ($row_srv = $SQLAcesso->fetch()) {
                                                ?>
                                                        <option value="<?php echo $row_srv['id_servidor']; ?>"> <?php echo $row_srv['nome']; ?> - <?php echo $row_srv['ip_servidor']; ?></option>
                                                    <?php }
                                                } else { ?>
                                                    <option>Nenhum Servidor</option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="basicSelect">Revendedor</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="users"></i></span>
                                            <select class="form-select" name="revendedor" id="revendedor">
                                                <?php
                                                $SQL = "SELECT * FROM usuario where tipo='revenda' and subrevenda='nao' and id_mestre=0";
                                                $SQL = $conn->prepare($SQL);
                                                $SQL->execute();

                                                if (($SQL->rowCount()) > 0) {
                                                    while ($row = $SQL->fetch()) {
                                                        $SQLServidor = "select * from acesso_servidor  WHERE id_usuario = '" . $row['id_usuario'] . "' ";
                                                        $SQLServidor = $conn->prepare($SQLServidor);
                                                        $SQLServidor->execute();
                                                        $acesso_server = $SQLServidor->rowCount();
                                                ?>
                                                        <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['login']; ?> - Servidores Alocados: <?php echo $acesso_server; ?> </option>

                                                    <?php }
                                                } else { ?>
                                                    <option>Nenhum Revendedor</option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Validade em Dias</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='calendar'></i></span>
                                            <input type="number" name="dias" id="dias" class="form-control" placeholder="30" value="30" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-2">
                                        <label class="form-label" for="country-floating">Limite</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='smartphone'></i></span>
                                            <input type="number" name="limite" id="limite" placeholder="30" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success me-1 waves-effect waves-float waves-light">Adicionar</button>
                                    <button type="reset" class="btn btn-danger waves-effect">Limpar</button>
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