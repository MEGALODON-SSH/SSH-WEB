<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

$buscasmtp = "SELECT * FROM smtp WHERE usuario_id='" . $_SESSION['usuarioID'] . "'";
$buscasmtp = $conn->prepare($buscasmtp);
$buscasmtp->execute();
$smtp = $buscasmtp->fetch();

$conta = $buscasmtp->rowCount();

?>
<script language="JavaScript">
    <!--
    function desabilitar() {
        with(document.form) {
            qtd_ssh.disabled = true;
        }
    }

    function habilitar() {
        with(document.form) {

            qtd_ssh.disabled = false;

        }
    }
    // 
    -->
</script>
<!-- Input with Icons start -->
<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title font-medium-2"><i class="fad fa-at text-info font-large-1"></i> Enviar email</h1>
                </div>
                <div class="card-content">
                    <form action="pages/email/enviandoemail.php" method="POST" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Topo de serviço
                                        </div>
                                        <fieldset class="form-group position-relative">
                                            <select class="form-select" name="tipomodelo">
                                                <option value="1">Suporte Tecnico</option>
                                                <option value="2">Entrega de Contas</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Tipo de conta
                                        </div>
                                        <fieldset class="form-group position-relative">
                                            <select class="form-select" name="tipoconta">
                                                <option value="1" selected=selected>Conta SSH</option>
                                                <option value="2">Acesso Painel</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Assunto :
                                        </div>
                                        <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                            <input type="text" class="form-control" id="assunto" name="assunto" value="Acesso de SSH Liberado" placeholder="Digite um Assunto EX: Compra de SSH ">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Validade :
                                        </div>
                                        <fieldset class="form-group position-relative input-divider-right">
                                            <input type="text" class="form-control" id="validade" name="validade" value="30 Dias" placeholder="Validade 30">
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Login :
                                        </div>
                                        <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                            <input type="text" class="form-control" id="login" name="login" placeholder="Digite o Login">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Senha :
                                        </div>
                                        <fieldset class="form-group position-relative input-divider-right">
                                            <input class="form-control" id="senha" name="senha" placeholder="Digite a Senha">
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Email :
                                        </div>
                                        <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                            <input required="required" type="text" class="form-control" name="email" placeholder="Digite seu email e Selecione o Servidor ao lado">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        <div class="mb-0">
                                            Servidor de email
                                        </div>
                                        <fieldset class="form-group position-relative input-divider-right">
                                            <select class="form-select" name="servidoremail" data-toggle="dropdown" aria-expanded="false">
                                                <option value="1" selected=selected>Eu Decido</a></option>
                                                <option value="2">@Gmail.com</a></option>
                                                <option value="3">@Outlook.com</a></option>
                                                <option value="4">@Hotmail.com</a></option>
                                                <option value="5">@Yahoo.com.br</a></option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <div class="mb-2">
                                        <div class="mb-0">
                                            Conteudo do Email :
                                        </div>
                                        <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                            <textarea class="form-control" name="msg" id="msg" rows="6" placeholder="Obrigado pela preferencia, prezamos pela qualidade de sua internet..">Obrigado pela preferencia, prezamos pela qualidade de conexao.</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-12 text-center">
                                    <button type="submit" class="btn btn-success">Enviar</button>
                                    <button type="button" class="btn btn-info"><a class="text-white" href="home.php?page=email/1etapasmtp">Configurar SMTP</a></button>
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