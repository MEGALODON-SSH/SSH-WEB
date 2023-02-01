<?php
$procnoticias = "select * FROM noticias where status='ativo'";
$procnoticias = $conn->prepare($procnoticias);
$procnoticias->execute();

if ($usuario['tipo'] == 'revenda') {
  // Clientes
  $SQLbuscaclientes = "select * from usuario where tipo='vpn' and id_mestre='" . $usuario['id_usuario'] . "'";
  $SQLbuscaclientes = $conn->prepare($SQLbuscaclientes);
  $SQLbuscaclientes->execute();
  $totalclientes = $SQLbuscaclientes->rowCount();

  // Servidores
  $SQLbuscaservidores = "select * from acesso_servidor where id_usuario='" . $usuario['id_usuario'] . "'";
  $SQLbuscaservidores = $conn->prepare($SQLbuscaservidores);
  $SQLbuscaservidores->execute();
  $servidoresclientes = $SQLbuscaservidores->rowCount();

  // Cotas
  $totaldecotas = 0;
  $SQLbuscacontasssh = "select sum(qtd) as cotas from acesso_servidor where id_usuario='" . $usuario['id_usuario'] . "'";
  $SQLbuscacontasssh = $conn->prepare($SQLbuscacontasssh);
  $SQLbuscacontasssh->execute();
  $minhascotas = $SQLbuscacontasssh->fetch();
  $totaldecotas += $minhascotas['cotas'];
} else {
  // Contas
  $SQLbuscacontinhas = "select * from usuario_ssh where id_usuario='" . $usuario['id_usuario'] . "'";
  $SQLbuscacontinhas = $conn->prepare($SQLbuscacontinhas);
  $SQLbuscacontinhas->execute();
  $totalcontas = $SQLbuscacontinhas->rowCount();

  // Cotas
  $totaldecotas2 = 0;
  $SQLbuscacontasssh2 = "select sum(acesso) as cotas from usuario_ssh where id_usuario='" . $usuario['id_usuario'] . "'";
  $SQLbuscacontasssh2 = $conn->prepare($SQLbuscacontasssh2);
  $SQLbuscacontasssh2->execute();
  $minhascotas2 = $SQLbuscacontasssh2->fetch();
  $totaldecotas2 += $minhascotas2['cotas'];

  // Faturas
  if ($usuario['id_mestre'] == 0) {
    $SQLbuscafaturinhas = "select * from fatura where usuario_id='" . $usuario['id_usuario'] . "' and status='pendente'";
    $SQLbuscafaturinhas = $conn->prepare($SQLbuscafaturinhas);
    $SQLbuscafaturinhas->execute();
    $minhasfatu = $SQLbuscafaturinhas->rowCount();
  } else {
    // Faturas
    $SQLbuscafaturinhas = "select * from fatura_clientes where usuario_id='" . $usuario['id_usuario'] . "' and status='pendente'";
    $SQLbuscafaturinhas = $conn->prepare($SQLbuscafaturinhas);
    $SQLbuscafaturinhas->execute();
    $minhasfatu = $SQLbuscafaturinhas->rowCount();
  }
}

?>

