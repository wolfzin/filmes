<?php 
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));

class anexos extends base{
	public function __construct($campos=array()){
		parent::__construct();
		$this->tabela = "anexos";
		if (sizeof($campos)<=0) {
			$this->campos_valores = array(
				"id_anexo" => null,
				"nome_anexo" => null,
				"nome_original_anexo" => null,
				"created_anexo" => null,
				"tipo_anexo" => null,
				"link_anexo" => null,
				
				);
		}else{
			$this->campos_valores = $campos;
		}
		$this->campopk = "id_anexo";
	}//construct


	public function existeRegistro($campo=null, $valor=null){
		if ($campo!=NULL && $valor!=NULL):
			is_numeric($valor) ? $valor = $valor : $valor = "'".$valor."'";
		$this->extras_select = "WHERE $campo=$valor";
		$this->selecionaTudo($this);
		if($this->linhasafetadas >0):
			return TRUE;
		else:
			return FALSE;
		endif;
		else:
			$this->trataerro(__FILE__,__FUNCTION__,NULL,'Faltam parâmetros para executar a função', TRUE);
		endif;
	}

}//class cliente	
?>