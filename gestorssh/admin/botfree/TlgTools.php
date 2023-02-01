<?php session_start(); include 'Class.php';
 $geral = new Geral();

 define("SITE_URL", "https://".$_SERVER['SERVER_NAME']);
 define("MP_TOKEN", "seu token do mercado pago aqui");  
 define("BOT_TOKEN", "coloca seu token aqui");  
 define("VALOR", "0.50"); 
 define("BOT_USER", " nome do not "); 
 define("SERVIDOR", "ip do sua vps");  
 define("IP", "ip da sua vps "); 
 define("SENHA", "senha do vps "); 
 define("TESTE_TEMPO", 15);  
 define("CANAL_ID", " id do seu canal"); 
 define("SUPORTE_USER", " seu user sem o @ ");  
 define("APK_NOME", "TesteBot.apk"); 
     
     set_include_path(get_include_path() . PATH_SEPARATOR . 'lib2');
     include ('Net/SSH2.php');

 function criar_usuario($user, $senha, $tempo, $limite)
     {
      $ssh = new Net_SSH2(SERVIDOR,'22');

      if($ssh->login('root', SENHA)){
      $ssh->exec('./2.sh '.$user.' '.$senha.' '.$tempo.' '.$limite.' ');
    //echo "Logado com sucesso!";
      }
      else{
     
      }
      }

      function criar_teste($user, $senha, $tempo, $limite)
     {
      $ssh = new Net_SSH2(SERVIDOR,'22');

      if($ssh->login('root', SENHA)){
      $ssh->exec('./1.sh '.$user.' '.$senha.' '.$tempo.' '.$limite.' ');
    //echo "Logado com sucesso!";
      }
      else{
     
      }
      }

      
?>
