<?php

	if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
$my_id = $_SESSION['usuarioID'];
?>
<script>
    $(document).ready(function ($) {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
<!-- Input with Icons start -->
<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title font-medium-2">Ticket Suporte</h1>
                </div>
                <div class="card-content">
                    <form class="" action="pages/chamados/abrindo_chamado.php" method="post">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" class="form-control" id="tipo" name="tipo" value="5">

                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        Assunto
                                    </div>
                                    <fieldset class="form-group position-relative has-icon-left input-divider-left">
                                        <input type="text" name="motivo" placeholder="Fale qual é o motivo Principal..." minlength="5" class="form-control" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-phone"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                
                                <div class="col-sm-6 col-12">
                                    <div class="mb-1">
                                        Informacoes
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <input type="text" class="form-control" name="login" minlength="4" placeholder="Digite o Login ou o Servidor com Problemas" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                
                                <div class="col-sm-12 col-12">
                                    <div class="mb-1">
                                        Informe sua mensagem
                                    </div>
                                    <fieldset class="form-group position-relative input-divider-right">
                                        <textarea class="form-control" name="problema" placeholder="Fale mais sobre oquê está acontecento..." rows=5 cols=20 wrap="off" required></textarea>
                                        <input  type="hidden" class="form-control" id="diretorio" name="diretorio"  value="../../admin/home.php?page=ssh/adicionar">
                                        <input  type="hidden" class="form-control" id="owner" name="owner"  value="<?php echo $accessKEY;?>">
                                        <input  type="hidden" class="form-control" id="my_id" name="my_id"  value="<?php echo $my_id;?>">
                                        <div class="form-control-position">
                                            <i class="feather icon-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-12 col-12	text-center">
                                    <button type="submit" class="btn btn-success">Criar Ticket</button>
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
