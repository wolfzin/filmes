<?php





require_once("funcoes.php"); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Painel Administrativo</title>
	<?php 
	loadCSS('reset');
	loadCSS('style');
	loadJS('jquery');
	loadJS('geral');
	?>
</head>
<body>
	<?php 
	loadmodulo('usuarios', 'login');
	?>

</body>
</html>



