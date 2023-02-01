<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html class="loading bordered-layout" lang="pt" data-layout="bordered-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/ui-feather.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

</head>

<body class="vertical-layout vertical-menu-modern">
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../../../app-assets/js/scripts/ui/ui-feather.js"></script>
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
        function _0x3e89(){var _0x10914b=['copy','623SxRAzY','21860SbXFDJ','1575235BrHYIR','log','innerHTML','execCommand','2838FsacAd','remove','1131256CaYzsE','627psSrtU','248657ZHpriu','<textarea>','body','replace','4JBEANW','append','select','2566017UxqnMR','16758yBiGnL','3970kNKFky'];_0x3e89=function(){return _0x10914b;};return _0x3e89();}function _0x4b62(_0x51d26e,_0x4abb1d){var _0x3e893b=_0x3e89();return _0x4b62=function(_0x4b62e6,_0x337b34){_0x4b62e6=_0x4b62e6-0x154;var _0x13f79f=_0x3e893b[_0x4b62e6];return _0x13f79f;},_0x4b62(_0x51d26e,_0x4abb1d);}(function(_0x3c269c,_0x57aeff){var _0x31a21e=_0x4b62,_0x2de3bb=_0x3c269c();while(!![]){try{var _0x3e8336=parseInt(_0x31a21e(0x164))/0x1+parseInt(_0x31a21e(0x158))/0x2*(parseInt(_0x31a21e(0x163))/0x3)+-parseInt(_0x31a21e(0x168))/0x4*(-parseInt(_0x31a21e(0x15c))/0x5)+parseInt(_0x31a21e(0x157))/0x6*(parseInt(_0x31a21e(0x15a))/0x7)+-parseInt(_0x31a21e(0x162))/0x8+-parseInt(_0x31a21e(0x156))/0x9+-parseInt(_0x31a21e(0x15b))/0xa*(parseInt(_0x31a21e(0x160))/0xb);if(_0x3e8336===_0x57aeff)break;else _0x2de3bb['push'](_0x2de3bb['shift']());}catch(_0x35a1d4){_0x2de3bb['push'](_0x2de3bb['shift']());}}}(_0x3e89,0x39c5e));function copy(_0x259fb7='er'){var _0x4c162f=_0x4b62,_0x366e04=$(_0x4c162f(0x165));$(_0x4c162f(0x166))[_0x4c162f(0x154)](_0x366e04);var _0x3cfaf3='';$(_0x259fb7)['each'](function(_0x7a2553,_0x815392){var _0x2821f1=_0x4c162f;_0x7a2553>0x0&&(_0x3cfaf3=_0x3cfaf3+'\x0a'),_0x3cfaf3=_0x3cfaf3+_0x815392[_0x2821f1(0x15e)];}),console[_0x4c162f(0x15d)](_0x3cfaf3);var _0x3baa2e=_0x3cfaf3;_0x366e04['val'](_0x3cfaf3[_0x4c162f(0x167)](/&amp;/g,'&'))[_0x4c162f(0x155)](),document[_0x4c162f(0x15f)](_0x4c162f(0x159)),_0x366e04[_0x4c162f(0x161)]();};
    </script>
    <?php


    function myalertuser($status, $msgalert, $dirt)
    {
        $myalert = "
    let timerInterval
    Swal.fire({
    icon: '" . $status . "',
    title: '" . $msgalert . "',
    timer: 2000,
    timerProgressBar: true,
    willClose: () => {
        clearInterval(timerInterval)
    }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            window.location='" . $dirt . "';
        } else {
            window.location='" . $dirt . "';
        }
    })
    ";
        $alert = '<script type="text/javascript">' . $myalert . '</script>';
        return $alert;
    };


    function info_alert($_serv, $_user, $_pass, $_lim, $_days, $_dir)
    {
        $my_alert = "
    Swal.fire({
        icon: 'success',
        title: 'Criado com sucesso!',
        html: '<er>🌍 Servidor: " . $_serv . "</er> <br><br><er>👤 Usuário: " . $_user . "</er> <br><er>🔑 Senha: " . $_pass . "</er> <br><er>📲 Limite: " . $_lim . "</er> <br><er>🗓️ Expira em: " . $_days . "</er>',
        showDenyButton: true,
        confirmButtonText: 'Copiar',
        confirmButtonColor: '#8080ff',
        denyButtonText: 'Ok',
        denyButtonColor: '#33cc00', 
        }).then((result) => {
          if (result.isConfirmed) {
            copy();
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Copiado!',
              showConfirmButton: false,
              timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location='" . $_dir . "';
                } else {
                    window.location='" . $_dir . "';
                }
            })
          } else {
            window.location='" . $_dir . "';
          }
        })
    ";
        $alert2 = '<script>' . $my_alert . '</script>';
        return $alert2;
    };


    function info_alertv2($_serv, $_user, $_pass, $_lim, $_days, $link, $_dir)
    {
		echo "<er hidden>$link</er><br>";
        $my_alert = "
    Swal.fire({
        icon: 'success',
        title: 'Criado com sucesso !',
        html: '<er>🔰☝️ Copia e cola o link acima em seu app ☝️🔰</er> <br><er>📲 Limite: " . $_lim . "</er> <br><er>🗓️ Expira em: " . $_days . "</er>',
        showDenyButton: true,
        confirmButtonText: 'Copiar',
        confirmButtonColor: '#8080ff',
        denyButtonText: 'Ok',
        denyButtonColor: '#33cc00', 
        }).then((result) => {
          if (result.isConfirmed) {
            copy();
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Copiado!',
              showConfirmButton: false,
              timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location='" . $_dir . "';
                } else {
                    window.location='" . $_dir . "';
                }
            })
          } else {
            window.location='" . $_dir . "';
          }
        })
    ";
        $alert2 = '<script>' . $my_alert . '</script>';
        return $alert2;
    };
	
	function info_alert_user($_site, $_user, $_pass, $_dir)
    {
        $my_alert = "
    Swal.fire({
        icon: 'success',
        title: 'Criado com sucesso!',
        html: '<er>🌐 Site: http://" . $_site . "</er> <br><br><er>👤 Usuário: " . $_user . "</er> <br><er>🔑 Senha: " . $_pass . "</er>',
        showDenyButton: true,
        confirmButtonText: 'Copiar',
        confirmButtonColor: '#8080ff',
        denyButtonText: 'Ok',
        denyButtonColor: '#33cc00', 
        }).then((result) => {
          if (result.isConfirmed) {
            copy();
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Copiado!',
              showConfirmButton: false,
              timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location='" . $_dir . "';
                } else {
                    window.location='" . $_dir . "';
                }
            })
          } else {
            window.location='" . $_dir . "';
          }
        })
    ";
        $alert2 = '<script>' . $my_alert . '</script>';
        return $alert2;
    };



    ?>
</body>

</html>