<?php

    if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>
<script type="text/javascript" src="../../plugins/datatables/sort-table.js"></script>
<style type="text/css">

  table { 
    width: 100%; 
    border-collapse: collapse; 
  }
  /* Zebra striping */
  tr:nth-of-type(odd) { 
    background: #f3f4f8; 
  }
  th { 
    background: white; 
    color: black; 
    font-weight: bold; 
  }
  td, th { 
    padding: 6px; 
    border: 1px solid #d7dfe2; 
    text-align: left; 
  }

</style>

<script>
  $(document).ready(function(){
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
<script type="text/javascript">
function deletatudo(){
    window.location.href='pages/download/excluir_todos.php?id=1';
}
</script>
<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<!-- Input with Icons start -->
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hospedar Arquivos</h4>
					<a class="text-bold-800 grey darken-2" href="../appss" target="_self">CLICK AQUI PARA IR PARA A LOJA DE ARQUIVOS</a></span>
                </div>
                <div class="card-body">
                    <form action="pages/download/enviandoarquivo.php" enctype="multipart/form-data" method="POST" role="form">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                <div class="mb-1">
                                    <div class="mb-0">
                                        Nome do Arquivo
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <input type="text" name="nome" id="nome" class="form-control" minlength="4"  placeholder="Digite o Nome do Arquivo..." required>
                                    </fieldset>
                                </div>
                                </div>
                                <input type="hidden" class="form-control" name="operadora" value="1">
                                <div class="col-sm-6 col-12">
                                <div class="mb-1">
                                    <div class="mb-0">
                                        Status
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-select" name="status">
                                            <option value='1' selected=selected>Funcionando</option>
                                            <option value='2'>Em Testes</option>
                                        </select>
                                    </fieldset>
                                </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                <div class="mb-1">
                                    <div class="mb-0">
                                        Tipo do arquivo
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-select" name="tipo">
                                            <option value='1' selected=selected>Ehi</option>
                                            <option value='2'>Apk</option>
                                            <option value='3'>Outros</option>
                                        </select>
                                    </fieldset>
                                </div>
                                </div>
                                <input type="hidden" class="form-control" name="tipocliente" value="1">
                                <div class="col-sm-6 col-12">
                                <div class="mb-1">
                                    <div class="mb-0">
                                        Descrição
                                    </div>
                                    <fieldset class="form-group position-relative">
                                        <input type="text" name="msg" id="msg" class="form-control" placeholder="Digite uma descrição..." required></div>
                                </fieldset>
                                </div>
                            </div>
                            
                            <div class="col-12 text-center">
                            <div class="mb-0">
                                <div class="mb-0">
                                    Arquivo
                                </div>
                                <fieldset class="form-group position-relative input-divider-right">
                                    <input type="file" class="form-control" name="arquivo">
                                    <div class="form-control-position">
                                        <i class="feather icon-file"></i>
                                    </div>
                                </fieldset>
                            </div>
                            </div>
                            <div class="col-sm-12 col-12 text-center">
                                <button type="submit" name="enviandoarquivos" class="btn btn-success"><i data-feather='upload'></i> Enviar</button>
                                <button type="reset" class="btn btn-danger"><i data-feather='rotate-ccw'></i> Limpar</button>
                                <button type="submit" class="btn btn-warning" onclick="deletatudo();"><i data-feather='trash'></i> Apagar Todos</button>
                            </div>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .card-datatable {
        padding-left: 5px;
        padding-right: 5px;
    }
</style>
<section id="complex-header-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Arquivos Hospedados</h4>
                </div>
                <div class="card-datatable">
                    <table id="example" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>TIPO</th>
                            <th>DATA POSTADO</th>
                            <th>NOME</th>
                            <th>DETALHES</th>
                            <th>APAGAR</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $SQLSubSSH = "SELECT * FROM arquivo_download ORDER BY id desc";
                        $SQLSubSSH = $conn->prepare($SQLSubSSH);
                        $SQLSubSSH->execute();
                        if(($SQLSubSSH->rowCount()) > 0){
                            while($row = $SQLSubSSH->fetch()){
                                $dataatual=$row['data'];
                                $dataconv = substr($dataatual, 0, 10);
                                $partes = explode("-", $dataconv);
                                $ano = $partes[0];
                                $mes = $partes[1];
                                $dia = $partes[2];
                                ?>
                                <tr>
                                    <td><?php echo $row['id'];?></td>
                                    <td><?php echo ucfirst($row['tipo']);?></td>
                                    <td><?php echo $dia;?>/<?php echo $mes;?> - <?php echo $ano;?></td>
                                    <td><?php echo $row['nome_arquivo'];?></td>
                                    <td><?php echo $row['detalhes'];?></td>
                                    <td><a href="pages/download/excluir.php?id=<?php echo $row['id'];?>" class="btn btn-danger btn-sm"><i data-feather='trash'></i></a></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                       </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>