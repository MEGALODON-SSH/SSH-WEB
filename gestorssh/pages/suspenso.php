<?php

require_once("system/seguranca.php");
require_once("system/config.php");
require_once("system/funcoes.system.php");

protegePagina("user");


$SQLUsuario = "SELECT * FROM usuario WHERE id_usuario = '" . $_SESSION['usuarioID'] . "'";
$SQLUsuario = $conn->prepare($SQLUsuario);
$SQLUsuario->execute();
$usuario = $SQLUsuario->fetch();


if (($usuario['ativo'] == 1) || ($usuario['tipo'] == 'vpn')) {
  echo '<script type="text/javascript">';
  echo   'alert("Sua conta não encontra-se Suspensa!");';
  echo   'window.location="../home.php";';
  echo '</script>';
  exit;
}

$idmestre = $usuario['id_mestre'];
$SQLU = "SELECT * FROM usuario WHERE id_usuario = '" . $idmestre . "'";
$SQLU = $conn->prepare($SQLU);
$SQLU->execute();
$info = $SQLU->fetch();
if($info['email'] != ''){
  $email = $info['email'];
}else{
  $email = 'xxxx@gamail.com';
}
if($info['celular'] != ''){
  $contato = $info['celular'];
}else{
  $contato = '11000000000';
}
?>

<body class="vertical-layout 1-column navbar-floating footer-static blank-page blank-page pace-done menu-hide vertical-overlay-menu" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-body">
        <section class="row flexbox-container">
          <div class="mb-5">
            <div class="col-xl-12 col-12 d-flex justify-content-center">
              <div class="row">
                <div class="col-12">
                  <div class="card border-danger">
                    <div class="card-content">
                      <div class="card-body text-center">
                        <div class="mb-2 text-center">
                          <h3 class="text-danger"><i class="font-medium-5" data-feather='info'></i>Seu acesso está suspenso!<br></h3>
                        </div>
                        <div class="mb-2">
                          <div class="avatar avatar-xl bg-danger">
                            <div class="avatar-content">
                            <i data-feather='slash'></i>
                            </div>
                          </div>
                        </div>
                        <div class="text-center">
                          <div class="form-group text-center mb-2">
                            <div class="demo-spacing-0">
                              <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading"><i data-feather='dollar-sign'></i>Pagamento em atraso!</h4>
                                <div class="alert-body">
                                  Entre em contato com seu MASTER e <br>resolva a sua situação agora mesmo!
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-2">
                             <h5><i class="font-medium-2 text-primary" data-feather='smartphone'></i>CONTATO: <a class="text-bold-800 " href="https://api.whatsapp.com/send?phone=55<?php echo $contato;?>&text=Ol%C3%A1%2C%20sou%20revendedor%20com%20nome%20<?php echo strtoupper($usuario['nome']);?>%2C%20estou%20suspenso%20e%20quero%20regularizar%20essa%20pend%C3%AAncia!%20" target="_blank">CLICK AQUI 🚀</a></h5>
                          </div>
                          <a href="../index.php" class="btn btn-primary btn-block"><i data-feather='log-in'></i><b>Logar</b></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <footer class="footer footer-static footer-light mt-5">
      <div class="text-center">
        <p class="clearfix blue-grey lighten-2 mb-0">
          <span class="center">
            &copy; <script>
              document.write(new Date().getFullYear())
            </script>
            <a class="text-bold-800 " href="https://api.whatsapp.com/send?phone=55<?php echo $contato;?>&text=Ol%C3%A1%2C%20sou%20revendedor%20e%20minha%20revenda%20se%20chama%20<?php echo strtoupper($usuario['nome']);?>%20e%20estou%20precisando%20de%20suporte!" target="_blank">EMPRESA 🚀</a><br>Todos os direitos reservados.</span>
          </span>
        </p>
      </div>
    </footer>
  </div>

  <!-- BEGIN: Footer-->
  <script src="../app-assets/vendors/js/vendors.min.js"></script>
  <script src="../app-assets/vendors/js/charts/apexcharts.min.js"></script>
  <script src="../app-assets/vendors/js/extensions/tether.min.js"></script>
  <script src="../app-assets/js/core/app-menu.js"></script>
  <script src="../app-assets/js/core/app.js"></script>
  <script src="../app-assets/js/scripts/components.js"></script>
  <script src="../app-assets/js/scripts/pages/dashboard-analytics.js"></script>
  <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>