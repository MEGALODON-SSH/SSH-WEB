<?php
include('../../pages/system/config.php');

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__)) {
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

$SQLmp = "select * from mercadopago";
$SQLmp = $conn->prepare($SQLmp);
$SQLmp->execute();
$mp = $SQLmp->fetch();


if (isset($_GET['delinfo'])) {

	$SQLinfo = "select * from informativo";
	$SQLinfo = $conn->prepare($SQLinfo);
	$SQLinfo->execute();

	if ($SQLinfo->rowCount() > 0) {

		$info = $SQLinfo->fetch();


		if (unlink("../admin/pages/noticias/" . $info['imagem'] . "")) {

			$SQLinfo2 = "delete from informativo";
			$SQLinfo2 = $conn->prepare($SQLinfo2);
			$SQLinfo2->execute();

			echo '<script type="text/javascript">';
			echo 	'alert("Informativo apagado!");';
			echo	'window.location="home.php?page=apis/gerenciar";';
			echo '</script>';
		} else {

			echo '<script type="text/javascript">';
			echo	'window.location="home.php?page=apis/gerenciar";';
			echo '</script>';
		}
	} else {

		echo '<script type="text/javascript">';
		echo 	'alert("Não foi encontrado nenhum informativo!");';
		echo	'window.location="home.php?page=apis/gerenciar";';
		echo '</script>';
	}
}

// desativa a noticia ativada
if (isset($_GET['delnoti'])) {
	$id = $_GET['delnoti'];
	$SQLnoticia = "select * from noticias where id='" . $id . "'";
	$SQLnoticia = $conn->prepare($SQLnoticia);
	$SQLnoticia->execute();

	if ($SQLnoticia->rowCount() > 0) {

		$not = $SQLnoticia->fetch();

		if ($not['status'] <> 'ativo') {
			echo '<script type="text/javascript">';
			echo	'window.location="home.php?page=apis/gerenciar";';
			echo '</script>';
			exit;
		}

		$SQLinfo2 = "update noticias set status='desativado' where id='" . $id . "'";
		$SQLinfo2 = $conn->prepare($SQLinfo2);
		$SQLinfo2->execute();

		echo '<script type="text/javascript">';
		echo	'window.location="home.php?page=apis/gerenciar";';
		echo '</script>';
	} else {

		echo '<script type="text/javascript">';
		echo	'window.location="home.php?page=apis/gerenciar";';
		echo '</script>';
	}
}


?>
<section id="input-style">
	<div class="row">
		<div class="col-12">
			<form class="" action="" method="post">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title text-danger">Gerenciar Email do Sistema</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12 text-center">
									<p>
										Funcional em: (Recuperar Senha, Enviar Email)
									</p>
								</div>
								<div class="col-12 text-center">
									<fieldset class="form-group">
										<a href="home.php?page=email/1etapasmtp" class="btn btn-danger"><i data-feather='mail'></i> Configurar o PHP Mailer</a>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<?php
$procnoticias = "select * FROM noticias where status='ativo'";
$procnoticias = $conn->prepare($procnoticias);
$procnoticias->execute();
?>
<section id="input-style">
	<div class="row">
		<div class="col-12">
			<form role="form" action="pages/apis/addnoti.php" method="post" enctype="multipart/form-data">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title text-warning">Notificar na Tela Inicial</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12">
									<p>
										Notificar seus clientes!
									</p>
								</div>
								<div class="col-md-6 col-12">
									<div class="mb-2">
										<label class="form-label" for="email-id-column">Titulo</label>
										<div class="input-group input-group-merge">
											<span class="input-group-text"><i data-feather='edit-3'></i></span>
											<input required="required" class="form-control" name="titu" placeholder="Titulo da noticia">
										</div>
									</div>
								</div>
								<div class="col-md-6 col-12">
									<div class="mb-2">
										<label class="form-label" for="email-id-column">Subtitulo</label>
										<div class="input-group input-group-merge">
											<span class="input-group-text"><i data-feather='edit-2'></i></span>
											<input required="required" class="form-control" name="subtitu" placeholder="Exemplo: Remova as contas expiradas!">
										</div>
									</div>
								</div>


								<div class="col-12">
									<div class="mb-2">
										<label class="form-label" for="email-id-column">Informe seu Texto</label>
										<div class="input-group input-group-merge">
											<textarea class="form-control" rows="10" name="msg" placeholder="Digite ... Use <br> para quebra de linhas"></textarea>
										</div>
									</div>
								</div>


								<div class="col-12 text-center">
									<div class="mb-1">
										<fieldset class="form-group">
											<button type="submit" name="adicionanoticia" class="btn btn-success">Adicionar</button>
											<?php if ($procnoticias->rowCount() > 0) {
												$noticia = $procnoticias->fetch(); ?>
												<a href="home.php?page=apis/gerenciar&delnoti=<?php echo $noticia['id']; ?>" name="remove" class="btn btn-danger">Desativar</a>
											<?php } ?>
										</fieldset>
									</div>
								</div>
								<?php if ($procnoticias->rowCount() > 0) { ?>
									<div class="col-12">
										<center>
											<h5 class="text-warning"><i data-feather='info'></i> Existe uma Notificacão Ativa</h5>
										</center>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>