<!-- Noticias -->
<?php if ($procnoticias->rowCount() > 0) {
  $noticia = $procnoticias->fetch();

  $datapega = $noticia['data'];
  $data = date('D', strtotime($datapega));
  $mes = date('M', strtotime($datapega));
  $dia = date('d', strtotime($datapega));
  $ano = date('Y', strtotime($datapega));

  $semana = array(
    'Sun' => 'Domingo',
    'Mon' => 'Segunda-Feira',
    'Tue' => 'Terça-Feira',
    'Wed' => 'Quarta-Feira',
    'Thu' => 'Quinta-Feira',
    'Fri' => 'Sexta-Feira',
    'Sat' => 'Sábado'
  );

  $mes_extenso = array(
    'Jan' => 'Janeiro',
    'Feb' => 'Fevereiro',
    'Mar' => 'Marco',
    'Apr' => 'Abril',
    'May' => 'Maio',
    'Jun' => 'Junho',
    'Jul' => 'Julho',
    'Aug' => 'Agosto',
    'Sep' => 'Setembro',
    'Oct' => 'Outubro',
	'Nov' => 'Novembro',
    'Dec' => 'Dezembro'
  );


?>
<?php
                        $SQLSubSSH = "SELECT * FROM acesso_servidor where id_usuario='".$usuario['id_usuario']."' ORDER BY id_usuario desc";
                        $SQLSubSSH = $conn->prepare($SQLSubSSH);
                        $SQLSubSSH->execute();
                        if(($SQLSubSSH->rowCount()) > 0){
                        while($row = $SQLSubSSH->fetch()){


                        $buscaserver = "SELECT * FROM servidor where id_servidor='".$row['id_servidor']."'";
                        $buscaserver = $conn->prepare($buscaserver);
                        $buscaserver->execute();
                        $servidor = $buscaserver->fetch();


                        $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '".$servidor['id_servidor']."'  and id_usuario='".$row['id_usuario']."' ";
                        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                        $SQLAcessoSSH->execute();
                        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                        $acessos = $SQLAcessoSSH['quantidade'];
                        if($acessos==0){$acessos=0;}

                        $SQLUsuarioSSH = "SELECT * from usuario_ssh WHERE id_servidor = '".$servidor['id_servidor']."' and id_usuario='".$row['id_usuario']."'";
                        $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                        $SQLUsuarioSSH->execute();
                        $contas = $SQLUsuarioSSH->rowCount();
                        if($contas==0){$contas=0;}

                        //Calcula os dias restante
                        $data_atual = date("Y-m-d");
                        $data_validade = $row['validade'];
                        if($data_validade > $data_atual){
                            $data1 = new DateTime( $data_validade );
                            $data2 = new DateTime( $data_atual );
                            $dias_acesso = 0;
                            $diferenca = $data1->diff( $data2 );
                            $ano = $diferenca->y * 364 ;
                            $mes = $diferenca->m * 30;
                            $dia = $diferenca->d;
                            $dias_acesso = $ano + $mes + $dia;

                        }else{
                            $dias_acesso = 0;
                        }

                        $SQLopen = "select * from ovpn WHERE servidor_id = '".$row['id_servidor']."' ";
                        $SQLopen = $conn->prepare($SQLopen);
                        $SQLopen->execute();
                        if($SQLopen->rowCount()>0){
                            $openvpn=$SQLopen->fetch();
                            $texto="<a href='../admin/pages/servidor/baixar_ovpn.php?id=".$openvpn['id']."' class=\"label label-info\">Baixar</a>";
                        }else{
                            $texto="<span class=\"label label-danger\">Indisponivel</span>";
                        }


                        ?>
                        
                            <?php
                            }

                            }


                            ?>

  <div class="demo-spacing-0 text-center mb-2">
    <div class="alert alert-primary alert-dismissible" role="alert">
      <h2 class="alert-heading text-warning"><i data-feather='alert-octagon'></i> <?php echo $noticia['titulo']; ?></h2>
      <div class="alert-body text-warning">
        <?php echo $noticia['subtitulo']; ?> <br />
        <?php echo $noticia['msg']; ?><br />
        <?php echo $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";; ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>


<?php } ?>

