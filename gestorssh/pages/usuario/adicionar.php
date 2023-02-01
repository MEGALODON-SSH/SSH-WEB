<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>
<script>
    $(document).ready(function($) {
        //$("[data-mask]").inputmask();
        //Inputmask().mask(document.querySelectorAll("input"));
        $('#celular').inputmask("(99) 99999-9999"); //static mask
    });
</script>

<?php
    $SQL = "SELECT permitir_demo FROM usuario where id_usuario = '" . $_SESSION['usuarioID'] . "'";
    $SQL = $conn->prepare($SQL);
    $SQL->execute();
    $row = $SQL->fetch();
    $perm_v2 = $row['permitir_demo'];
?>

<?php
 function geraSenha(){
				

    $salt = "1234567890";
    srand((double)microtime()*1000000); 

    $i = "";
    $pass = "";
    while($i <= 7){

        $num = rand() % 10;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;

    }
    
    
    

    return $pass;

}
$senha_ssh = geraSenha();
 
?>

<!-- Input with Icons start -->
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Adicionar Subrevendedor ao Painel</h4>
                </div>
                <div class="card-body">
                    <form data-toggle="validator" action="pages/system/funcoes.usuario.php" method="GET" role="form">
                        <div class="row">
                            <div class="col-12">
                                <p>Adicionar acesso ao Painel!</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Nome</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" name="nome" id="nome" class="form-control" minlength="4" placeholder="Digite o Nome..." required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="text" name="celular" id="celular" placeholder="Digite os 11 Digítos..." value="(11) 99999-9999" class="form-control" minlength="4" maxlength="16" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Usuário</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" name="login" id="login" class="form-control" minlength="4" placeholder="Digite o Usuario..." required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="first-name-icon">Senha</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="key"></i></span>
                                        <input type="text" min="4" max="32" class="form-control" name="senha" data-minlength="4" id="senha" placeholder="Digite a Senha..." required="" value="<?php echo $senha_ssh;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-12 text-center">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="owner" name="owner" value="<?php echo $_SESSION['usuarioID']; ?>">
                                    <input type="hidden" class="form-control" id="diretorio" name="diretorio" value="../../home.php?page=subrevenda/revendedores">
                                    <input type="hidden" class="form-control" id="op" name="op" value="new">
                                    <input type="hidden" class="check" id="radio" name="tipo" value="1">
                                </div>
                            </div>
                            <?php if($perm_v2 == 1){?>
                            <div class="col-12">
                                <div class="mb-2">
                                    <div class="row custom-options-checkable g-2">
                                        <div class="col-md-6">
                                            <input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon2" value="0" checked />
                                            <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                                                <i data-feather="shield" class="font-large-1 mb-75"></i>
                                                <span class="custom-option-item-title h4 d-block">ACESSO SSH</span>
                                                <small>Nesse modo ele poderá criar apenas contas ssh</small>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon3" value="1" />
                                            <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
                                                <i data-feather="link" class="font-large-1 mb-75"></i>
                                                <span class="custom-option-item-title h4 d-block">ACESSO SSH E V2RAY</span>
                                                <small>Nesse modo ele poderá criar contas ssh e v2ray</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }else {?>
                                <input type="hidden" class="check" id="radio" name="acesso" value="0">
                            <?php }?>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success me-1 waves-effect waves-float waves-light">Criar</button>
                                <button type="reset" class="btn btn-danger waves-effect">Limpar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Input with Icons end -->