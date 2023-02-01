<?php include 'TlgTools.php';   
if(isset($_SESSION['payment_id']))
{       
$url = "https://api.mercadopago.com/v1/payments/".$_SESSION['payment_id'];
$token = MP_TOKEN;
$header = array('Authorization: Bearer '.$token,
'Content-Type: application/json');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$result = curl_exec($ch);

curl_close($ch);

$status = json_decode($result);


if($status->status == "approved")
{


 $user = "user-".$geral->gerador1(3).$geral->gerador2(2);
 $senha = rand(11111, 99999);
 $tempo = 32;
 $limite = 1;
 criar_usuario($user, $senha, $tempo, $limite);
  $msg = "<b>✅ COMPRA FEITA COM SUCESSO! ✅</b>


<b>USER    👤:</b> ".$user."

<b>SENHA  🔑:</b> ".$senha."

<b>LIMITER💻:</b> ".$limite."

<b>🕒:</b> 31 Dias

 Digite /app para baixar o apk
 PlayStore: https://coutyssh.com.br

        ";

         $data = [
            'text' => $msg,
            'chat_id' => $_SESSION['chat_id'],
            'parse_mode'=>'html'
                   ];

           file_get_contents("https://api.telegram.org/bot".BOT_TOKEN."/sendMessage?" . http_build_query($data));
 $mensagem = "<b>".$_SESSION['usuario']." Acaba de comprar um login!</b>
ID: ".rand(0000000000, 9999999999)."
Valor: R$".VALOR."

@".BOT_USER;
$data1 = [
            'text' => $mensagem,
            'chat_id' => CANAL_ID,
            'parse_mode'=>'html'
                   ];
           file_get_contents("https://api.telegram.org/bot".BOT_TOKEN."/sendMessage?".http_build_query($data1));

            echo "Aprovado";
            session_destroy();
            Header("Location: https://t.me/botfreecoutysshBot".BOT_USER);

}else{
    echo "O pagamento ainda não foi efetuado! ".$_SESSION['payment_id'];

}
}
         ?>
