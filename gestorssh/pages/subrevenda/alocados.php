<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/apexcharts.css">
<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/charts/chart-apex.css">
<script src="../../../app-assets/vendors/js/charts/apexcharts.min.js"></script>

<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

$owner = $_SESSION['usuarioID'];
// Usados

$qtddoserverusado = 0;

$SQLusuariosdele = "SELECT * FROM acesso_servidor where id_mestre = '" . $_SESSION['usuarioID'] . "'";
$SQLusuariosdele = $conn->prepare($SQLusuariosdele);
$SQLusuariosdele->execute();

if ($SQLusuariosdele->rowCount() > 0) {
    while ($usuariosdele = $SQLusuariosdele->fetch()) {


        $SQLcontaqtdsshusadodele = "SELECT sum(acesso) as acessosdosserversusados2 FROM usuario_ssh where id_usuario = '" . $usuariosdele['id_usuario'] . "'";
        $SQLcontaqtdsshusadodele = $conn->prepare($SQLcontaqtdsshusadodele);
        $SQLcontaqtdsshusadodele->execute();

        $qtdusadosdele = $SQLcontaqtdsshusadodele->fetch();

        $qtddoserverusado += $qtdusadosdele['acessosdosserversusados2'];

        //Select sub deles

        $SQLusuariossubdele = "SELECT * FROM usuario where id_mestre = '" . $usuariosdele['id_usuario'] . "'";
        $SQLusuariossubdele = $conn->prepare($SQLusuariossubdele);
        $SQLusuariossubdele->execute();

        if ($SQLusuariossubdele->rowCount() > 0) {
            while ($usuariossubdele = $SQLusuariossubdele->fetch()) {
                $SQLcontaqtdsshusado = "SELECT sum(acesso) as acessosdosserversusados FROM usuario_ssh where id_usuario = '" . $usuariossubdele['id_usuario'] . "'";
                $SQLcontaqtdsshusado = $conn->prepare($SQLcontaqtdsshusado);
                $SQLcontaqtdsshusado->execute();

                $qtdusados = $SQLcontaqtdsshusado->fetch();

                $qtddoserverusado += $qtdusados['acessosdosserversusados'];
            }
        }
    }
}


// Todos Acessos

$todosacessos = 0;

$SQLqtdserveracessos2 = "SELECT sum(qtd) as tudo FROM  acesso_servidor where id_mestre = '" . $_SESSION['usuarioID'] . "'";
$SQLqtdserveracessos2 = $conn->prepare($SQLqtdserveracessos2);
$SQLqtdserveracessos2->execute();

$totalacessf = $SQLqtdserveracessos2->fetch();

$todosacessos += $totalacessf['tudo'];


//Disponiveis
$disponiveis = $todosacessos - $qtddoserverusado;

if ($disponiveis <= 0) {
    $disponiveis = 0;
}


//Calculo Porcentagem

$porcent = ($qtddoserverusado / $todosacessos) * 100; // %

$resultado = $porcent;

$valor_porce = round($resultado);

if ($valor_porce >= 100) {
    $valor_porce = 100;
}



if (($valor_porce >= 70) and ($valor_porce < 90)) {
    $sucessobar = "warning";
    $bgbar = "warning";
} elseif ($valor_porce >= 90) {
    $sucessobar = "danger";
    $bgbar = "danger";
} else {
    $sucessobar = "success";
    $bgbar = "success";
}


?>

<div class="row match-height">
    <!-- Support Tracker Chart Card starts -->
    <div class="col-lg-12 col-12">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between pb-0">
                <h4 class="card-title text-center">Estatisticas de logins deles</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex justify-content-center">
                        <div id="line-chart2" value="10"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <div class="text-center">
                        <p class="card-text mb-50">Em uso</p>
                        <span class="font-large-1 fw-bold"><?php echo $qtddoserverusado; ?></span>
                    </div>
                    <div class="text-center">
                        <p class="card-text mb-50">Total liberado</p>
                        <span class="font-large-1 fw-bold"><?php echo $todosacessos; ?></span>
                    </div>
                    <div class="text-center">
                        <p class="card-text mb-50">Disponivel</p>
                        <span class="font-large-1 fw-bold"><?php echo $disponiveis; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-datatable {
        padding-left: 5px;
        padding-right: 5px;
    }

    ;
