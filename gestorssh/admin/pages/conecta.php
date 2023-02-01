<?php
if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
  header('Location: index.php?e=404');exit;
}
?>
<div class="active"><a class="d-flex align-items-center" href="home.php"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a>
</div>
<iframe id="iFrameVideos" src="/conecta4g/index.php" height="700px" width="100%" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes" allowfullscreen="allowfullscreen" > Por favor, use um navegador que suporte iframes!</iframe>
<script>iFrameResize({log: false, inPageLinks: true}, '#iFrameVideos')</script>

</div>