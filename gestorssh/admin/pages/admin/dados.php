<?php

	if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
$procnoticias= "select * FROM noticias where status='ativo'";
$procnoticias = $conn->prepare($procnoticias);
$procnoticias->execute();


        // Clientes
        $SQLbuscaclientes= "select * from usuario where tipo='vpn'";
        $SQLbuscaclientes = $conn->prepare($SQLbuscaclientes);
        $SQLbuscaclientes->execute();
        $totalclientes = $SQLbuscaclientes->rowCount();
        // Revendedores
        $SQLbuscarevendedores= "select * from  usuario where tipo='revenda'";
        $SQLbuscarevendedores = $conn->prepare($SQLbuscarevendedores);
        $SQLbuscarevendedores->execute();
        $totalrevendedores = $SQLbuscarevendedores->rowCount();
        // Servidores
        $SQLbuscaservidores= "select * from  servidor";
        $SQLbuscaservidores= $conn->prepare($SQLbuscaservidores);
        $SQLbuscaservidores->execute();
        $totalservidores = $SQLbuscaservidores->rowCount();
?>
<!-- Input with Icons start -->
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Alterar Informações admin (Atenção!)</h4>
                </div>
                <div class="card-body">
						<form action="pages/admin/alterar.php" method="POST" role="form">
                        <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Nome</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" name="nome" id="nome" class="form-control" minlength="4"  value="<?php echo $administrador['nome'];?>" required>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Login</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user-check"></i></span>
                                        <input type="text" disabled class="form-control" minlength="6" value="<?php echo $administrador['login'];?>" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="text" name="email" id="email" minlength="5" class="form-control" value="<?php echo $administrador['email'];?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Link do Painel</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather='link-2'></i></span>
                                        <input type="text" name="site" id="site" minlength="5" value="<?php echo $administrador['site'];?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Senha atual</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather='key'></i></span>
                                        <input type="password" name="senhaantiga" id="senhaantiga" minlength="5" placeholder="Digite a Senha atual..." class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="first-name-icon">Nova senha</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather='key'></i></span>
                                        <input type="password" name="novasenha" id="novasenha" minlength="5" placeholder="Digite a Nova Senha..." class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success me-1 waves-effect waves-float waves-light">Salvar</button>
                                <button type="reset" class="btn btn-danger waves-effect">Limpar</button>
                            </div>

                        </div>
                    </div>
                  </form>
                
            </div>
        </div>
    </div>
</section>
<!-- Input with Icons end -->
