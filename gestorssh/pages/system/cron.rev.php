<?php
require_once('seguranca.php');
require_once('config.php');
$data = date("Y-m-d");
echo $data."\n\n";

$SQLSUS = "SELECT * FROM acesso_servidor ";
$SQLSUS = $conn->prepare($SQLSUS);
$SQLSUS->execute();
if(($SQLSUS->rowCount()) > 0){
    while($row = $SQLSUS->fetch()){
        $dtuser = substr($row[validade],0,10);
        if ($dtuser <= $data) {
            
            $SQLUSER = "SELECT * FROM usuario where  id_usuario='".$row['id_usuario']."' ";
            $SQLUSER = $conn->prepare($SQLUSER);
            $SQLUSER->execute();
            
            if(($SQLUSER->rowCount()) > 0){
                while($rowuser = $SQLUSER->fetch()){
                    if($rowuser[ativo] == 1){
                        echo "$dtuser $rowuser[id_usuario] $rowuser[nome] $rowuser[tipo] - VENCEU\n";
                        $SQLSSH = "update usuario_ssh set status='2', apagar='2' WHERE id_usuario = '".$rowuser[id_usuario]."'  ";
                        $SQLSSH = $conn->prepare($SQLSSH);
                        $SQLSSH->execute();
                        $SQLUserativo = "update usuario set ativo='2', apagar='2' WHERE id_usuario = '".$rowuser[id_usuario]."'  ";
                        $SQLUserativo = $conn->prepare($SQLUserativo);
                        $SQLUserativo->execute();
                        
                        $SQLUsuarioSub= "SELECT * FROM usuario where id_mestre =  '".$rowuser[id_usuario]."' ";
                        $SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
                        $SQLUsuarioSub->execute();
                        if (($SQLUsuarioSub->rowCount()) > 0) {
                            while($row1 = $SQLUsuarioSub->fetch()){
                                $SQLSSH1 = "update usuario_ssh set status='2', apagar='2' WHERE id_usuario = '".$row1['id_usuario']."'  ";
                                $SQLSSH1 = $conn->prepare($SQLSSH1);
                                $SQLSSH1->execute();
                                $SQLUser1 = "update usuario set ativo='2', apagar='2' WHERE id_usuario = '".$row1['id_usuario']."'  ";
                                $SQLUser1 = $conn->prepare($SQLUser1);
                                $SQLUser1->execute();
                            }
                        }
                    }
                }
            }
        }
    }

}else{
    echo "<br>Nenhum revendedor suspenso!<br>\n";
}
?>