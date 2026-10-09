<?php 

$pathlocal = dirname(__FILE__);
require_once(dirname($pathlocal)."/funcoes.php");
function autoload($classe){
	$classe = str_replace('..', '', $classe);
	$pathlocal = dirname(__FILE__);


	require_once($pathlocal."/{$classe}.class.php");
	
	
}

spl_autoload_register('autoload');
?>