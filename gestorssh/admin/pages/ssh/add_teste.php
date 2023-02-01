<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>

<?php
 function geraUser(){
				

    $salt = "1234567890";
    srand((double)microtime()*1000000); 

    $i = "";
    $pass = "";
    while($i <= 2){

        $num = rand() % 10;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;

    }
    
    
    

    return $pass;

}
$user_ssh = geraUser();
 
?>

<?php
 function geraSenha(){
				

    $salt = "1234567890";
    srand((double)microtime()*1000000); 

    $i = "";
    $pass = "";
    while($i <= 5){

        $num = rand() % 10;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;

    }
    
    
    

    return $pass;

}
$senha_ssh = geraSenha();
 
?>

<div class="active"><a class="d-flex align-items-center" href="home.php"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a>
</div>
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Criar Teste SSH</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="../pages/system/conta.ssh.php" method="POST" role="form">
                        <div class="row">
                            <div class="demo-spacing-0 mb-2">
                                    <div class="alert alert-warning" role="alert">
                                        <div class="alert-body d-flex align-items-center">
                                            <i data-feather="info" class="me-50"></i>
                                            <span>Esse login será excluído automaticamente, não adianta acrescentar dias posteriormente!!!</span>
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
                                            $SQLAcesso = "select * from servidor  ";
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
                                                    $SQLContasSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row_srv['id_servidor'] . "'  ";
                                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                                    $SQLContasSSH->execute();
                                                    $SQLContasSSH = $SQLContasSSH->fetch();
                                                    $contas_ssh_criadas += $SQLContasSSH['quantidade'];
                                            ?>
                                                    <option selected="selected" value="<?php echo $row_srv['id_servidor']; ?>"> <?php echo $servidor['nome']; ?> - <?php echo $servidor['ip_servidor']; ?> - <?php echo $contas_ssh_criadas; ?> Conexões </option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="basicSelect">Quem será o Dono? (Crie uma revenda caso não exista nenhuma!)</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="users"></i></span>
                                        <select class="form-select" name="usuario" id="usuario">
                                            <?php
                                            $SQL = "SELECT * FROM usuario where id_mestre=0";
                                            $SQL = $conn->prepare($SQL);
                                            $SQL->execute();
                                            if (($SQL->rowCount()) > 0) {
                                                // output data of each row
                                                while ($row = $SQL->fetch()) { ?>
                                                    <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['login']; ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="basicSelect">Tempo de Duração</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                        <select class="form-select" name="tempuser" id="tempuser">
                                        <option value="30">30 Minutos</option>
                                            <option value="60">1 hora</option>
                                            <option value="180">3 horas</option>
                                            <option selected="selected" value="360">6 horas <small>(recomendado)</small></option>
                                            <option value="720">12 horas</option>
                                            <option value="1440">24 horas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="dias" id="dias" class="form-control" placeholder="30" value="2">
                            <input type="hidden" name="acessos" id="acessos" placeholder="1" class="form-control" value="1">

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="first-name-icon">Usuário</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" name="login_ssh" id="login_ssh" class="form-control" data-minlength="4" data-maxlength="32" placeholder="Digite a Senha" required="" value="teste<?php echo $user_ssh;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="country-floating">Senha</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather='lock'></i></span>
                                        <input type="text" min="4" max="32" class="form-control" name="senha_ssh" data-minlength="4" id="senha_ssh" placeholder="Digite a Senha" required="" value="<?php echo $senha_ssh;?>">
                                    </div>
                                </div>
                            </div>
                                    <input type="hidden" class="form-control" id="tipoconta" name="tipoconta" value="ssh">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <div class="row custom-options-checkable g-1">
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" name="tipoconta" id="customOptionsCheckableRadiosWithIcon2" value="ssh" checked />
                                                    <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                                                        <i data-feather="shield" class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">CONTA SSH</span>
                                                        <small>Nesse modo funcionara apenas ssh</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" name="tipoconta" id="customOptionsCheckableRadiosWithIcon3" value="v2ray" />
                                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
                                                        <i data-feather="link" class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">CONTA SSH E V2RAY</span>
                                                        <small>Nesse modo funcionara ssh e v2ray</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <input type="hidden" class="form-control" id="diretorio" name="diretorio" value="../../admin/home.php?page=ssh/add_teste">
                            <input type="hidden" class="form-control" id="owner" name="owner" value="<?php echo $accessKEY; ?>">
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
