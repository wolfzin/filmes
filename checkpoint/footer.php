 <?php 

 loadJS('carrinho');
 loadJS('geral');

 ?>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<?php   loadJS('materializeSelect'); ?>
<script type="text/javascript">


	$("button").click( function(e){
		e.preventDefault()
	});
	$(document).ready(function(){
		$('.modal').modal();
		//$('select').formSelect();
		$('.sidenav').sidenav();
	});
	$( "#formUser" ).validate({
		rules: {
			password: "required",
			password_again: {
				equalTo: "#password"
			}
		}
	});
	atualizarBarraDeProgresso();
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script>
	$(document).ready(function () {

		/* MASCARA PREÇO DO PAINEL */ 

		var $campoPreco = $("#preco");
		$campoPreco.mask('00.000.00', {reverse: true});
		var $campoPreco = $("#valor-pago");
		$campoPreco.mask('00.000.00', {reverse: true});
// 		var $campoValorpago = $("#valor-pago");
// 		$campoValorpago.mask('00.000.00', {reverse: true});
// 		var $campoDesconto = $("#desconto");
// 		$campoDesconto.mask('00.000.00', {reverse: true});
		// var $campoPreco = $("#valor-pago");
		// $campoPreco.mask('000.00', {reverse: true});

	});
	
	
</script>

 

<?php 
$marcas  = new ps_marcas();
$marcas->selecionaTudo($marcas);
echo "<script>	
  $(document).ready(function(){
    $('input.autocomplete').autocomplete({
      data: {";
while ($res = $marcas->retornaDados()) {


	echo "'$res->id_marca $res->nome_marca'".":null,";


} 
echo "	},
    });
  });
       </script> ";
?>
