<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/apexcharts.css">
<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/charts/chart-apex.css">
<script src="../../../app-assets/vendors/js/charts/apexcharts.min.js"></script>
<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

// Usados

$qtddoserverusado = 0;

$SQLusuariosdele = "SELECT * FROM usuario where id_mestre = '" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
$SQLusuariosdele = $conn->prepare($SQLusuariosdele);
$SQLusuariosdele->execute();

if ($SQLusuariosdele->rowCount() > 0) {
    while ($usuariosdele = $SQLusuariosdele->fetch()) {
        $SQLcontaqtdsshusadodele = "SELECT sum(acesso) as acessosdosserversusados2 FROM usuario_ssh where id_usuario = '" . $usuariosdele['id_usuario'] . "'";
        $SQLcontaqtdsshusadodele = $conn->prepare($SQLcontaqtdsshusadodele);
        $SQLcontaqtdsshusadodele->execute();

        $qtdusadosdele = $SQLcontaqtdsshusadodele->fetch();

        $qtddoserverusado += $qtdusadosdele['acessosdosserversusados2'];
    }
}


$SQLcontaqtdsshusado = "SELECT sum(acesso) as acessosdosserversusados FROM usuario_ssh where id_usuario = '" . $_SESSION['usuarioID'] . "'";
$SQLcontaqtdsshusado = $conn->prepare($SQLcontaqtdsshusado);
$SQLcontaqtdsshusado->execute();

$qtdusados = $SQLcontaqtdsshusado->fetch();

$qtddoserverusado += $qtdusados['acessosdosserversusados'];


// Todos Acessos

$todosacessos = 0;

$SQLqtdserveracessos = "SELECT sum(qtd) as todosacessos FROM  acesso_servidor where id_usuario = '" . $_SESSION['usuarioID'] . "'";
$SQLqtdserveracessos = $conn->prepare($SQLqtdserveracessos);
$SQLqtdserveracessos->execute();

$totalacess = $SQLqtdserveracessos->fetch();

$todosacessos += $totalacess['todosacessos'];

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

if ($valor_porce <= 0) {
    $valor_porce = 0;
}

if (($valor_porce >= 70) and ($valor_porce < 90)) {
    $sucessobar = "warning";
    $bgbar = "orange";
} elseif ($valor_porce >= 90) {
    $sucessobar = "danger";
    $bgbar = "red";
} else {
    $sucessobar = "success";
    $bgbar = "green";
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
                <h4 class="card-title text-center">Estatisticas de contas ssh</h4>
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
                        <p class="card-text mb-50">Disponível</p>
                        <span class="font-large-1 fw-bold"><?php echo $disponiveis; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom ">
                    <h4 class="card-title">Servidores disponíveis listados abaixo</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>NOME</th>
                                <th>ENDERECO IP</th>
                                <th>APLICATIVO</th>
                                <th>LIMITE TOTAL</th>
                                <th>CONTAS SSH</th>
                                <?php if ($usuario['subrevenda'] <> 'sim') {?>
                                <th>LIMITE DOS SUB</th>
                                <?php } ?>
                                <th>UTILIZADO (%)</th>
                                <th>VALIDADE</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">

                            <?php

                            $SQLAcessoServidor = "SELECT * FROM acesso_servidor where id_usuario = '" . $_SESSION['usuarioID'] . "' ";
                            $SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
                            $SQLAcessoServidor->execute();

                            // output data of each row
                            if (($SQLAcessoServidor->rowCount()) > 0) {

                                while ($row = $SQLAcessoServidor->fetch()) {

                                    $contas = 0;
                                    $total_acesso_ssh = 0;

                                    $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='" . $_SESSION['usuarioID'] . "' and id_servidor='" . $row['id_servidor'] . "'";
                                    $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                    $SQLAcessoSSH->execute();
                                    $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                    $total_acesso_ssh += $SQLAcessoSSH['quantidade'];

                                    $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '" . $_SESSION['usuarioID'] . "' and id_servidor='" . $row['id_servidor'] . "'";
                                    $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                    $SQLContasSSH->execute();
                                    $contas += $SQLContasSSH->rowCount();

                                    $SQLUserSub = "select * from usuario WHERE id_mestre = '" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
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
                                        }
                                    }




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


                                    $SQLSUB = "select * from acesso_servidor where id_mestre='" . $row['id_usuario'] . "'  ";
                                    $SQLSUB = $conn->prepare($SQLSUB);
                                    $SQLSUB->execute();
                                    
                                    $mytotal = 0;
                                    if (($SQLSUB->rowCount()) > 0) {
                                        $total_subrev = 0;
                                        while ($row_user = $SQLSUB->fetch()) {
                                        
                                            $SQLUSSH = "select sum(qtd) as soma from acesso_servidor where id_usuario = '" . $row_user['id_usuario'] . "' ";
                                            $SQLUSSH = $conn->prepare($SQLUSSH);
                                            $SQLUSSH->execute();
                                            $total_sub = $SQLUSSH->fetch();
                                            $mytotal = $total_subrev += $total_sub['soma'];

                                        }
                                        $todosacessos2 = $row['qtd'] += $mytotal;
                                    }

                            ?>

                                    <tr>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $servidor['nome']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $servidor['ip_servidor']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $texto; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $row['qtd']; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $contas; ?>
                                                </span>
                                            </span>
                                        </td>

                                        <?php if ($usuario['subrevenda'] <> 'sim') {?>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $mytotal; ?>
                                                </span>
                                            </span>
                                        </td>
                                        <?php } ?>

                                        <td><span class="badge bg-<?php echo $bgbar2; ?>"><?php echo $valor_porcetage; ?>%</span></td>
                                        <td>
                                            <span class="pull-left-container" style="margin-right: 5px;">
                                                <span class="badge badge-light-info">
                                                    <?php echo $dias_acesso . "  dias   "; ?>
                                                </span>
                                            </span>
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