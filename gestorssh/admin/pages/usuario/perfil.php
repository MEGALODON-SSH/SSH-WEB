<link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
<script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}


if (isset($_GET["id_usuario"])) {
	$id_usuario = $_GET['id_usuario'];
	$diretorio = "../../admin/home.php?page=usuario/perfil&id_usuario=" . $id_usuario;
	$SQLUsuario = "select * from usuario WHERE id_usuario = '" . $id_usuario . "'  ";
	$SQLUsuario = $conn->prepare($SQLUsuario);
	$SQLUsuario->execute();
	$usuario = $SQLUsuario->fetch();
	if (($SQLUsuario->rowCount()) <= 0) {
		echo '<script type="text/javascript">';
		echo 	'alert("O usuario nao existe!");';
		echo	'window.location="home.php?page=usuario/listar";';
		echo '</script>';
		exit;
	}

	// avatares
	switch ($usuario['avatar']) {
		case 1:
			$avatarusu = "avatar1.png";
			break;
		case 2:
			$avatarusu = "avatar2.png";
			break;
		case 3:
			$avatarusu = "avatar3.png";
			break;
		case 4:
			$avatarusu = "avatar4.png";
			break;
		case 5:
			$avatarusu = "avatar5.png";
			break;
		default:
			$avatarusu = "boxed-bg.png";
			break;
	}

	$SQLUsuarioSSH = "select * from usuario_ssh WHERE id_usuario = '" . $id_usuario . "' ";
	$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
	$SQLUsuarioSSH->execute();
	$total_ssh = $SQLUsuarioSSH->rowCount();
	$SQLSubrevendedores = "select * from usuario WHERE id_mestre = '" . $id_usuario . "' and tipo='revenda' and subrevenda='sim' ";
	$SQLSubrevendedores = $conn->prepare($SQLSubrevendedores);
	$SQLSubrevendedores->execute();
	$todossubrevendedores = $SQLSubrevendedores->rowCount();

	if (($SQLSubrevendedores->rowCount()) > 0) {
		while ($subrow = $SQLSubrevendedores->fetch()) {
			$quantidade_ssh_subs = 0;
			$SQLSubSSHsubs = "select * from usuario_ssh WHERE id_usuario = '" . $subrow['id_usuario'] . "'  ";
			$SQLSubSSHsubs = $conn->prepare($SQLSubSSHsubs);
			$SQLSubSSHsubs->execute();
			$sshsubs = $SQLSubSSHsubs->rowCount();
			$SQLSubUSUARIOSsubs = "select * from usuario WHERE id_mestre = '" . $subrow['id_usuario'] . "' ";
			$SQLSubUSUARIOSsubs = $conn->prepare($SQLSubUSUARIOSsubs);
			$SQLSubUSUARIOSsubs->execute();
			$quantidade_USUARIOS_subs += $SQLSubUSUARIOSsubs->rowCount();
			$sshsubs132 = $SQLSubUSUARIOSsubs->rowCount();
		}
	}


	$total_ssh_sub = 0;
	if ($usuario['tipo'] == "revenda") {
		$SQLUsuarioSUB = "select * from usuario WHERE id_mestre = '" . $_GET['id_usuario'] . "' and subrevenda='nao' ";
		$SQLUsuarioSUB = $conn->prepare($SQLUsuarioSUB);
		$SQLUsuarioSUB->execute();
		$total_user = $SQLUsuarioSUB->rowCount();
		if (($SQLUsuarioSUB->rowCount()) > 0) {
			while ($row_sub = $SQLUsuarioSUB->fetch()) {
				$SQLUsuarioSSH = "select * from usuario_ssh WHERE id_usuario = '" . $row_sub['id_usuario'] . "' ";
				$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
				$SQLUsuarioSSH->execute();
				$total_ssh_sub += $SQLUsuarioSSH->rowCount();
			}
			$total = $total_ssh + $total_ssh_sub;
		}
	}
	if ($usuario['id_mestre'] != 0) {
		$SQLUsuario = "select * from usuario WHERE id_usuario = '" . $usuario['id_mestre'] . "'  ";
		$SQLUsuario = $conn->prepare($SQLUsuario);
		$SQLUsuario->execute();
		$usuario_mestre = $SQLUsuario->fetch();
	}
} else {
	echo '<script type="text/javascript">';
	echo 	'alert("Preencha todos os campos!");';
	echo	'window.location="home.php?page=usuario/listar";';
	echo '</script>';
}

