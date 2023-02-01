<?php  include 'TlgTools.php';
 require_once 'lib/vendor/autoload.php';

 MercadoPago\SDK::setAccessToken(MP_TOKEN);

 $payment = new MercadoPago\Payment();
 $payment->transaction_amount = VALOR;
 $payment->description = "LOGIN SSH 31 DIAS";
 $payment->payment_method_id = "pix";
 $payment->payer = array(
     "email" => "test@test.com",
     "first_name" => "Test",
     "last_name" => "User",
     "identification" => array(
         "type" => "CPF",
         "number" => "19119119100"
      ),
     "address"=>  array(
         "zip_code" => "06233200",
         "street_name" => "Av. das Nações Unidas",
         "street_number" => "3003",
         "neighborhood" => "Bonfim",
         "city" => "Osasco",
         "federal_unit" => "SP"
      )
   );

 $payment->save();
 //var_dump($payment);

$_SESSION['payment_id'] = $payment->id;
$_SESSION['qr_code_base64'] = $payment->point_of_interaction->transaction_data->qr_code_base64;
$_SESSION['qr_code'] = $payment->point_of_interaction->transaction_data->qr_code;
$_SESSION['usuario'] = $_GET['nome'];
$_SESSION['chat_id'] = $_GET['userid'];
echo "<script>window.location.replace('https://coutyssh.com.br/botfree/pagamento.php')</script>";

?>
