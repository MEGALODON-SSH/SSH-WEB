<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

function meulink(){
    if(isset($_SERVER['SERVER_ADDR'])){
        return $_SERVER['SERVER_ADDR'];
    }
    elseif(isset($_SERVER['LOCAL_ADDR'])){
        return $_SERVER['LOCAL_ADDR'];
    }
    else{
        return false;
    }
}

?>

<script>
    function ValidateIPaddress(inputText)
    {
        var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        if(inputText.value.match(ipformat))
        {
            document.form1.ip.focus();
            return true;
        }
        else
        {
            alert("Endereço IP Invalido!");
            document.form1.ip.focus();return false;
        }
    }
</script>
<!-- Input with Icons start -->
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Adicionar servidor ao Painel</h4>
                </div>
                <div class="card-content">
                    <form action="pages/servidor/adicionar_exe.php" method="POST" enctype="multipart/form-data" role="form">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Nome do servidor</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='server'></i></span>
                                            <input type="text" id="nomesrv" name="nomesrv" class="form-control" minlength="4" placeholder="Ex: Brasil-1" required>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Endereço de IP</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='radio'></i></span>
                                            <input type="text" name="ip" id="ip" class="form-control" minlength="4" placeholder="Digite o IP" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Usuário root</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='user'></i></span>
                                            <input type="text" name="login" id="login" value="root" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Senha root</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='key'></i></span>
                                            <input type="password" min="4" max="64" class="form-control"  name="senha" data-minlength="6" id="senha" placeholder="Digite a Senha" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="basicSelect">Região global</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='map'></i></span>
                                            <select class="form-select" name="regiao" data-placeholder="Selecione a regiao" tabindex="1">
                                                <option value="1">Asia</option>
                                                <option value="2" selected>America</option>
                                                <option value="3">Europa</option>
                                                <option value="4">Antártida</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Localização</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='map-pin'></i></span>
                                            <input type="text" maxlength="32" name="localiza" id="localiza" placeholder="Ex: São Paulo" value="São Paulo" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Validade</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='calendar'></i></span>
                                            <input type="number"  min="1" max="9999" name="validade" id="validade" placeholder="Ex: 9999" value="9999" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="country-floating">Limite</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='smartphone'></i></span>
                                            <input type="number"  min="1" max="9999" name="limite" id="limite" placeholder="Ex: 9999" value="9999" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-12">
                                    <div class="mb-2">
                                        <label class="form-label" for="country-floating">Link do Painel</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather='link-2'></i></span>
                                            <input type="text" name="siteserver" id="siteserver" value="<?php echo meulink(); ?>" class="form-control" placeholder="Digite seu link ou IP" required>
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
