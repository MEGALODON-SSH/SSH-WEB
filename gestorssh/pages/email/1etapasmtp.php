<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

$buscasmtp = "SELECT * FROM smtp_usuarios WHERE usuario_id='" . $_SESSION['usuarioID'] . "'";
$buscasmtp = $conn->prepare($buscasmtp);
$buscasmtp->execute();
$smtp = $buscasmtp->fetch();

$conta = $buscasmtp->rowCount();

?>
<!-- Input with Icons start -->
<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title font-medium-2"><i class="fad fa-pencil text-success font-large-1"></i> Alterar Informações</h1>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form action="pages/email/configurasmtp.php" method="POST" role="form">
                            <div class="row">
                                <div class="col-12">
                                    <p>Digite abaixo os novos dados do seu servidor de email</p>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-1">
                                        Nome de Sua Empresa

                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input required="required" type="text" value="<?php echo $smtp['empresa']; ?>" class="form-control" id="nomeempresa" name="nomeempresa" placeholder="Ex: sshplus">
                                            <div class="form-control-position">
                                                <i class="fad fa-user-tie"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-1">
                                        Servidor

                                        <fieldset class="form-group position-relative">
                                            <input required="required" type="text" value="<?php echo $smtp['servidor']; ?>" class="form-control" id="servidor" name="servidor" placeholder="Ex: smtp.gmail.com">
                                            <div class="form-control-position">
                                                <i class="fad fa-server"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-1">
                                        Porta

                                        <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                            <input required="required" type="text" value="<?php echo $smtp['porta']; ?>" class="form-control" id="porta" name="porta" placeholder="Ex: 465 SSL ou 587 TLS">
                                            <div class="form-control-position">
                                                <i class="fad fa-globe-americas"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-1">
                                        SSL

                                        <fieldset class="form-group position-relative input-divider-right">
                                            <input required="required" type="text" value="<?php echo $smtp['ssl_secure']; ?>" class="form-control" id="ssl" name="ssl" placeholder="Ex: SSL ou TLS">
                                            <div class="form-control-position">
                                                <i class="fad fa-at"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-1">
                                        Email

                                        <fieldset class="form-group position-relative input-divider-right">
                                            <input required="required" type="text" value="<?php echo $smtp['email']; ?>" class="form-control" id="email" name="email" placeholder="Email do servidor">
                                            <div class="form-control-position">
                                                <i class="fad fa-at"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-2">
                                        Senha

                                        <fieldset class="form-group position-relative input-divider-right">
                                            <input class="form-control" type="password" value="<?php echo $smtp['senha']; ?>" id="senha" name="senha" placeholder="Senha do Email">
                                            <div class="form-control-position">
                                                <i class="fad fa-key-skeleton"></i>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-12 text-center">
                                    <button type="submit" class="btn btn-success">Salvar</button>
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