if ($usuario['ativo'] == 2) {
	$sts = "danger";
} else {
	$sts = "info";
}

?>

<?php if ($usuario['ativo'] == 2) { ?>
	<center>
		<div class='alert alert-danger col-12 col-md-12' role='alert'>
			<div class='alert-body d-center align-items-center'>
				<span>CONTA SUSPENSA</span>
			</div>
		</div>
	</center>
<?php } ?>


<div class="row">
	<div class="col-lg-6 col-xlg-6 col-md-6">
		<div class="card border-<?php echo $sts; ?>">

			<div class="card-body little-profile text-center">
				<div class="pro-img">
					<img class="img-circle" src="../app-assets/images/avatars/<?php echo $avatarusu; ?>" height="60" width="60" alt="user" />
				</div>
				<h3 class="profile-username text-center"><?php echo $usuario['nome']; ?></h3>
				<?php if ($usuario['tipo'] == "vpn") { ?>
					<p class="text-muted text-center">Usuário SSH</p>
					<?php } elseif ($usuario['tipo'] == "revenda") {
					if ($usuario['subrevenda'] == 'sim') {
					?>
						<p class="text-primary text-center">Sub-Revendedor de [<a class="text-info" href="home.php?page=usuario/perfil&id_usuario=<?php echo $usuario_mestre['id_usuario']; ?>"><?php echo $usuario_mestre['nome']; ?></a>]</p>
					<?php } else {
					?>
						<p class="text-primary text-center">Revendedor</p>
				<?php
					}
				} ?>

				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Contas SSH</b> <a class="badge badge-pill bg-info float-right text-white"><?php echo $total_ssh; ?></a>
					</li>
					<?php if ($usuario['tipo'] == "revenda") { ?>
						<!--<li class="list-group-item">
							<b>Contas dos Usuários SSH</b> <a class="badge badge-pill bg-danger float-right text-white"><?php echo $total_ssh_sub; ?></a>
						</li>-->
						<?php if ($usuario['subrevenda'] == 'sim') {
							$totalserversadd = "select * from acesso_servidor WHERE id_usuario = '" . $usuario['id_usuario'] . "' ";
							$totalserversadd = $conn->prepare($totalserversadd);
							$totalserversadd->execute();
							$total_servers_add = $totalserversadd->rowCount();
						?>
							<li class="list-group-item">
								<b>Servidores Adicionados</b>
								<a class="badge badge-pill bg-warning float-right text-white"><?php echo $total_servers_add; ?></a>
							</li>
						<?php } ?>
						<?php if ($usuario['subrevenda'] == 'nao') { ?>
							<li class="list-group-item">
								<b>Sub Revenda</b> <a class="badge badge-pill bg-warning float-right text-white"><?php echo $todossubrevendedores; ?></a>
							</li>
							<!--<li class="list-group-item">
								<?php if ($quantidade_USUARIOS_subs > 0) { ?>
									<b>Usuários dos Sub</b> <a class="badge badge-pill bg-info float-right text-white"><?php echo $quantidade_USUARIOS_subs; ?></a>
								<?php } else { ?>
									<b>Usuários dos Sub</b> <a class="badge badge-pill bg-info float-right text-white">0</a>
								<?php } ?>
							</li>-->
						<?php } ?>
					<?php  } ?>
				</ul>

				<script type="text/javascript">
					function excluir_usuario() {
						Swal.fire({
							title: 'Excluir',
							text: "Realmente deseja excluir ?",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#28c76f',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Sim',
							cancelButtonText: 'Nao'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = '../pages/system/funcoes.usuario.php?&op=deletar&id_usuario=<?php echo $usuario['id_usuario']; ?>&diretorio=../../admin/home.php?page=usuario/revenda&owner=<?php echo $accessKEY; ?>'
							}
						})
					}

					function suspende_usuario() {
						Swal.fire({
							title: 'Suspender',
							text: "Realmente deseja suspender ?",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#28c76f',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Sim',
							cancelButtonText: 'Nao'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = '../pages/system/funcoes.usuario.php?&id_usuario=<?php echo $usuario['id_usuario']; ?>&diretorio=../../admin/home.php?page=usuario/listar&owner=<?php echo $accessKEY; ?>&op=suspender'
							}
						})
					}
				</script>

				<br>
				<ul class="list-group">
					<li class="list-group mb-1">
						<div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
							<a href="?page=usuario/addservidor" class="btn btn-success waves-effect waves-float waves-light"><i data-feather='server'></i> ADICIONAR SERVIDOR</a>
						</div>
					</li>
					<li class="list-group mb-1">
						<div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
							<a href="../pages/system/funcoes.usuario.php?&id_usuario=<?php echo $usuario['id_usuario']; ?>&diretorio=../../admin/home.php?page=usuario/listar&owner=<?php echo $accessKEY; ?>&op=senha" class="btn btn-primary waves-effect waves-float waves-light"><i data-feather='key'></i> REENVIAR SENHA</a>
						</div>
					</li>
					<li class="list-group mb-1">
						<?php if ($usuario['ativo'] == 1) { ?>
							<div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
								<a onclick="suspende_usuario()" class="btn btn-warning waves-effect waves-float waves-light"><i data-feather='user-x'></i> SUSPENDER TUDO</a>
							</div>
						<?php } else { ?>
							<div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
								<a href="../pages/system/funcoes.usuario.php?&id_usuario=<?php echo $usuario['id_usuario']; ?>&diretorio=../../admin/home.php?page=usuario/listar&owner=<?php echo $accessKEY; ?>&op=ususpender" class="btn btn-success waves-effect waves-float waves-light"><i data-feather='user-check'></i> REATIVAR TUDO</a>
							</div>
						<?php } ?>
					</li>
					<li class="list-group mb-1">
						<div class="d-grid col-lg-12 col-md-12 mb-1 mb-lg-0">
							<a onclick="excluir_usuario()" class="btn btn-danger waves-effect waves-float waves-light"><i data-feather='trash'></i> DELETAR TUDO</a>
						</div>
					</li>
				</ul>

			</div>



		</div>

	</div>


	<!-- /.col -->
	<div class="col-md-6">
		<div class="card border-primary">
			<div class="card-body p-b-0">
				<h4 class="card-title"><i class="fa fa-edit"></i> Gerenciar Revenda</i></h4>
				<!-- Nav tabs -->

				<ul class="nav nav-tabs customtab" role="tablist">
					<li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#activity" role="tab" aria-selected="true"><i data-feather="info"></i>Informações do Usuário</span></a> </li>
					<?php if ($usuario['tipo'] == "revenda") { ?>
						<?php if ($usuario['subrevenda'] == "nao") { ?>
							<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#timeline" role="tab" aria-selected="true"><i data-feather="server"></i>Servidores</span></a> </li>
						<?php } ?>
						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#users" role="tab" aria-selected="true"><i data-feather="users"></i>Sub Revenda</span></a> </li>
					<?php } ?>
					<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#ssh" role="tab" aria-selected="true"><i data-feather="shield"></i>Contas SSH</span></a> </li>
					<?php if ($usuario['subrevenda'] == "nao") { ?>
						<!--<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#fatura" role="tab" aria-selected="true"><i data-feather="money"></i>Gerar Fatura</span></a> </li>-->
					<?php } ?>
				</ul>

				<div class="tab-content">
					<div class="active tab-pane" id="activity">
						<form class="form-horizontal" role="perfil" name="perfil" id="perfil" action="pages/usuario/editar_exe.php" method="post">
							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Login</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="user"></i></span>
									<input type="text" class="form-control" disabled value="<?php echo $usuario['login']; ?>">
									<input type="hidden" class="form-control" id="idusuario" name="idusuario" value="<?php echo $usuario['id_usuario']; ?>">
								</div>
							</div>
							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Nome</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="user"></i></span>
									<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>">
								</div>
							</div>
							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Senha</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="key"></i></span>
									<input type="password" class="form-control" id="senha" name="senha" value="<?php echo $usuario['senha']; ?>">
								</div>
							</div>
							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Email</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="mail"></i></span>
									<input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>">
								</div>
							</div>
							<div class="mb-1">
								<label class="form-label" for="first-name-icon">Celular</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="smartphone"></i></span>
									<input type="text" class="form-control" id="celular" name="celular" value="<?php echo $usuario['celular']; ?>">
								</div>
							</div>
							<div class="mb-2">
								<label class="form-label" for="first-name-icon">Data de cadastro</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i data-feather="calendar"></i></span>
									<input type="text" class="form-control" disabled value="<?php echo $usuario['data_cadastro']; ?>">
								</div>
							</div>
							<?php if($usuario['permitir_demo'] == 0) { 
								$checkssh = 'checked';
							} ?>
							<?php if($usuario['permitir_demo'] == 1) { 
								$checkv2 = 'checked';
							} ?>
							<div class="mb-2">
								<div class="row custom-options-checkable g-2">
									<div class="col-md-6">
										
										<input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon2" value="0" <?php echo $checkssh; ?> />
										<label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
											<i data-feather="shield" class="font-large-1 mb-75"></i>
											<span class="custom-option-item-title h4 d-block">ACESSO SSH</span>
											<small>Ele e os revendedores poderá criar apenas contas ssh</small>
										</label>
									</div>
									<div class="col-md-6">
										<input class="custom-option-item-check" type="radio" name="acesso" id="customOptionsCheckableRadiosWithIcon3" value="1" <?php echo $checkv2; ?> />
										<label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
											<i data-feather="link" class="font-large-1 mb-75"></i>
											<span class="custom-option-item-title h4 d-block">ACESSO SSH E V2RAY</span>
											<small>Ele e os revendedores poderá criar contas ssh e v2ray</small>
										</label>
									</div>
								</div>
							</div>
							<div class="text-center mb-1">
								<button type="submit" class="btn btn-success">Alterar Dados</button>
							</div>
						</form>

					</div>
					<!-- /.tab-pane -->
					<?php if ($usuario['tipo'] == "revenda") { ?>
						<?php if ($usuario['subrevenda'] == 'nao') { ?>
							<div class=" tab-pane" id="timeline">

								<div class="row">
									<div class="col-lg-12">
										<div class="box box-primary">
											<!-- /.box-header -->
											<div class="table-responsive">
												<table class="table table-hover">
													<tr>
														<th>Servidor</th>
														<th>Limite (fora revenda)</th>
														<th>Validade</th>
														<th>Sub Revenda</th>
														<th>Contas SSH</th>
														<!--<th>Acessos SSH</th>-->
														<th></th>

													</tr>
													<?php
													$SQLAcessoServidor = "select * from acesso_servidor where id_usuario='" . $_GET['id_usuario'] . "'  ";
													$SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
													$SQLAcessoServidor->execute();

													if (($SQLAcessoServidor->rowCount()) > 0) {
														while ($row2 = $SQLAcessoServidor->fetch()) {

															$SQLTotalUser = "select * from usuario WHERE id_usuario = '" . $_GET['id_usuario'] . "' ";
															$SQLTotalUser = $conn->prepare($SQLTotalUser);
															$SQLTotalUser->execute();
															$total_user = $SQLTotalUser->rowCount();



															$SQLServidor = "select * from servidor where id_servidor = '" . $row2['id_servidor'] . "'";
															$SQLServidor = $conn->prepare($SQLServidor);
															$SQLServidor->execute();

															$SQLsubservidores = "select * from acesso_servidor WHERE id_servidor_mestre = '" . $row2['id_acesso_servidor'] . "'";
															$SQLsubservidores = $conn->prepare($SQLsubservidores);
															$SQLsubservidores->execute();
															$total_subservers = $SQLsubservidores->rowCount();

															$contas = 0;
															$acessos = 0;


															$SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '" . $row2['id_servidor'] . "'
																and id_usuario='" . $_GET['id_usuario'] . "'  ";
															$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
															$SQLUsuarioSSH->execute();
															$contas += $SQLUsuarioSSH->rowCount();

															$SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row2['id_servidor'] . "'  and id_usuario='" . $_GET['id_usuario'] . "' ";
															$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
															$SQLAcessoSSH->execute();
															$SQLAcessoSSH = $SQLAcessoSSH->fetch();
															$acessos += $SQLAcessoSSH['quantidade'];



															$SQLUsuarioSub = "select * from usuario WHERE id_mestre = '" . $_GET['id_usuario'] . "' and subrevenda='nao' ";
															$SQLUsuarioSub = $conn->prepare($SQLUsuarioSub);
															$SQLUsuarioSub->execute();

															if (($SQLUsuarioSub->rowCount()) > 0) {
																while ($row3 = $SQLUsuarioSub->fetch()) {

																	$SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '" . $row2['id_servidor'] . "'
																		and id_usuario='" . $row3['id_usuario'] . "'  ";
																	$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
																	$SQLUsuarioSSH->execute();
																	$contas += $SQLUsuarioSSH->rowCount();

																	$SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor = '" . $row2['id_servidor'] . "'  and id_usuario='" . $row3['id_usuario'] . "' ";
																	$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
																	$SQLAcessoSSH->execute();
																	$SQLAcessoSSH = $SQLAcessoSSH->fetch();
																	$acessos += $SQLAcessoSSH['quantidade'];
																}
															}


															if (($SQLServidor->rowCount()) > 0) {
																while ($row3 = $SQLServidor->fetch()) {

																	$qtd_srv = 0;

																	//Calcula os dias restante
																	$data_atual = date("Y-m-d");
																	$data_validade = $row2['validade'];
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

													?>


																	<tr>
																		<td><?php echo $row3['nome']; ?></td>
																		<td><?php echo $row2['qtd']; ?></td>
																		<td>
																			<span class="pull-left-container" style="margin-right: 5px;">
																				<span class="label label-primary pull-left">
																					<?php echo $dias_acesso . "  dias   "; ?>
																				</span>
																			</span>

																		</td>
																		<td><?php echo $total_subservers; ?></td>
																		<td><?php echo $contas; ?></td>
																		<!--<td><?php echo $acessos; ?></td>-->
																		<td>

																			<script>
																				function apaga_tudo<?php echo $row2['id_acesso_servidor']; ?>() {
																					Swal.fire({
																						title: 'Remover Servidor',
																						text: "Também sera removido o servidor dos revendedores dele, Realmente deseja remover ?",
																						icon: 'warning',
																						showCancelButton: true,
																						confirmButtonColor: '#28c76f',
																						cancelButtonColor: '#d33',
																						confirmButtonText: 'Sim',
																						cancelButtonText: 'Nao'
																					}).then((result) => {
																						if (result.isConfirmed) {
																							window.location.href = 'pages/usuario/remover_servidor.php?&id_acesso=<?php echo $row2['id_acesso_servidor']; ?>'
																						}
																					})
																				}
																			</script>
																			<!-- <a href="#" class="btn btn-warning">Editar Acesso</a> -->
																			<div class="btn-group" role="group" aria-label="Basic example">
																				<a onclick="apaga_tudo<?php echo $row2['id_acesso_servidor']; ?>()" class="btn-sm btn-danger waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
																						<polyline points="3 6 5 6 21 6"></polyline>
																						<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
																					</svg></a>
																			</div>


																		</td>

																	</tr>


													<?php
																}
															}
														}
													}
													?>
												</table>
											</div>
											<!-- /.box-body -->
										</div>
										<!-- /.box -->
									</div>
								</div>
							</div>
							<!-- /.tab-pane -->
						<?php } ?>
						<div class="tab-pane" id="users">
							<div class="row">
								<div class="col-lg-12">
									<div class="box box-primary">

										<!-- /.box-header -->
										<div class="table-responsive">
											<table class="table table-hover">
												<tr>
													<th>Login</th>
													<th>Nome</th>
													<th>Contas</th>

												</tr>
												<?php

												$SQLUsuarioSUB = "select * from usuario where id_mestre='" . $usuario['id_usuario'] . "'  ";
												$SQLUsuarioSUB = $conn->prepare($SQLUsuarioSUB);
												$SQLUsuarioSUB->execute();


												if (($SQLUsuarioSUB->rowCount()) > 0) {
													// output data of each row
													while ($row_user = $SQLUsuarioSUB->fetch()) {

														$total_ssh = 0;

														$SQLUsuarioSSH = "select * from usuario_ssh where id_usuario = '" . $row_user['id_usuario'] . "' ";
														$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
														$SQLUsuarioSSH->execute();
														$total_ssh += $SQLUsuarioSSH->rowCount();

														$color = "";
														if ($row_user['ativo'] == 2) {
															$color = "bgcolor='#FF6347'";
														}

												?>


														<tr <?php echo $color; ?>>
															<td><?php echo $row_user['login']; ?></td>
															<td><?php echo $row_user['nome']; ?></td>
															<td><?php echo $total_ssh; ?></td>


														</tr>


												<?php

													}
												}
												?>
											</table>
										</div>
										<!-- /.box-body -->
									</div>
									<!-- /.box -->
								</div>
							</div>


						</div>
					<?php } ?>
					<!-- /.tab-pane -->
					<div class=" tab-pane" id="ssh">
						<div class="row">
							<div class="col-lg-12">
								<div class="box box-primary">
									<!-- /.box-header -->
									<div class="table-responsive">
										<table class="table table-hover">
											<tr>
												<th>Login</th>
												<th>Servidor</th>
												<th>Acessos</th>
												<th>Dono</th>
											</tr>
											<?php
											$SQLUsuarioSSH = "select * from usuario_ssh where id_usuario='" . $usuario['id_usuario'] . "'";
											$SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
											$SQLUsuarioSSH->execute();


											if (($SQLUsuarioSSH->rowCount()) > 0) {

												// output data of each row
												while ($row_user = $SQLUsuarioSSH->fetch()) {

													$SQLServidor = "select * from servidor where id_servidor='" . $row_user['id_servidor'] . "'  ";
													$SQLServidor = $conn->prepare($SQLServidor);
													$SQLServidor->execute();
													$servidor = $SQLServidor->fetch();
													$color = "";

													$acessos = 0;
													$SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario_ssh='" . $row_user['id_usuario_ssh'] . "' ";
													$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
													$SQLAcessoSSH->execute();
													$SQLAcessoSSH = $SQLAcessoSSH->fetch();
													$acessos += $SQLAcessoSSH['quantidade'];
													if ($row_user['status'] == 2) {

														$color = "bgcolor='#FF6347'";
													}

											?>
													<tr <?php echo $color; ?>>
														<td><?php echo $row_user['login']; ?></td>
														<td><?php echo $servidor['nome']; ?></td>
														<td><?php echo $acessos; ?></td>
														<td>Usuario Atual</td>
													</tr>
													<?php


												}
											}
											if ($usuario['tipo'] == "revenda") {

												$SQLUserSub = "select * from usuario where id_mestre = '" . $usuario['id_usuario'] . "'  ";
												$SQLUserSub = $conn->prepare($SQLUserSub);
												$SQLUserSub->execute();

												if (($SQLUserSub->rowCount()) > 0) {

													while ($row_user_sub = $SQLUserSub->fetch()) {
														$SQLSubSSH = "select * from usuario_ssh where id_usuario='" . $row_user_sub['id_usuario'] . "'  ";
														$SQLSubSSH = $conn->prepare($SQLSubSSH);
														$SQLSubSSH->execute();

														if (($SQLSubSSH->rowCount()) > 0) {
															while ($row_ssh_sub = $SQLSubSSH->fetch()) {

																$SQLServidor = "select * from servidor where id_servidor='" . $row_ssh_sub['id_servidor'] . "'  ";
																$SQLServidor = $conn->prepare($SQLServidor);
																$SQLServidor->execute();
																$servidor = $SQLServidor->fetch();
																$color = "";
																$acessos  = 0;

																if ($row_ssh_sub['status'] == 2) {

																	$color = "bgcolor='#FF6347'";
																}

																$SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario_ssh='" . $row_ssh_sub['id_usuario_ssh'] . "'  ";
																$SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
																$SQLAcessoSSH->execute();
																$SQLAcessoSSH = $SQLAcessoSSH->fetch();
																$acessos += $SQLAcessoSSH['quantidade'];


													?>

																<tr <?php echo $color; ?>>
																	<td><?php echo $row_ssh_sub['login']; ?></td>
																	<td><?php echo $servidor['nome']; ?></td>
																	<td><?php echo $acessos; ?></td>
																	<td><?php echo $row_user_sub['login']; ?></td>
																</tr>

											<?php	}
														}
													}
												}
											}


											?>

										</table>
									</div>
									<!-- /.box-body -->
								</div>
								<!-- /.box -->
							</div>
						</div>
					</div>
					<!-- /.tab-pane -->
					<!-- /.tab-fatura -->
					<!--<div class="tab-pane" id="fatura">
						<div class="row">
							<div class="col-lg-12">
								<form class="form-horizontal" role="perfil" name="gerandofatura" id="gerandofatura" action="pages/usuario/gerarfatura_exe.php" method="post">
									<div class="form-group"><br>
										<input type="hidden" class="form-control" id="usuarioid" name="usuarioid" value="<?php echo $_GET['id_usuario']; ?>">
										<label for="exampleInputPassword1">Tipo da Fatura</label>
										<select class="form-control" name="tipofat">
											<?php if ($usuario['tipo'] == 'vpn') { ?><option value='1'> Acesso VPN</option><?php } ?>
											<option value='2'>Outros</option>
											<?php if ($usuario['tipo'] == 'revenda') { ?><option value='1' selected=selected>Revenda</option><?php } ?>
										</select>

									</div>

									<div class="form-group">
										<label for="exampleInputPassword1">Quantidade</label>
										<input type="number" class="form-control" id="qtd" name="qtd" value="1" required>
									</div>

									<div class="form-group">
										<label for="exampleInputPassword1">Valor</label>
										<input type="number" class="form-control" id="valor" name="valor" value="5" required>
									</div>
									<div class="form-group">
										<label for="exampleInputPassword1">Desconto</label>
										<input type="number" class="form-control" id="desconto" name="desconto" value="0" required>
									</div>

									<div class="form-group">

										<label for="exampleInputPassword1">Vencimento</label>
										<input type="number" class="form-control" id="venc" name="venc" value="5" required>
									</div>


									<div class="form-group">
										<label for="exampleInputPassword1">Descrição</label>
										<textarea class="form-control" name="msg" id="msg" rows="3" placeholder="Digite ..." required></textarea>
									</div>
									<?php if ($usuario['tipo'] == "revenda") { ?>
										<div class="form-group">
											<label for="exampleInputPassword1">Servidor</label>
											<select class="form-control" name="servidorid">
												<option selected=selected>Servidores</option>
												<?php
												$SQLServidor2 = "select * from servidor";
												$SQLServidor2 = $conn->prepare($SQLServidor2);
												$SQLServidor2->execute();

												if (($SQLServidor2->rowCount()) > 0) {
													// output data of each row
													while ($row22 = $SQLServidor2->fetch()) {

														$SQLAcessoServidor = "select * from acesso_servidor where id_servidor='" . $row22['id_servidor'] . "'  and  id_usuario = '" . $_GET['id_usuario'] . "'";
														$SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
														$SQLAcessoServidor->execute();
														$acc = $SQLAcessoServidor->fetch();

														if (($SQLAcessoServidor->rowCount()) > 0) {
												?>

															<option value="<?php echo $row22['id_servidor']; ?>"> <?php echo $row22['ip_servidor']; ?> - Acessos: <?php echo $acc['qtd']; ?> - VAL: <?php echo $acc['validade']; ?> </option>

														<?php
														} else { ?>
															<option value="<?php echo $row22['id_servidor']; ?>"> <?php echo $row22['nome']; ?> - <?php echo $row22['ip_servidor']; ?> - Não tem acesso </option>
												<?php }
													}
												}
												?>
											</select>

										</div>
									<?php } elseif ($usuario['tipo'] == "vpn") { ?>
										<div class="form-group">
											<label for="inputName" for="exampleInputPassword1">Contas SSH</label>
											<select class="form-control" name="account">
												<option value="outros" selected=selected>Gerar em Outros</option>
												<?php
												$SQLServidor2 = "select * from usuario_ssh where id_usuario='" . $_GET['id_usuario'] . "'";
												$SQLServidor2 = $conn->prepare($SQLServidor2);
												$SQLServidor2->execute();

												if (($SQLServidor2->rowCount()) > 0) {
													// output data of each row
													while ($row22 = $SQLServidor2->fetch()) {
														$data = $row22['data_validade'];

														$datacriado = $data;
														$dataconvcriado = substr($datacriado, 0, 10);
														$partes = explode("-", $dataconvcriado);
														$ano = $partes[0];
														$mes = $partes[1];
														$dia = $partes[2];

														/*
																$SQLAcessoServidor = "select * from acesso_servidor where id_servidor='".$row22['id_servidor']."'  and  id_usuario = '".$_GET['id_usuario']."'";
														        $SQLAcessoServidor = $conn->prepare($SQLAcessoServidor);
														        $SQLAcessoServidor->execute();
														        $acc=$SQLAcessoServidor->fetch();

														        if(($SQLAcessoServidor->rowCount()) > 0 ){   */
												?>
														<option value="<?php echo $row22['id_usuario_ssh']; ?>"> <?php echo $row22['login']; ?> - Acessos: <?php echo $row22['acesso']; ?> - VAL: <?php echo $dia; ?>/<?php echo $mes; ?> - <?php echo $ano; ?> </option>
												<?php /*
																			}else{ ?>
																	        <option value="<?php echo $row22['id_servidor'];?>" > <?php echo $row22['nome'];?> - <?php echo $row22['ip_servidor'];?> - Não tem acesso </option>
																	    <?php }   */
													}
												}


												?>
											</select>
										</div>

										<div class="form-group">
											<label for="inputName" for="exampleInputPassword1">Servidor</label>
											<select class="form-control" name="servidorid">
												<option value="outros" selected=selected>Outros (Tipo em outros Tbm)</option>
												<?php


												$SQLServidor2 = "select * from servidor";
												$SQLServidor2 = $conn->prepare($SQLServidor2);
												$SQLServidor2->execute();

												if (($SQLServidor2->rowCount()) > 0) {
													// output data of each row
													while ($row22 = $SQLServidor2->fetch()) {



														$SQLcriados = "select * from usuario_ssh where id_servidor='" . $row22['id_servidor'] . "'";
														$SQLcriados = $conn->prepare($SQLcriados);
														$SQLcriados->execute();
														$criados = $SQLcriados->rowCount();

												?>

														<option value="<?php echo $row22['id_servidor']; ?>"> <?php echo $row22['nome']; ?> - <?php echo $row22['ip_servidor']; ?> - Contas: <?php echo $criados; ?></option>

												<?php

													}
												}


												?>
											</select>
										</div>
									<?php } ?>



									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary">Gerar Fatura</button>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>-->
				</div>

			</div>
			<!-- /.tab-content -->
		</div>
		<!-- /.nav-tabs-custom -->
	</div>

	<!-- /.col -->
</div>
<!-- /.row -->

</section>
<!-- /.content -->