</style>


<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom ">
                    <h4 class="card-title">Sub Revenda com acesso a servidor</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>NOME</th>
                                <th>CLIENTE</th>
                                <th>USO</th>
                                <th>ENDERECO IP</th>
                                <th>CONTAS SSH</th>
                                <th>ACESSOS SSH</th>
                                <th>LIMITE</th>
                                <th>VALIDADE</th>
                                <th>OPCOES</th>
                            </tr>
                        </thead>
                        <tbody id="MeuServidor">
                            <?php

                            $SQLAcessoServidor = "SELECT * FROM acesso_servidor where id_mestre = '" . $_SESSION['usuarioID'] . "' ";
                            $SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
                            $SQLAcessoServidor->execute();

                            // output data of each row
                            if (($SQLAcessoServidor->rowCount()) > 0) {

                                while ($row = $SQLAcessoServidor->fetch()) {

                                    $SQLusuario = "SELECT * FROM usuario where id_usuario = '" . $row['id_usuario'] . "' ";
                                    $SQLusuario = $conn->prepare($SQLusuario);
                                    $SQLusuario->execute();
                                    $usuario = $SQLusuario->fetch();

                                    $contas = 0;
                                    $total_acesso_ssh = 0;

                                    $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $row['id_usuario'] . "' and id_servidor='" . $row['id_servidor'] . "'";
                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                    $SQLContasSSH->execute();
                                    $contas += $SQLContasSSH->rowCount();

                                    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $row['id_usuario'] . "' and id_servidor='" . $row['id_servidor'] . "'";
                                    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                    $SQLAcessoSSH->execute();
                                    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

                                    $SQLUserSub = "select * from usuario WHERE id_mestre = '" . $row['id_usuario'] . "'";
                                    $SQLUserSub = $conn->prepare($SQLUserSub);
                                    $SQLUserSub->execute();

                                    if (($SQLUserSub->rowCount()) > 0) {

                                        while ($rowS = $SQLUserSub->fetch()) {
                                            $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $rowS['id_usuario'] . "'  and id_servidor='" . $row['id_servidor'] . "'";
                                            $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                            $SQLContasSSH->execute();
                                            $contas += $SQLContasSSH->rowCount();

                                            $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $rowS['id_usuario'] . "'  and id_servidor='" . $row['id_servidor'] . "'";
                                            $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                            $SQLAcessoSSH->execute();
                                            $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                            $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

                                            $SQLusuariossubdele2 = "SELECT * FROM usuario where id_mestre = '" . $rowS['id_usuario'] . "'";
                                            $SQLusuariossubdele2 = $conn->prepare($SQLusuariossubdele2);
                                            $SQLusuariossubdele2->execute();
                                            if (($SQLusuariossubdele2->rowCount()) > 0) {
                                                while ($rowSubdele = $SQLusuariossubdele2->fetch()) {
                                                    $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $rowSubdele['id_usuario'] . "'  and id_servidor='" . $row['id_servidor'] . "'";
                                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                                    $SQLContasSSH->execute();
                                                    $contas += $SQLContasSSH->rowCount();

                                                    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $rowSubdele['id_usuario'] . "'  and id_servidor='" . $row['id_servidor'] . "'";
                                                    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                                    $SQLAcessoSSH->execute();
                                                    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                                    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];
                                                }
                                            }
                                        }
                                    }



                                    $Servidor = "select * from servidor where id_servidor='" . $row['id_servidor'] . "'";
                                    $Servidor = $conn->prepare($Servidor);
                                    $Servidor->execute();
                                    $rowservidor = $Servidor->fetch();

                                    $SQLcliennte = "select * from usuario WHERE id_usuario='" . $row['id_usuario'] . "' ";
                                    $SQLcliennte = $conn->prepare($SQLcliennte);
                                    $SQLcliennte->execute();
                                    $cliente = $SQLcliennte->fetch();

                                    $todosacessos2 = 0;

                                    $SQLqtdserveracessos2 = "SELECT sum(qtd) as todosacessos FROM  acesso_servidor where id_usuario = '" . $row['id_usuario'] . "' and id_servidor='" . $row['id_servidor'] . "' ";
                                    $SQLqtdserveracessos2 = $conn->prepare($SQLqtdserveracessos2);
                                    $SQLqtdserveracessos2->execute();

                                    $totalacess2 = $SQLqtdserveracessos2->fetch();

                                    $todosacessos2 += $totalacess2['todosacessos'];

                                    //Calculo Porcentagem

                                    $porcentagem = ($total_acesso_ssh / $todosacessos2) * 100; // %

                                    $resultado2 = $porcentagem;

                                    $valor_porcetage = round($resultado2);

                                    if ($valor_porcetage >= 100) {
                                        $valor_porcetage = 100;
                                    }

                                    if ($valor_porcetage <= 0) {
                                        $valor_porcetage = 0;
                                    }
                                    if (($valor_porcetage >= 70) and ($valor_porcetage < 90)) {
                                        $sucessobar = "warning";
                                        $bgbar2 = "warning";
                                    } elseif ($valor_porcetage >= 90) {
                                        $sucessobar = "danger";
                                        $bgbar2 = "danger";
                                    } else {
                                        $sucessobar = "success";
                                        $bgbar2 = "success";
                                    }


                                    $SQLServidor = "select * from servidor WHERE id_servidor = '" . $row['id_servidor'] . "' ";
                                    $SQLServidor = $conn->prepare($SQLServidor);
                                    $SQLServidor->execute();
                                    $servidor =  $SQLServidor->fetch();


                                    $SQLopen = "select * from ovpn WHERE servidor_id = '" . $row['id_servidor'] . "' ";
                                    $SQLopen = $conn->prepare($SQLopen);
                                    $SQLopen->execute();
                                    if ($SQLopen->rowCount() > 0) {
                                        $openvpn = $SQLopen->fetch();
                                        $texto = "<a href='../admin/pages/servidor/baixar_ovpn.php?id=" . $openvpn['id'] . "' class=\"label label-info\">Baixar</a>";
                                    } else {
                                        $texto = "<span class=\"label label-danger\">Indisponivel</span>";
                                    }

                                    //Calcula os dias restante
                                    $data_atual = date("Y-m-d");
                                    $data_validade = $row['validade'];
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

                                    //Carrega contas SSH criadas
                                    $SQLContasminha = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row['id_servidor'] . "' and id_usuario='" . $_SESSION['usuarioID'] . "' ";
                                    $SQLContasminha = $conn->prepare($SQLContasminha);
                                    $SQLContasminha->execute();
                                    $SQLContasminha = $SQLContasminha->fetch();
                                    $contas_ssh_criadas_minhas = $SQLContasminha['quantidade'];

                                    //Carrega usuario sub
                                    $SQLUsuarioSub_minhas = "SELECT * FROM usuario WHERE id_mestre ='" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
                                    $SQLUsuarioSub_minhas = $conn->prepare($SQLUsuarioSub_minhas);
                                    $SQLUsuarioSub_minhas->execute();


                                    if (($SQLUsuarioSub_minhas->rowCount()) > 0) {
                                        while ($row2 = $SQLUsuarioSub_minhas->fetch()) {
                                            $SQLSubSSH_minhas = "select sum(acesso) AS quantidade  from usuario_ssh WHERE id_usuario = '" . $row2['id_usuario'] . "' and id_servidor='" . $serveacesso['id_servidor'] . "' ";
                                            $SQLSubSSH_minhas = $conn->prepare($SQLSubSSH_minhas);
                                            $SQLSubSSH_minhas->execute();
                                            $SQLSubSSH_minhas = $SQLSubSSH_minhas->fetch();
                                            $contas_ssh_criadas_minhas += $SQLSubSSH_minhas['quantidade'];
                                        }
                                    }


                                    $SQLservermy = "select * from acesso_servidor WHERE id_acesso_servidor='" . $row['id_servidor_mestre'] . "'";
                                    $SQLservermy = $conn->prepare($SQLservermy);
                                    $SQLservermy->execute();

                                    $meuserver = $SQLservermy->fetch();
                                    $somameusatuais = $meuserver['qtd'] - $contas_ssh_criadas_minhas;

                            ?>

                                    <div id="myModal<?php echo $row['id_acesso_servidor']; ?>" data-bs-backdrop="false" class="modal fade in">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form name="deletarserver" action="pages/subrevenda/deletarservidor_exe.php" method="post">
                                                    <input name="servidor" type="hidden" value="<?php echo $row['id_acesso_servidor']; ?>">
                                                    <input name="cliente" type="hidden" value="<?php echo $cliente['id_usuario']; ?>">
                                                    <div class="modal-header">
                                                        <a class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></a>
                                                        <h4 class="modal-title">Apagar Tudo de <?php echo $cliente['nome']; ?></h4>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h4>Tem certeza?</h4>
                                                        <p>Todos os clientes deles irão ter a conta SSH Deletada.</p>
                                                        <p>Você recebe os Acessos de Volta.</p>
                                                    </div>
                                                    <div class="modal-footer mb-2">
                                                        <div class="col-12 text-center">
                                                            <button name="deletandoserver" class="btn btn-success">Confirmar</button>
                                                            <button onclick="reload();" type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dalog -->
                                    </div><!-- /.modal -->

                                    <div class="modal fade text-start" data-bs-backdrop="false" id="squarespaceModal<?php echo $row['id_acesso_servidor']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="lineModalLabel"><i class="fa fa-pencil-square-o"></i> Editar Servidor de Acesso</h3>
                                                    <button type="button" onclick="reload();" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- content goes here -->
                                                    <form name="editaserver" action="pages/subrevenda/editar_acesso.php" method="post">
                                                        <input name="idservidoracesso" type="hidden" value="<?php echo $row['id_acesso_servidor']; ?>">

                                                        <div class="mb-1">
                                                            <div class="form-group">
                                                                <label class="form-label" for="first-name-icon">Servidor</label>
                                                                <select size="1" class="form-control select2" name="fazer" disabled>
                                                                    <option value="<?php echo $rowservidor['id_servidor']; ?>" selected=selected> <?php echo $rowservidor['nome']; ?> - Limite Atual: <?php echo $row['qtd']; ?> Resta: <?php echo $somameusatuais; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="mb-1">
                                                            <div class="form-group">
                                                                <label class="form-label" for="first-name-icon">Sub Revendedor</label>
                                                                <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $cliente['nome']; ?>" disabled>
                                                            </div>
                                                        </div>



                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="demo-spacing-0 mb-2">
                                                                <div class="alert alert-warning" role="alert">
                                                                    <div class="alert-body d-flex align-items-center">
                                                                        <i data-feather="info" class="me-50"></i>
                                                                        <span>Para altera dias ou limite use + ou - Ex: -1</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-center mb-2">
                                                                <label class="form-control-label" for="control-label">Dias </label>
                                                                <div class="input-group">
                                                                    <input type="number" class="touchspin-min-max position-left-50" name="dias" value="0" />
                                                                </div>
                                                                <label class="form-control-label" for="control-label">Limite</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="touchspin-min-max" name="limite" value="0" />
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <input type="hidden" class="form-control" id="qtd" name="qtd" value="<?php echo $row['qtd']; ?>">
                                                        <input type="hidden" class="form-control" id="cnts" name="cnts" value="<?php echo $contas; ?>">

                                                        <div class="modal-footer">
                                                            <div class="col-12 text-center">
                                                                <button class="btn btn-success">Confirmar</button>
                                                                <button type="button" onclick="reload();" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <tr>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $servidor['nome']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $usuario['nome']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-<?php echo $bgbar2; ?>"><?php echo $valor_porcetage; ?>%</span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $servidor['ip_servidor']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $contas; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $total_acesso_ssh; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 3px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $row['qtd']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($dias_acesso < 10) { ?>
                                                <span class="pull-left-container" style="margin-right: 3px;">
                                                    <span class="badge badge-light-warning">
                                                        <?php echo $dias_acesso . "  dias   "; ?>
                                                    </span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="pull-left-container" style="margin-right: 3px;">
                                                    <span class="badge badge-light-info">
                                                        <?php echo $dias_acesso . "  dias   "; ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td>

                                            <div class="modal-size-xs d-inline-block">
                                                <!-- Modal -->
                                                <div class="modal fade text-start" id="xSmall<?php echo $row['id_acesso_servidor']; ?>" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel20">Escolha uma opcao !</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="list-group">
                                                                    <li class="list-group mb-1">
                                                                        <a href="home.php?page=usuario/perfil&id_usuario=<?php echo  $cliente['id_usuario']; ?>" class="btn btn-sm btn-primary"><i data-feather="eye"></i> Ver Perfil</a>
                                                                    </li>
                                                                    <li class="list-group mb-1">
                                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#squarespaceModal<?php echo $row['id_acesso_servidor']; ?>" data-bs-dismiss="modal" class="btn btn-sm btn-warning"><i data-feather="edit"></i> Editar limite ou data</button>
                                                                    </li>
                                                                    <li class="list-group mb-1">
                                                                        <?php if ($cliente['ativo'] != 1) { ?>
                                                                            <a type="button" href="pages/system/funcoes.usuario.php?&id_usuario=<?php echo $cliente['id_usuario']; ?>&diretorio=../../home.php?page=subrevenda/alocados&owner=<?php echo $owner; ?>&op=ususpender&renovar=<?php echo $row['id_acesso_servidor']; ?>" class="btn btn-sm btn-success"><i data-feather='refresh-ccw'></i> Renovar Revenda (30 dias)</a>
                                                                        <?php } else { ?>
                                                                            <a type="button" href="pages/system/funcoes.usuario.php?&id_usuario=<?php echo $cliente['id_usuario']; ?>&diretorio=../../home.php?page=subrevenda/revendedores&owner=<?php echo $owner; ?>&op=suspender" class="btn btn-sm btn-danger"><i data-feather='user-x'></i> Suspender Revenda</a>
                                                                        <?php } ?>
                                                                    </li>
                                                                    <li class="list-group mb-1">
                                                                        <a type="button" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#myModal<?php echo $row['id_acesso_servidor']; ?>" class="btn btn-sm btn-danger"><i data-feather='trash'></i> Excluir Acesso</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#xSmall<?php echo $row['id_acesso_servidor']; ?>"><i data-feather='grid'></i></button>
                                        </td>
                                    </tr>

                            <?php }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>


