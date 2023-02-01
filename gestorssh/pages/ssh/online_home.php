<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');

protegePagina("user");

if (isset($_GET['requisicao'])) {

    $eubusca = "SELECT * FROM usuario where id_usuario='" . $_SESSION['usuarioID'] . "'";
    $eubusca = $conn->prepare($eubusca);
    $eubusca->execute();
    $eu = $eubusca->fetch();

    if ($_GET['requisicao'] == 1) {
        $SQLSub = "SELECT * FROM usuario_ssh where id_usuario='" . $_SESSION['usuarioID'] . "'";
        $SQLSub = $conn->prepare($SQLSub);
        $SQLSub->execute();

        if (($SQLSub->rowCount()) > 0) {
            $cont = 0;
            while ($rowSub = $SQLSub->fetch()) {

                if ($rowSub['online'] > 0) {
                    $SQLdono = "SELECT * FROM usuario where id_usuario='" . $rowSub['id_usuario'] . "'";
                    $SQLdono = $conn->prepare($SQLdono);
                    $SQLdono->execute();
                    $dono = $SQLdono->fetch();
                    $cont += 1;
                    if ($cont == 51) {
                        break;
                    }
?>
                    <div class="list-item d-flex align-items-center">
                        <div class="me-1">
                            <div class="avatar bg-light-success">
                                <div class="avatar-content"><i><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                        </svg></i>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-body flex-grow-1 d-flex justify-content-between">
                            <span class="me-3 text-success fw-bolder"><?php echo $rowSub['login']; ?></span> <span class="me-3 text-warning fw-bolder"><?php echo $rowSub['online']; ?> / <?php echo $rowSub['acesso']; ?></span>
                        </div>
                    </div>

                    <?php
                }
            }

            if ($eu['tipo'] == 'revenda') {
                $SQLSubuser = "SELECT * FROM usuario where id_mestre='" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
                $SQLSubuser = $conn->prepare($SQLSubuser);
                $SQLSubuser->execute();

                if (($SQLSubuser->rowCount()) > 0) {
                    while ($subus = $SQLSubuser->fetch()) {


                        $SQLSubssh = "SELECT * FROM usuario_ssh where id_usuario='" . $subus['id_usuario'] . "'";
                        $SQLSubssh = $conn->prepare($SQLSubssh);
                        $SQLSubssh->execute();

                        if (($SQLSubssh->rowCount()) > 0) {
                            while ($subussh = $SQLSubssh->fetch()) {
                                if ($subussh['online'] > 0) {

                                    $SQLdono = "SELECT * FROM usuario where id_usuario='" . $subussh['id_usuario'] . "'";
                                    $SQLdono = $conn->prepare($SQLdono);
                                    $SQLdono->execute();
                                    $dono = $SQLdono->fetch();
                    ?>

                                    <div class="list-item d-flex align-items-center">
                                        <div class="me-1">
                                            <div class="avatar bg-light-success">
                                                <div class="avatar-content"><i><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
                                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                                        </svg></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-item-body flex-grow-1 d-flex justify-content-between">
                                            <span class="me-3 text-success fw-bolder"><?php echo $subussh['login']; ?></span> <span class="me-3 text-warning fw-bolder"><?php echo $subussh['online']; ?> / <?php echo $subussh['acesso']; ?></span>
                                        </div>
                                    </div>

<?php
                                }
                            }
                        }
                    }
                }
            }
        }
    } elseif ($_GET['requisicao'] == 2) {


        $total_acesso_ssh_online = 0;
        $SQLAcessoSSH = "SELECT sum(online) AS quantidade  FROM usuario_ssh  where id_usuario='" . $_SESSION['usuarioID'] . "'";
        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
        $SQLAcessoSSH->execute();
        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
        $total_acesso_ssh_online += $SQLAcessoSSH['quantidade'];

        if ($eu['tipo'] == 'revenda') {
            $SQLSub = "select * from usuario WHERE id_mestre = '" . $_SESSION['usuarioID'] . "' and subrevenda='nao'";
            $SQLSub = $conn->prepare($SQLSub);
            $SQLSub->execute();

            if (($SQLSub->rowCount()) > 0) {

                while ($row = $SQLSub->fetch()) {

                    $SQLAcessoSSHon = "SELECT sum(online) AS quantidade  FROM usuario_ssh  where id_usuario='" . $row['id_usuario'] . "' ";
                    $SQLAcessoSSHon = $conn->prepare($SQLAcessoSSHon);
                    $SQLAcessoSSHon->execute();
                    $SQLAcessoSSHon = $SQLAcessoSSHon->fetch();
                    $total_acesso_ssh_online += $SQLAcessoSSHon['quantidade'];
                }
            }
        }

        echo $total_acesso_ssh_online;
    }
}

?>