<?php
if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

if (isset($_GET["id_servidor"])) {
    $SQLServidor = "select * from servidor WHERE id_servidor = '" . $_GET['id_servidor'] . "' ";
    $SQLServidor = $conn->prepare($SQLServidor);
    $SQLServidor->execute();
    $servidor = $SQLServidor->fetch();
    if (($SQLServidor->rowCount()) == 0) {
        echo '<script type="text/javascript">';
        echo     'alert("Nao encontrado!");';
        echo    'window.location="home.php?page=servidor/listar";';
        echo '</script>';
        exit;
    }
} else {
    echo '<script type="text/javascript">';
    echo     'alert("Preencha todos os campos!");';
    echo    'window.location="home.php?page=servidor/listar";';
    echo '</script>';
    exit;
}

//Realiza a comunicacao com o servidor
$ip_servidor = $servidor['ip_servidor'];
$loginSSH = $servidor['login_server'];
$senhaSSH =  $servidor['senha'];
$ssh = new SSH2($ip_servidor);

//Verifica se o servidor esta online
$servidor_online = $ssh->online($ip_servidor);
if ($servidor_online) {
    $servidor_autenticado = $ssh->auth($loginSSH, $senhaSSH);
    if ($servidor_autenticado) {

        $status = "<div class='alert alert-success col-12 col-md-12' role='alert'>
                        <div class='alert-body d-center align-items-center text-center'>
                            <span>Autenticado</span>
                        </div>
                    </div>";
        //Verifica memoria
        $ssh->exec("free -m");
        $mensagem = (string) $ssh->output();
        $words = preg_split(
            "/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/",
            $mensagem,
            0,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        //Memoria total $words[7]
        //Memoria usada $words[8]
        //Memoria livre $words[9]

        //Quantidade de CPU fisico
        $ssh->exec("echo $(grep -c cpu[0-9] /proc/stat)");
        $mensagem_f = (string) $ssh->output();
        $cpu_fisico = $mensagem_f;

        //Utilizacao de CPU
        $ssh->exec("echo \"$[100-$(vmstat 1 2|tail -1|awk '{print $15}')]\"%");
        $mensagem_v2 = (string) $ssh->output();
        $utilproc = $mensagem_v2;

        //Nome do Processador
        $ssh->exec("cat /proc/cpuinfo | egrep ' model name|model name'");
        $mensagem_p = (string) $ssh->output();
        $partes = explode(":", $mensagem_p);
        $nome_processador = $partes[1];

        //Utilizacao Ram
        $ssh->exec("printf '%-8s' \"$(free -m | awk 'NR==2{printf \"%.2f%%\", $3*100/$2 }')\"");
        $mensagem_uram = (string) $ssh->output();
        $uram = $mensagem_uram;

        //UPTIME
        $ssh->exec("uptime");
        $mensagem_u = (string) $ssh->output();
        $uptime = $mensagem_u;
        
        if ($servidor['tipo'] != 'free') {
			$usuarios_online = 0;
            //Usuarios SSH online neste servidor
            $SQLContasSSH = "SELECT sum(online) AS soma  FROM usuario_ssh where id_servidor = '" . $_GET['id_servidor'] . "'   ";
            $SQLContasSSH = $conn->prepare($SQLContasSSH);
            $SQLContasSSH->execute();
            $SQLContasSSH = $SQLContasSSH->fetch();
            $usuarios_online += $SQLContasSSH['soma'];
        }
    } else {
        $status = "<div class='alert alert-warning col-12 col-md-12' role='alert'>
                        <div class='alert-body d-center align-items-center text-center'>
                            <span>Não Autenticado</span>
                        </div>
                    </div>";
    }
} else {
    $status = "<div class='alert alert-danger col-12 col-md-12' role='alert'>
                    <div class='alert-body d-center align-items-center text-center'>
                        <span>OFFLINE</span>
                    </div>
                </div>";
}

?>


<div class="modal-size-xs d-inline-block">
    <!-- Modal -->
    <div class="modal fade text-start" id="xSmall" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel20">Funções do servidor</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
					<li class="list-group mb-1">
                            <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=sincronizar" class="btn btn-success"><i data-feather='repeat'></i> Sincronizar Contas SSH</a>
                        </li>
                        <li class="list-group mb-1">
                            <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=reiniciar" class="btn btn-warning"><i data-feather='refresh-cw'></i> Reiniciar Servidor</a>
                        </li>
                        <li class="list-group mb-1">
                            <?php if ($servidor['manutencao'] == 'nao') { ?>
                                <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=manutencao" class="btn btn-warning"><i data-feather='tool'></i> Por em Manutenção</a>
                            <?php } else { ?>
                                <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=manutencao" class="btn btn-success"><i data-feather='tool'></i> Tirar Manutenção</a>
                            <?php } ?>
                        </li>
                        <li class="list-group mb-1">
                            <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=deletarContas" class="btn btn-danger"><i data-feather='trash'></i> Deletar Contas SSH</a>
                        </li>
                        <li class="list-group mb-1">
                            <a href="../admin/pages/servidor/servidor_exe.php?id_servidor=<?php echo $servidor['id_servidor']; ?>&op=deletarGeral" class="btn btn-danger"><i data-feather='trash-2'></i> Deletar Servidor</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Input with Icons start -->
<section id="collapsible1">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-transaction">
                        <div class="card-body little-profile text-center">
                            <p class="card-text"><?php echo $status; ?></p>
                            <?php if ($servidor['manutencao'] != 'nao') { ?>
                                <div class='alert alert-warning col-12 col-md-12' role='alert'>
                                    <div class='alert-body d-center align-items-center text-center'>
                                        <span>Em Manutençao !</span>
                                    </div>
                                </div>
                            <?php } ?>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <span class="badge badge-pill bg-primary float-right"></span>
                                    <b class="text-primary"><i data-feather='hard-drive' class="avatar-icon font-medium-3"></i> <?php echo $nome_processador; ?></b>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-info rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='cpu' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-info">Nucleos do Processador</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder"><?php echo $cpu_fisico; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-info rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='cpu' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-info">Utilizaçao Processador</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder"><?php echo $utilproc; ?></div>
                                    </div>

                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-warning rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='sliders' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-warning">Total Memoria Ram</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder"><?php echo $words[7]; ?> Mb</div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-warning rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='sliders' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-warning">Utilizacao Memoria Ram</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder"><?php echo $uram; ?></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <div class="avatar bg-light-success rounded float-start">
                                                <div class="avatar-content">
                                                    <i data-feather='zap' class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-success">Usuários Online</h6>
                                            </div>
                                        </div>
                                        <div class="fw-bolder"><?php echo $usuarios_online; ?></div>
                                    </div>
                                </li>
                            </ul>
                            <br>
                            <ul class="list-group">
                                <li class="list-group mb-1">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#xSmall">Funções do servidor</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body p-b-0">
                            <h4 class="card-title"><i class="fa fa-edit"></i> Editar Servidor</i></h4>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#activity" role="tab"><span class="hidden-sm-up"><i class="ti-pencil"></i></span> <span class="hidden-xs-down">Informações</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#timeline" role="tab"><span class="hidden-sm-up"><i class="ti-align-center"></i></span> <span class="hidden-xs-down">Contas SSH</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#ovpn" role="tab"><span class="hidden-sm-up"><i class="ti-export"></i></span> <span class="hidden-xs-down">Arquivo APK</span></a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <form role="form" action="pages/servidor/editar_exe.php" method="post" enctype="multipart/form-data">
                                        <div class="form-body">
                                            <input type="hidden" class="form-control" id="id_servidor" name="id_servidor" value="<?php echo $servidor['id_servidor']; ?>"><br>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Nome para o servidor</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='server'></i></span>
                                                    <input required="required" type="text" class="form-control" id="nomesrv" name="nomesrv" value="<?php echo $servidor['nome']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Endereço de IP</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='radio'></i></span>
                                                    <input required="required" type="text" class="form-control" id="ip" name="ip" value="<?php echo $servidor['ip_servidor']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Usuario com acesso root</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='user'></i></span>
                                                    <input required="required" type="text" class="form-control" id="login" name="login" value="<?php echo $servidor['login_server']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Senha root</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='key'></i></span>
                                                    <input required="required" type="password" class="form-control" id="login" name="senha" value="<?php echo $servidor['senha']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label" for="country-floating">Link do Painel</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='link-2'></i></span>
                                                    <input required="required" type="text" class="form-control" id="siteserver" name="siteserver" value="<?php echo $servidor['site_servidor']; ?>" placeholder="seu-link.com">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Localização</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='map-pin'></i></span>
                                                    <input required="required" type="text" class="form-control" id="localiza" name="localiza" value="<?php echo $servidor['localizacao']; ?>" placeholder="sao paulo">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Validade</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='calendar'></i></span>
                                                    <input required="required" type="text" minlength="1" maxlength="4" class="form-control" id="validade" name="validade" value="<?php echo $servidor['validade']; ?>" placeholder="Ex: 9999">
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label" for="country-floating">Limite</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather='smartphone'></i></span>
                                                    <input required="required" type="text" minlength="1" maxlength="4" class="form-control" id="limite" name="limite" value="<?php echo $servidor['limite']; ?>" placeholder="Ex: 9999">
                                                </div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-success">Alterar Servidor</button>
                                                <button type="reset" class="btn btn-danger">Limpar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-panel -->
                                <div class="tab-pane" id="timeline">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Login SSH</th>
                                                <th>Vencimento</th>
                                                <th>Online</th>
                                                <th>Acesso</th>
                                            </tr>
                                            <?php
                                            $SQLUsuarioSSH = "select * from usuario_ssh where id_servidor='" . $servidor['id_servidor'] . "'";
                                            $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                                            $SQLUsuarioSSH->execute();

                                            if (($SQLUsuarioSSH->rowCount()) > 0) {
                                                while ($row2 = $SQLUsuarioSSH->fetch()) {
                                                    if ($servidor['tipo'] <> 'free') {
                                                        $SQLTotalUser = "select * from usuario_ssh WHERE id_servidor='" . $servidor['id_servidor'] . "' ";
                                                        $SQLTotalUser = $conn->prepare($SQLTotalUser);
                                                        $SQLTotalUser->execute();
                                                        $total_user = $SQLTotalUser->rowCount();

                                                        $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row2['id_servidor'] . "'  and id_usuario='" . $_GET['id_usuario'] . "' ";
                                                        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                                        $SQLAcessoSSH->execute();
                                                        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                                        $acessos += $SQLAcessoSSH['quantidade'];
                                                    }
                                                    $data_atual = date("Y-m-d ");
                                                    $data_validade = $row2['data_validade'];
                                                    if ($data_validade > $data_atual) {
                                                        $data1 = new DateTime($data_validade);
                                                        $data2 = new DateTime($data_atual);
                                                        $dias_acesso = 0;
                                                        $diferenca = $data1->diff($data2);
                                                        $ano = $diferenca->y * 364;
                                                        $mes = $diferenca->m * 30;
                                                        $dia = $diferenca->d;
                                                        $dias_acesso = $ano + $mes + $dia;
                                                    } else {
                                                        $dias_acesso = 0;
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo $row2['login']; ?></td>
                                                        <td><span class="pull-left-container" style="margin-right: 5px;">
                                                                <span class="label label-primary pull-left">
                                                                    <?php echo $dias_acesso . "  dias   "; ?>
                                                                </span>
                                                            </span>
                                                        </td>
                                                        <td><?php if ($servidor['tipo'] == 'premium') {
                                                                echo $row2['online'];
                                                            } else {
                                                                echo "<small>null</small>";
                                                            } ?></td>
                                                        <td><?php if ($servidor['tipo'] == 'premium') {
                                                                echo $row2['acesso'];
                                                            } else {
                                                                echo "<small>null</small>";
                                                            } ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                $SQLovpn = "select * from ovpn WHERE servidor_id = '" . $_GET["id_servidor"] . "' ";
                                $SQLovpn = $conn->prepare($SQLovpn);
                                $SQLovpn->execute();
                                ?>
                                <div class="tab-pane" id="ovpn">
                                    <div class="box-body">
                                        <?php if ($SQLovpn->rowCount() == 0) { ?>
                                            <form role="form" action="pages/servidor/enviar_ovpn.php" method="post" enctype="multipart/form-data">
                                                <input name="servidorid" type="hidden" value="<?php echo $servidor['id_servidor']; ?>">
                                                <div class="form-group">
                                                    <br><label for="exampleInputFile">Selecione o Arquivo APK</label>
                                                    <input type="file" id="arquivo" class="form-control" name="arquivo" required=required>
                                                    <p class="help-block"><small>Arquivo APK Tamanho Máximo 256MB.</small></p>
                                                <?php } else { ?>
                                                    <div class="box box-solid box-success">
                                                        <div class="text-center">Já existe um <b>Arquivo APK</b> neste Servidor!</div><br>
                                                        <div class="col-12 text-center">
                                                            <a href="../admin/pages/servidor/deletar_ovpn.php?id_servidor=<?php echo $servidor['id_servidor']; ?>" class="btn btn-danger">Deletar</a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="box-footer box-primary">
                                                    <div class="col-12 text-center">
                                                        <?php if ($SQLovpn->rowCount() == 0) { ?> <button type="submit" class="btn btn-success">Enviar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>