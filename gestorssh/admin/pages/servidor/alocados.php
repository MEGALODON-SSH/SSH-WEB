<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/apexcharts.css">
<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/charts/chart-apex.css">
<script src="../../../app-assets/vendors/js/charts/apexcharts.min.js"></script>

<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
//$date = date("Y-m-d H:i:s");
//echo $date;
// Usados

$qtddoserverusado = 0;

$SQLusuariosdele = "SELECT * FROM acesso_servidor";
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

$SQLqtdserveracessos2 = "SELECT sum(qtd) as tudo FROM  acesso_servidor";
$SQLqtdserveracessos2 = $conn->prepare($SQLqtdserveracessos2);
$SQLqtdserveracessos2->execute();

$totalacessf = $SQLqtdserveracessos2->fetch();

$todosacessos += $totalacessf['tudo'];

//Disponiveis
$disponiveis = $todosacessos - $qtddoserverusado;

//Calculo Porcentagem

$porcent = ($qtddoserverusado / $todosacessos) * 100; // %

$resultado = $porcent;

$valor_porce = round($resultado);


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

<style>
    .card-datatable {
        padding-left: 5px;
        padding-right: 5px;
    }
</style>
<div class="row match-height">
    <!-- Support Tracker Chart Card starts -->
    <div class="col-lg-12 col-12">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between pb-0">
                <h4 class="card-title text-center">Contas ssh liberadas para revenda</h4>
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
    <!-- Support Tracker Chart Card ends -->
    <div class="col-12 mb-1">
        <div class="alert alert-info" role="alert">
            <div class="alert-body d-flex align-items-center">
                <i data-feather="star" class="me-50 text-center"></i>
                <span> Logo Abaixo vc pode editar dias de acesso e limite do seus clientes!</span>
            </div>