<section id="dashboard-analytics">
  <style>
    .img-fluid {
      max-width: 100%;
      height: 100px;
	  background: linear-gradient(118deg, #7367f0, rgba(255, 0, 0, 0.70));
    }
  </style>
  <div class="col-12">
    <div class="card card-profile border-primary">
      <img src="" class="img-fluid ">
      <div class="card-body">
        <div class="profile-image-wrapper">
          <div class="profile-image">
            <div class="avatar-content">
              <img src="../../../app-assets/images/avatars/<?php echo $avatarusu; ?>" alt="user">
            </div>
          </div>
        </div>
        <h3>BEM VINDOª <?php echo strtoupper($usuario['nome']); ?></h3>
		<a class="dropdown-item" href="?page=servidor/listar"><i class="me-50" data-feather="calendar"></i>Resta: <span class="badge badge-light-info rounded-pill ms-auto me-1"><?php echo $dias_acesso." dias"; ?></span></a>
        <span class="badge badge-light-primary profile-badge">
          <?php if ($usuario['subrevenda'] <> 'sim') {
            echo 'Revendedor';
          } else {
            echo 'Sub Revendedor';
          } ?>
        </span>
      </div>
    </div>
  </div>
</section>
<!-- Dashboard Analytics end -->
<section id="statistics-card">
  <div class="row">
  <?php if (($usuario['tipo'] == "revenda") and ($acesso_servidor > 0)) { ?>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=ssh/adicionar">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-success avatar-xl">
              <div class="avatar-content">
                <i data-feather='user-plus'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1">Criar Conta ssh</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=ssh/add_teste">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-warning avatar-xl">
              <div class="avatar-content">
                <i data-feather='clock'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1">Criar Teste ssh</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
	<?php } ?>
    <?php if ($usuario['subrevenda'] <> 'sim') { ?>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="card border-primary">
          <a href="home.php?page=usuario/adicionar">
            <div class="card-header d-flex flex-column align-items-center pb-0">
              <div class="avatar bg-light-info avatar-xl">
                <div class="avatar-content">
                  <i data-feather='user-plus'></i>
                </div>
              </div>
              <h4 class="text-bold-700 mt-1">Criar Revenda</h4>
              <p class="mb-2"></p>
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=ssh/online">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-success avatar-xl">
              <div class="avatar-content">
                <i data-feather='zap'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1"><?php echo $total_acesso_ssh_online; ?> Online</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=ssh/contas">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-success avatar-xl">
              <div class="avatar-content">
                <i data-feather='shield'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1 mb-25"><?php echo $quantidade_ssh; ?> Contas SSH</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
    <?php if (($usuario['tipo'] == "revenda") and ($usuario['subrevenda'] == 'nao')) { ?>
      <div class="col-lg-3 col-md-6 col-12">
        <div class="card border-primary">
          <a href="home.php?page=subrevenda/revendedores">
            <div class="card-header d-flex flex-column align-items-center pb-0">
              <div class="avatar bg-light-info avatar-xl">
                <div class="avatar-content">
                  <i data-feather='users'></i>
                </div>
              </div>
              <h4 class="text-bold-700 mt-1"><?php echo $quantidade_sub_revenda; ?> Revenda</h4>
              <p class="mb-2"></p>
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
    <?php if (($usuario['tipo'] == "revenda") and ($acesso_servidor > 0)) { ?>
      <div class="col-lg-3 col-md-6 col-12">
        <div class="card border-primary">
          <a href="?page=servidor/listar">
            <div class="card-header d-flex flex-column align-items-center pb-0">
              <div class="avatar bg-light-warning avatar-xl">
                <div class="avatar-content">
                  <i data-feather='server'></i>
                </div>
              </div>
              <h4 class="text-bold-700 mt-1"><?php echo $acesso_servidor; ?> Servidores</h4>
              <p class="mb-2"></p>
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=notificacoes/notificacoes&ler=all">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-danger avatar-xl">
              <div class="avatar-content">
                <i data-feather='bell'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1"><?php echo $totalnoti; ?> Notificações</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
    <?php if ($usuario['subrevenda'] <> 'sim') { ?>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="card border-primary">
          <a href="home.php?page=chamadosclientes/abertas">
            <div class="card-header d-flex flex-column align-items-center pb-0">
              <div class="avatar bg-light-danger avatar-xl">
                <div class="avatar-content">
                  <i data-feather='life-buoy'></i>
                </div>
              </div>
              <h4 class="text-bold-700 mt-1"><?php echo $all_chamados + $all_chamados_clientes; ?> Tickets</h4>
              <p class="mb-2"></p>
            </div>
          </a>
        </div>
      </div>
    <?php } else { ?>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="card border-primary">
          <a href="home.php?page=chamados/abertas">
            <div class="card-header d-flex flex-column align-items-center pb-0">
              <div class="avatar bg-light-info avatar-xl">
                <div class="avatar-content">
                  <i data-feather='life-buoy'></i>
                </div>
              </div>
              <h4 class="text-bold-700 mt-1"><?php echo $all_chamados; ?> Tickets</h4>
              <p class="mb-2"></p>
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-primary">
        <a href="home.php?page=downloads/downloads">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-primary avatar-xl">
              <div class="avatar-content">
                <i data-feather='download'></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1"><?php echo $todosarquivos; ?> Arquivos</h4>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>