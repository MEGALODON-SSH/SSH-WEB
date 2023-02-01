<?php 

    include 'TlgTools.php';
    include "Telegram.php";
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    error_reporting(E_ALL);
    
    
    $token = BOT_TOKEN;
    $hook = SITE_URL."/botfree/bot.php";
    
    $bot = new Telegram($token);
    // Setando o Webhook
    $bot->setWebhook($hook);
    
    // variaveis
    $chat_id = $bot->ChatID();
    $texto = $bot->Text();
    
    if($texto == "/start")
    {   

    	 $nome = $bot->FirstName()." ".$bot->LastName();
         $nomeurl = $bot->FirstName()."%20".$bot->LastName();
         $mensagem = ["chat_id" => $chat_id, "text" => $bemvindo];
    
        $bot->sendMessage($mensagem);
       
        $apresentacao = "<b>🙋‍♂️ OLÁ ".$nome." SEJA BEM VINDO</b>\n\nCompre seu login ou gere seu teste agora mesmo!";
    
        $option = [
        
        array($bot->buildInlineKeyBoardButton("🇧🇷 CRIAR TESTE 🇧🇷", null, "/teste")),
        array($bot->buildInlineKeyBoardButton("🇧🇷 COMPRAR SSH 🇧🇷", SITE_URL."/botfree/processando.php?nome=".$nomeurl."&userid=".$chat_id)),
        array($bot->buildInlineKeyBoardButton("📲 BAIXAR APP 📲", null, "/app")),
		array($bot->buildInlineKeyBoardButton("📲 PlaStore APP 📲", null, "https://coutyssh.com.br")),
        array($bot->buildInlineKeyBoardButton("👨‍💻 SUPORTE 👨‍💻", "https://t.me/Couty_SSH"))
    
        ];
    
        $keyb = $bot->buildInlineKeyBoard($option);
        $content = ["chat_id" => $chat_id, "reply_markup" => $keyb, "parse_mode" => "html", "text" => $apresentacao];
        $bot->sendMessage($content);
        
    
    }
    elseif($texto == "/teste")
    {
    $user = "teste-".$geral->gerador2(4); 
    $senha = rand(11111, 99999);
    $limite = 1;  
    criar_teste($user, $senha, $tempo, $limite);
   
   $retorno = "✅ TESTE CRIADO COM SUCESSO! ✅

🌐: ".TESTEBOT."

USER    👤: ".$user."

SENHA   🔑: ".$senha."

LIMITER 💻: 1

🕒: ".TESTE_TEMPO." Minutos

";
    $mensagem = ["chat_id" => $chat_id, "text" => $retorno];

    
         $bot->sendMessage($mensagem);

        
    
    }
    elseif($texto == "/app")
    {   
    $doc = "apk/".APK_NOME;
$document = new CURLFile(realpath($doc));
$content = array('chat_id' => $chat_id, 'document' => $document);
$bot->sendDocument($content);
    
    
    }
    

?>