<style>
    .modal .modal-header {
        border-bottom: none;
        position: relative;
    }

    .modal .modal-header .btn {
        position: absolute;
        top: 0;
        right: 0;
        margin-top: 0;
        border-top-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .modal .modal-footer {
        border-top: none;
        padding: 0;
    }

    .modal .modal-footer .btn-group>.btn:first-child {
        border-bottom-left-radius: 0;
    }

    .modal .modal-footer .btn-group>.btn:last-child {
        border-top-right-radius: 0;
    }
</style>

<script>
    var $avgSessionStrokeColor2 = '#ebf0f7';
    var $textHeadingColor = '#5e5873';
    var $white = '#fff';
    var $strokeColor = '#ebe9f1';
    var chartOptions = {
        chart: {
            height: 220,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                size: 150,
                offsetY: 20,
                startAngle: -150,
                endAngle: 150,
                hollow: {
                    size: '65%'
                },
                track: {
                    background: $white,
                    strokeWidth: '100%'
                },
                dataLabels: {
                    name: {
                        offsetY: -5,
                        color: $textHeadingColor,
                        fontSize: '1rem'
                    },
                    value: {
                        offsetY: 15,
                        color: $textHeadingColor,
                        fontSize: '1.714rem'
                    }
                }
            }
        },
        colors: ['#ea5455'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#28c76f'],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            dashArray: 5
        },
        series: [<?php echo $valor_porce; ?>],
        labels: ['Utilizado'],
    };
    var chart = new ApexCharts(document.querySelector("#line-chart2"), chartOptions);
    chart.render();
</script>

<script src="../../../app-assets/vendors/js/vendors.min.js"></script>
<script src="../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
<script src="../../../app-assets/js/scripts/forms/form-number-input.js"></script>

<script type="text/javascript">
    $("button[data-bs-dismiss=modal]").click(function(){
        $(".modal").modal('hide');
    });
    function reload() {
        document.location.reload(true);
    }
</script>