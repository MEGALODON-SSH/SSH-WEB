<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");

if (isset($_GET['requisicao'])) {


    if ($_GET['requisicao'] == 1) {
        $SQLSub = "SELECT * FROM usuario_ssh";
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
        }
    } elseif ($_GET['requisicao'] == 2) {

        $total_acesso_ssh_online = 0;
        $SQLAcessoSSH = "SELECT sum(online) AS quantidade  FROM usuario_ssh  ";
        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
        $SQLAcessoSSH->execute();
        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
        $total_acesso_ssh_online += $SQLAcessoSSH['quantidade'];

        echo $total_acesso_ssh_online;
    }
}

?>