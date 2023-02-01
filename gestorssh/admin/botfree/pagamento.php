<?php 
include 'TlgTools.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pagamento [Login SSH 30 Dias]</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<style>
*{
	padding: 0;
	margin: 0;
	font-family: sans-serif;
	box-sizing: border-box;
	font-weight: 200;

}

body{
	font-size: 0.9em;
	color: #3e423e;
}

header{
	height:  56px;
	width: 100%;
    float: left;
    text-align: center;
    background: #19d47d;
    padding:  7px;
}

header h1{
	text-transform: uppercase;
	font-weight: 700;
	color:  #eee;
}

.container{
	width: 100%;
	float: left;
	padding: 2% 3%;

}

.bloco{
	margin: 0 auto;
	text-transform: uppercase;
    width: 90%;
    background: #ccc;
    padding: 10px;
}

.bloco h1{
	text-align: center;
	font-size: 1em;
	font-weight: 600;
	margin-bottom: 50px;
	margin-top: 50px;
}
 img{
    text-align: center;
	width: 150px;
	height: 150px;
}

.bloco input{
	height:  46px;
	width: 200px;
	border:  1px solid #ccc;
	border-radius:  5px 0px 0px 5px;
}

.bloco button{
	height:  46px;
	width: 50px;
	border-radius: 0px 5px 5px 0px;
	background: #19d47d;
	color:  #eee;
	border:  none;
}

#timer{
	margin-bottom: 5px;
	font-weight: 600;
	font-size: 1.1em;
	text-transform: uppercase;
}


#snackbar {
  visibility: hidden; /* Hidden by default. Visible on click */
  min-width: 250px; /* Set a default minimum width */
  margin-left: -125px; /* Divide value of min-width by 2 */
  background-color: #333; /* Black background color */
  color: #fff; /* White text color */
  text-align: center; /* Centered text */
  border-radius: 2px; /* Rounded borders */
  padding: 16px; /* Padding */
  position: fixed; /* Sit on top of the screen */
  z-index: 1; /* Add a z-index if needed */
  left: 50%; /* Center the snackbar */
  bottom: 30px; /* 30px from the bottom */
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
#snackbar.show {
  visibility: visible; /* Show the snackbar */
  /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
  However, delay the fade out process for 2.5 seconds */
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
.info-text{
	text-transform: none;
	text-align: center;

}
</style>

<header>
<h1>PAGAMENTO</h1>
</header>

<div class="container">
	
	<div class="bloco">
		    <h1>INFORMAÇÕES</h1>
            <b>N° Pedido:</b> <?= $_SESSION['payment_id']?><br><br><hr><br>
			<b>Produto:</b> Login SSH 30 Dias<br><br><hr><br>
            <b>Valor:</b> R$<?= VALOR ?><br><br><hr><br>
		    <center>
		    	<div id="timer"></div>
		    

		  <div class="teste"></div>
		    <img class="qr_code" src="data:image/png;base64,<?= $_SESSION['qr_code_base64']?>">
		    <br>
		    <br>
		    <input type="text" id="foo" value="<?= $_SESSION['qr_code']?>"><button class="btn-copy"><i class="fa fa-copy" data-clipboard-target="#foo"></i></button>
     <div id="snackbar">Copiado com sucesso!</div>
		</center>
       <br><br>

		<p class="info-text">Atenção, não feche esta aba antes de fazer o pagamento!</p>
		<p class="info-text">Após efetuar o pagamento, o login será enviado automaticamente pelo bot.</p>
	</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	setTimeout(()=>{
	window.location.href = "https://t.me/botfreecoutysshBot<?= BOT_USER?>";

}, 602000);
	var timer2 = "10:01";
var interval = setInterval(function() {


  var timer = timer2.split(':');
  //by parsing integer, I avoid all extra string processing
  var minutes = parseInt(timer[0], 10);
  var seconds = parseInt(timer[1], 10);
  --seconds;
  minutes = (seconds < 0) ? --minutes : minutes;
  if (minutes < 0) clearInterval(interval);
  seconds = (seconds < 0) ? 59 : seconds;
  seconds = (seconds < 10) ? '0' + seconds : seconds;
  //minutes = (minutes < 10) ?  minutes : minutes;
  $('#timer').html(minutes + ':' + seconds);
  timer2 = minutes + ':' + seconds;
}, 1000);




</script>
<script type="text/javascript">

//Calling function
repeatAjax();


function repeatAjax(){
jQuery.ajax({
          type: "POST",
          url: 'verify.php',
          dataType: 'text',
          success: function(resp) {
          	if(resp == 'Aprovado')
          	{
              $(".qr_code").attr('src','https://www.pngplay.com/wp-content/uploads/2/Approved-PNG-Photos.png');
          	  window.location.replace("https://t.me/CoutySSH");

                    jQuery('.teste').html(resp);
                    }

          },
          complete: function() {
                setTimeout(repeatAjax,1000); //After completion of request, time to redo it after a second
             }
        });
}
</script>
<script type="text/javascript">
$(".btn-copy").click(()=>{
	 var copyText = document.getElementById("foo");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
 toastText()
});
	

function toastText() {
  // Get the snackbar DIV
  var x = document.getElementById("snackbar");

  // Add the "show" class to DIV
  x.className = "show";

  // After 3 seconds, remove the show class from DIV
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}
    </script>
</body>
</html>
