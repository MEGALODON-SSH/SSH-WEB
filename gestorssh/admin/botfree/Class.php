<?php 
class Geral{
    
	public function gerador1($qtd){
     $numero = "abcdefghijklmnopqrstuvxiadi";
       $mistura = str_shuffle($numero);

       $result = substr($mistura, 0, $qtd);
       return $result;
	}

	public function gerador2($qtd){
       $numero = "0987654321".rand(0000, 9999);
       $mistura = str_shuffle($numero);

       $result = substr($mistura, 0, $qtd);
       return $result;
	}

	public function install($valor){
		if($valor == 1)
		{
          $ssh = new Net_SSH2(SERVIDOR,'22');

      if($ssh->login('root', SENHA)){
      $ssh->exec('wget https://www.dropbox.com/s/m6ehbrft7kb9itk/1.sh && chmod +x 1.sh');
      return "Sucesso 1";
    //echo "Logado com sucesso!";
      }
      else{
     
      }
		}elseif($valor == 2)
		{
	    $ssh = new Net_SSH2(SERVIDOR,'22');
        if($ssh->login('root', SENHA)){
      $ssh->exec('wget https://www.dropbox.com/s/ske232656uo7r0h/2.sh && chmod +x 2.sh');
      return "Sucesso 2";
    //echo "Logado com sucesso!";
      }
      else{
     
      }
		}
	}
}
?>