<script type="text/javascript" src="../../app-assets/plugins/sort-table.js"></script>
<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom ">
                    <h4 class="card-title">Revendedores e Sub revendedores</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SERVIDOR</th>
                                <th>ENDERECO IP</th>
                                <th>TIPO</th>
                                <th>CLIENTE</th>
                                <th>USO(%)</th>
                                <th>SSH CRIADAS</th>
                                <th>RESTANTE</th>
                                <th>LIMITE TOTAL</th>
                                <th>VALIDADE</th>
                                <th>OPÇÕES</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php

                            $SQLServidorac = "select * from acesso_servidor";
                            $SQLServidorac = $conn->prepare($SQLServidorac);
                            $SQLServidorac->execute();

                            // output data of each row
                            if (($SQLServidorac->rowCount()) > 0) {

                                while ($serveacesso = $SQLServidorac->fetch()) {
                                    $Servidor = "select * from servidor where id_servidor='" . $serveacesso['id_servidor'] . "'";
                                    $Servidor = $conn->prepare($Servidor);
                                    $Servidor->execute();
                                    $row = $Servidor->fetch();

                                    $SQLcliennte = "select * from usuario WHERE id_usuario='" . $serveacesso['id_usuario'] . "' ";
                                    $SQLcliennte = $conn->prepare($SQLcliennte);
                                    $SQLcliennte->execute();
                                    $cliente = $SQLcliennte->fetch();

                                    $contas = 0;
                                    $total_acesso_ssh = 0;
                                    $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $serveacesso['id_usuario'] . "' and id_servidor='" . $serveacesso['id_servidor'] . "'";
                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                    $SQLContasSSH->execute();
                                    $contas += $SQLContasSSH->rowCount();

                                    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $serveacesso['id_usuario'] . "' and id_servidor='" . $serveacesso['id_servidor'] . "'";
                                    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                    $SQLAcessoSSH->execute();
                                    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

                                    $SQLUserSub = "select * from usuario WHERE id_mestre = '" . $serveacesso['id_usuario'] . "' and subrevenda='nao'";
                                    $SQLUserSub = $conn->prepare($SQLUserSub);
                                    $SQLUserSub->execute();

                                    if (($SQLUserSub->rowCount()) > 0) {
                                        while ($rowS = $SQLUserSub->fetch()) {


                                            $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $rowS['id_usuario'] . "'  and id_servidor='" . $serveacesso['id_servidor'] . "'";
                                            $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                            $SQLContasSSH->execute();
                                            $contas += $SQLContasSSH->rowCount();

                                            $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $rowS['id_usuario'] . "'  and id_servidor='" . $serveacesso['id_servidor'] . "'";
                                            $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                            $SQLAcessoSSH->execute();
                                            $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                            $total_acesso_ssh += $SQLAcessoSSH['quantidade'];
                                        }
                                    }

                                    $todosacessos2 = 0;
                                    $SQLqtdserveracessos2 = "SELECT sum(qtd) as todosacessos FROM  acesso_servidor where id_usuario = '" . $serveacesso['id_usuario'] . "' and id_servidor='" . $serveacesso['id_servidor'] . "' ";
                                    $SQLqtdserveracessos2 = $conn->prepare($SQLqtdserveracessos2);
                                    $SQLqtdserveracessos2->execute();
                                    $totalacess2 = $SQLqtdserveracessos2->fetch();
                                    $todosacessos2 += $totalacess2['todosacessos'];


                                    $SQLSUB = "select * from acesso_servidor where id_mestre='" . $serveacesso['id_usuario'] . "'  ";
                                    $SQLSUB = $conn->prepare($SQLSUB);
                                    $SQLSUB->execute();

                                    if (($SQLSUB->rowCount()) > 0) {
                                        $total_subrev = 0;
                                        while ($row_user = $SQLSUB->fetch()) {

                                            $SQLUSSH = "select sum(qtd) as soma from acesso_servidor where id_usuario = '" . $row_user['id_usuario'] . "' ";
                                            $SQLUSSH = $conn->prepare($SQLUSSH);
                                            $SQLUSSH->execute();
                                            $total_sub = $SQLUSSH->fetch();
                                            $mytotal = $total_subrev += $total_sub['soma'];
                                        }
                                        $todosacessos2 = $todosacessos2 += $mytotal;
                                    }

                                    //Calculo Porcentagem

                                    $porcentagem = ($total_acesso_ssh / $todosacessos2) * 100; // %

                                    $resultado2 = $porcentagem;

                                    $valor_porcetage = round($resultado2);

                                    if ($valor_porcetage >= 100) {
                                        $valor_porcetage = 100;
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

                                    //Calcula os dias restante
                                    $data_atual = date("Y-m-d");
                                    $data_validade = $serveacesso['validade'];
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

                                    $somapode = $serveacesso['qtd'] - $total_acesso_ssh;

                            ?>
                                    <?php if ($cliente['subrevenda'] == 'nao') { ?>

                                        <div class="modal fade text-start" id="squarespaceModal<?php echo $serveacesso['id_acesso_servidor'];?>" aria-hidden="true" aria-labelledby="modalToggleLabel2" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="lineModalLabel"><i data-feather="edit" class="font-medium-5"></i> Alterar Servidor de Acesso</h3>
                                                    </div>
                                                    <form name="editaserver" action="pages/usuario/edita_revenda.php" method="post">
                                                        <div class="modal-body">
                                                            <input class="form-control" name="idservidoracesso" type="hidden" value="<?php echo $serveacesso['id_acesso_servidor']; ?>">
                                                            <div class="col-md-12 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="basicSelect">Servidor</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i data-feather="server"></i></span>
                                                                        <select size="1" class="form-select" name="fazer" disabled>
                                                                            <option class="form-control" value="<?php echo $row['id_servidor']; ?>" selected=selected> <?php echo $row['nome']; ?> - Pode Remover: <?php echo $somapode; ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="first-name-icon">Revendedor</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i data-feather="users"></i></span>
                                                                        <input type="text" class="form-control text-left" id="exampleInputEmail1" value="    <?php echo $cliente['nome']; ?>" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="basicSelect">Oque deseja fazer?</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i data-feather='edit-3'></i></span>
                                                                        <select size="1" name="addremove" class="form-select">
                                                                            <option value="1" selected=selected> adicionar Dias e Limite</option>
                                                                            <option value="2">Apenas Remover Limite</option>
                                                                            <option value="3">Apenas Remover Dias</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="first-name-icon">Dias</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                                                        <input type="number" class="form-control" name="dias" value="0" require="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="first-name-icon">Limite</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                                                        <input type="number" class="form-control" name="limite" value="0" require="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-12 text-center">
                                                                <button class="btn btn-success">Confirmar</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                </div>
            </div>
        <?php } ?>

        <tr>
            <?php if ($cliente['subrevenda'] == 'nao') {
                                        $color = 'info';
                                        $btcolor = 'primary';
                                    } else {
                                        $btcolor = 'warning';
                                        $color = 'warning';
                                    } ?>
            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $row['nome']; ?>
                    </span>
                </span>
            </td>

            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $row['ip_servidor']; ?>
                    </span>
                </span>
            </td>

            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <?php if ($cliente['subrevenda'] == 'nao') {
                                        echo '<span class="badge badge-light-primary">Revenda</span>';
                                    } else {
                                        echo '<span class="badge badge-light-warning">Sub Revenda</span>';
                                    } ?>
                </span>
            </td>

            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $cliente['nome']; ?>
                    </span>
                </span>
            </td>

            <td><span class="badge badge-light-<?php echo $bgbar2; ?>"><?php echo $valor_porcetage; ?>%</span></td>

            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $total_acesso_ssh; ?>
                    </span>
                </span>
            </td>
            <!--<td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $contas; ?>
                    </span>
                </span>
            </td>-->


            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $somapode; ?>
                    </span>
                </span>
            </td>

            <td>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-info">
                        <?php echo $todosacessos2; ?>
                    </span>
                </span>
            </td>

            <td>
                <?php if ($dias_acesso > 10) {
                                        $c_dias = 'info';
                                    } elseif ($dias_acesso == 0) {
                                        $c_dias = 'danger';
                                    } else {
                                        $c_dias = 'warning';
                                    }
                ?>
                <span class="pull-left-container" style="margin-right: 5px;">
                    <span class="badge badge-light-<?php echo $c_dias; ?>">
                        <?php echo $dias_acesso . "  dias   "; ?>
                    </span>
                </span>
            </td>

            <td>
                <div class="modal-size-xs d-inline-block">
                    <!-- Modal -->
                    <div class="modal fade" id="xSmall<?php echo $serveacesso['id_acesso_servidor']; ?>" tabindex="-1" style="display: none" aria-hidden="true" aria-labelledby="modalToggleLabel1">
                        <div class="modal-dialog modal-dialog-centered modal-xs">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel20">Escolha uma opção!</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        <li class="list-group mb-1">
                                            <a href="home.php?page=usuario/perfil&id_usuario=<?php echo $serveacesso['id_usuario']; ?>" class="btn btn-sm btn-primary"><i data-feather="eye"></i> Ver Perfil</a>
                                        </li>
                                        <li class="list-group mb-1">
                                            <button type="button" data-bs-target="#squarespaceModal<?php echo $serveacesso['id_acesso_servidor'];?>" data-bs-toggle="modal" data-bs-dismiss="modal" class="btn btn-sm btn-warning"><i data-feather="edit"></i> Editar limite ou data</button>
                                        </li>
                                        <li class="list-group mb-1">
                                        <?php if ($cliente['ativo'] != 1) { ?>
                                            <a type="button" href="../pages/system/funcoes.usuario.php?&id_usuario=<?php echo $cliente['id_usuario']; ?>&diretorio=../../admin/home.php?page=servidor/alocados&owner=<?php echo $accessKEY; ?>&op=ususpender&renovar=<?php echo $serveacesso['id_acesso_servidor'];?>" class="btn btn-sm btn-success"><i data-feather='refresh-ccw'></i> Renovar Revenda (30 dias)</a>
                                        <?php } else { ?>
                                            <a type="button" href="../pages/system/funcoes.usuario.php?&id_usuario=<?php echo $cliente['id_usuario']; ?>&diretorio=../../admin/home.php?page=usuario/listar&owner=<?php echo $accessKEY; ?>&op=suspender" class="btn btn-sm btn-danger"><i data-feather='user-x'></i> Suspender Revenda</a>
                                        <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($cliente['subrevenda'] == 'nao') { ?>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#xSmall<?php echo $serveacesso['id_acesso_servidor']; ?>"><i data-feather='grid'></i></button>
                <?php } else { ?>
                    <a href="home.php?page=usuario/perfil&id_usuario=<?php echo $serveacesso['id_usuario']; ?>" class="btn btn-sm btn-warning"><i data-feather="eye"></i></a>
                <?php } ?>
            </td>
        </tr>

<?php }
                            }
?>
</tbody>
</table>
        </div>
    </div>
</section>

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
    
    $("button[data-bs-dismiss=modal]").click(function(){
        $(".modal").modal('hide');
    });

</script>
