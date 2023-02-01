<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })
</script>
<script type="text/javascript">
  $('.descricao').tooltipsy({
    offset: [0, 10],
    css: {
      'padding': '10px',
      'max-width': '200px',
      'color': '#303030',
      'background-color': '#f5f5b5',
      'border': '1px solid #deca7e',
      '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
      '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
      'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
      'text-shadow': 'none'
    }
  });
</script>
<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');

protegePagina("user");

if (isset($_GET['tipo'])) {
  $tipo = $_GET['tipo'];
} else {
  $tipo = 1;
}

switch ($tipo) {
  case 0:
    $tip = 'todos';
    break;
  case 1:
    $tip = 'ehi';
    break;
  case 2:
    $tip = 'apk';
    break;
  case 3:
    $tip = 'outros';
    break;
  default:
    $tip = 'todos';
    break;
}

$SQLUsuario = "SELECT * FROM usuario WHERE id_usuario = '" . $_SESSION['usuarioID'] . "'";
$SQLUsuario = $conn->prepare($SQLUsuario);
$SQLUsuario->execute();
$usuario = $SQLUsuario->fetch();

if ($tip == 'todos') {
  $SQLSubSSH = "SELECT * FROM arquivo_download where cliente_tipo='" . $usuario['tipo'] . "' or cliente_tipo='todos'  ORDER BY id desc";
} else {
  $SQLSubSSH = "SELECT * FROM arquivo_download where cliente_tipo='" . $usuario['tipo'] . "' or cliente_tipo='todos' and tipo='" . $tip . "'  ORDER BY id desc";
}


$SQLSubSSH = $conn->prepare($SQLSubSSH);
$SQLSubSSH->execute();

if (($SQLSubSSH->rowCount()) > 0) {
  while ($row = $SQLSubSSH->fetch()) {


    $dataatual = $row['data'];
    $dataconv = substr($dataatual, 0, 10);

    $partes = explode("-", $dataconv);
    $ano = $partes[0];
    $mes = $partes[1];
    $dia = $partes[2];

    switch ($row['operadora']) {
      case 'vivo':
        $bg = 'primary';
        break;
      case 'claro':
        $bg = 'danger';
        break;
      case 'tim':
        $bg = 'info';
        break;
      case 'oi':
        $bg = 'warning';
        break;
      default:
        $bg = 'primary';
        break;
    }

    switch ($row['tipo']) {
      case 'ehi':
        $ion = 'file';
        break;
      case 'apk':
        $ion = 'android';
        break;
      case 'outros':
        $ion = 'download';
        break;
      default:
        $ion = 'download';
        break;
    }

?>

    <div class="col-lg-3 col-sm-6 col-12">
      <div class="card border-<?php echo $bg; ?>">
        <a href="pages/downloads/baixar.php?id=<?php echo $row['id']; ?>" title="Descrição !" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="<?php echo $row['detalhes'];?>">
          <div class="card-header d-flex flex-column align-items-center pb-0">
            <div class="avatar bg-light-info avatar-xl">
              <div class="avatar-content">

                <i class="fa fa-<?php echo $ion; ?>"></i>
              </div>
            </div>
            <h4 class="text-bold-700 mt-1 text-success"><?php echo $row['nome']; ?></h4>
            <?php if ($row['status'] == 'funcionando') { ?>
              <span class="text-warning">Funcionando</span>
            <?php } elseif ($row['status'] == 'testes') { ?>
              <span class="text-danger">Em Testes</span>
            <?php } ?>
            <p class="mb-2"></p>
          </div>
        </a>
      </div>
    </div>
    
    
  <?php
  }
} else { ?>
  <div class="col-lg-3 col-sm-6 col-12">
    <div class="card border-danger">
      <a href="#">
        <div class="card-header d-flex flex-column align-items-center pb-0">
          <div class="avatar bg-light-danger avatar-xl">
            <div class="avatar-content">
              <i class="fa fa-times-circle-o"></i>
            </div>
          </div>
          <h4 class="text-bold-700 mt-1 text-danger">Nada foi encontrado !</h4>
          <p class="mb-2"></p>
        </div>
      </a>
    </div>
  </div>


<?php }


?>