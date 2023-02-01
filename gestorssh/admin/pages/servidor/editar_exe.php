<?php
require_once("../../../pages/system/seguranca.php");
require_once("../../../pages/system/config.php");
require_once("../../../pages/system/classe.ssh.php");
require_once("../../../pages/system/funcoes.system.php");
	protegePagina("admin");
	    if((isset($_POST["nomesrv"])) and (isset($_POST["ip"]))
                                        and (isset($_POST["login"]))
                                        and (isset($_POST["senha"]))
										and (isset($_POST["id_servidor"]))
                                        )
		{

		    if(!is_numeric($_POST['validade'])){
		        echo myalertuser('error', 'Só é permitido números na validade!', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['id_servidor'] .' ');
		    }
		    if(!is_numeric($_POST['limite'])){
				echo myalertuser('error', 'Só é permitido números no limite!', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['id_servidor'] .' ');
		    }


			$SQLServidor = "select * from servidor where id_servidor = '".$_POST['id_servidor']."'  ";
            $SQLServidor = $conn->prepare($SQLServidor);
            $SQLServidor->execute();
		    if(($SQLServidor->rowCount())>0){

				$servidor = $SQLServidor->fetch();

				$SQLServidor = "update servidor set   nome='".$_POST['nomesrv']."',
							                                    ip_servidor='".$_POST['ip']."',
																login_server='".$_POST['login']."',
                                                                senha='".$_POST['senha']."',
                                                               	site_servidor='".$_POST['siteserver']."',
                                                               	localizacao='".$_POST['localiza']."',
                                                               	localizacao_img='".$_POST['localiza_ico']."',
                                                               	validade='".$_POST['validade']."',
                                                               	limite='".$_POST['limite']."'
																WHERE id_servidor = '".$_POST['id_servidor']."' ";
                $SQLServidor = $conn->prepare($SQLServidor);
                $SQLServidor->execute();

							  echo myalertuser('success', 'O servidor '.$_POST['nomesrv'].' foi editado com sucesso!', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['id_servidor'] .' ');
							  
			}else{
				    echo myalertuser('error', 'Servidor  nao encontrado!', '../../home.php?page=servidor/listar');
			}


		}else{

		        echo myalertuser('error', 'Preencha!', '../../home.php?page=servidor/servidor&id_servidor='.$_POST['id_servidor'] .' ');
		}




?>