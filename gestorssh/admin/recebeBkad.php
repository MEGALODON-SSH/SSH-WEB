<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Gerenciador de conexôes SSH">
    <meta name="keywords" content="EMPRESA, VPS, SSH, VPN">
    <meta name="author" content="nandotorres">
    <meta name="msapplication-TileColor" content="#1e88e5">
    <meta name="theme-color" content="#1e88e5">
    <title> BACKGROUNDS! </title>
</head>

<body>
<center></br>
<?php
echo unlink("../app-assets/images/background/painel-ad.png");
// verifica se foi enviado um arquivo 
if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0)
{

	echo "Você enviou o arquivo: <strong>" . $_FILES['arquivo']['name'] . "</strong><br />";
	echo "Este arquivo é do tipo: <strong>" . $_FILES['arquivo']['type'] . "</strong><br />";
	echo "Temporáriamente foi salvo em: <strong>" . $_FILES['arquivo']['tmp_name'] . "</strong><br />";
	echo "Seu tamanho é: <strong>" . $_FILES['arquivo']['size'] . "</strong> Bytes<br /><br />";

    header('Location: ../admin/home.php?page=perso');
	
	$arquivo_tmp = $_FILES['arquivo']['tmp_name'];
	$nome = $_FILES['arquivo']['name'];
	

	// Pega a extensao
	$extensao = strrchr($nome, '.');

	// Converte a extensao para mimusculo
	$extensao = strtolower($extensao);

	// Somente imagens, .jpg;.jpeg;.gif;.png
	// Aqui eu enfilero as extesões permitidas e separo por ';'
	// Isso server apenas para eu poder pesquisar dentro desta String
	if(strstr('.png;jpg', $extensao))
	{
		// Cria um nome único para esta imagem
		// Evita que duplique as imagens no servidor.
		echo unlink("../app-assets/images/background/painel-ad.png");
		$novoNome =  "painel-ad.png";
		
		// Concatena a pasta com o nome
		$destino = '../app-assets/images/background/' . $novoNome; 
		
		// tenta mover o arquivo para o destino
		if( @move_uploaded_file( $arquivo_tmp, $destino  ))
		{
			echo "Arquivo salvo com sucesso em : <strong>" . $destino . "</strong><br />";
			echo "<file src=\"" . $destino . "\" />";
		}
		else
			echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";
	}
	else
		echo "Você poderá enviar apenas arquivos \"*.jpg;*.jpeg;*.gif;*.png\"<br />";
}
else
{
	echo "<b>Você não enviou nenhum arquivo!</b>";
}
?>
</center></br>
<center><button></b><a href="../admin/home.php?page=perso" class="text-info m-l-5"><b>VOLTAR</b></button> </center>
</body>
</html>