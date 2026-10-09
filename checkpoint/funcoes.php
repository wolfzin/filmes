<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
inicializa();
protegeArquivo(basename(__FILE__));
function inicializa(){
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	
	if(file_exists(dirname(__FILE__).'/config.php')):
		require_once(dirname(__FILE__).'/config.php');
	else:
		die(utf8_decode("O arquivo de configuração não foi localizado, contate o administrador"));
	endif;
	$constantes = array('BASEPATH', 'BASEURL', 'ADMURL', 'CLASSESPATH', 'MODULOSPATH', 'CSSPATH', 'JSPATH', 'DBHOST', 'DBUSER', 'DBPASS', 'DBNAME');
	foreach ($constantes as $valor) {
		if(!defined($valor)):
			die(utf8_decode("Faltam configurações básicas do sistema, contate o administrador: ".$valor));
		endif;
	}
	
	require_once(BASEPATH.CLASSESPATH.'/autoload.php');
	if (isset($_GET['logoff'])==true):
		$user = new usuarios;
		$user->doLogout();
	endif;
	date_default_timezone_set('America/Sao_Paulo');
}
function verificaLogin($url = BASEURL){
	if (!isset($_COOKIE['username'])) {
		?>
		<script>alert('Seu login expirou, faça login novamente para continuar!');window.location.href = "<?= $url ?>";</script>
		<?php
	}else{
		$username = $_COOKIE['username'];
		setcookie('username', $username, time() + 3600);
	}
	?>
	<script type="text/javascript">
		var logado = sessionStorage.getItem('logado');
		if (logado != 'yes') {
			alert('Usuário não logado!');
			window.location.href = "<?= BASEURL ?>";
		}else{
			
		}
	</script>
	<?php 
}
function verificaLoginCliente($url = URL){
	if (!isset($_COOKIE['clientecpf'])) {
		?>
		<script>
			alert('Seu login expirou, faça login novamente para continuar!');window.location.href = "<?= $url ?>";
		</script>
		<?php
	}else{
		$alunoname = $_COOKIE['alunocpf'];
		setcookie('alunocpf', $alunocpf, time() + 3600);
	}
	?>
	<script type="text/javascript">
		var logado_cliente = sessionStorage.getItem('logado_cliente');
		if (logado_cliente != 'yes') {
			alert('Cliente não logado!');
			window.location.href = "<?= URL ?>/login";
		}else{
			
		}
	</script>
	<?php 
}
function loadCSS($arquivo=NULL, $media='screen', $import=FALSE){
	if($arquivo != NULL){
		if($import == TRUE){
			echo '<style type="text/css">@import url("'.BASEURL.CSSPATH.$arquivo.'.css");</style>';
		}else{
			echo '<link rel="stylesheet" type="text/css" href="'.BASEURL.CSSPATH.$arquivo.'.css" media="'.$media.'"/>';
		}
	}
}
function loadJS($arquivo=NULL, $remoto = FALSE){
	if ($arquivo != NULL){
		if($remoto == FALSE) $arquivo = BASEURL.JSPATH.$arquivo.'.js';
		echo '<script type="text/javascript" src="'.$arquivo.'"></script>';
	}
}
function loadmodulo($modulo=NULL, $tela=NULL){
	if ($modulo==NULL || $tela ==NULL){
		echo '<p>Erro na  função <strong>'.__FUNCTION__.'</strong>: Faltam parâmetros para execução.</p>';
	}else{
		if(file_exists(MODULOSPATH."$modulo.php")){
			include_once((MODULOSPATH."$modulo.php"));
		}else{
			echo '<p> Módulo inexistente neste sistema';
		}
	}
}
function protegeArquivo($nomeArquivo, $redirPara = 'index.php?erro=3'){
	$url = $_SERVER["PHP_SELF"];
	if(preg_match("/$nomeArquivo/i", $url)){
		redireciona($redirPara);
	}
}
function limparCampos($valor){
	$valor = trim($valor);
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace("-", "", $valor);
	$valor = str_replace("/", "", $valor);
	return $valor;
}
function redireciona($url=''){
	//header("Location: ".BASEURL.$url);
	$end=BASEURL.$url; 
	?>
	<script language= "JavaScript">
		//window.open("<?php echo $url; ?>");
		location.href="<?php echo $url; ?>"
	</script>
	<?php 
}


// criptografia de senhas
function hash_password($password) {
	$salt = random_bytes(16);
	$hash = hash_pbkdf2('sha512', $password, $salt, 10000, 64);
	return base64_encode($salt . $hash);
}


// verifica senha
function verify_password($password, $hashed_password) {
	$decoded = base64_decode($hashed_password);
	$salt = substr($decoded, 0, 16);
	$hash = substr($decoded, 16);
	$test_hash = hash_pbkdf2('sha512', $password, $salt, 10000, 64);
	return hash_equals($hash, $test_hash);
}

// remove sql inject
function secureInput($string){
	$string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	return $string;
}

// verifica login
function verificaDados($usuario, $senha){
	$user = new usuarios();
	$user->extras_select= "where login_usuario = '$usuario'";
	$user->selecionaTudo($user);

	if ($user->linhasafetadas == 1) {
		$usLogado = $user->retornaDados();
		$pass_hash = $usLogado->senha_usuario;

		if (verify_password($senha, $pass_hash)) {
			?>
			<script type="text/javascript">
				sessionStorage.setItem("logado", 'yes');
				sessionStorage.setItem("nome_usuario", '$usLogado->nome_usuario');
				sessionStorage.setItem("login_usuario", '$usuario');
				sessionStorage.setItem("email_usuario", '$usLogado->email_usuario');
			</script>
			<?php 
			loginCookie('username', $usLogado->nome_usuario);
			loginCookie('logado', 'yes');
			return true;
		}else{
			return false;
		}

	}else{
		echo 'Usuário não encontrado';
	}
}

// verifica login
function verificaDadosCliente($email, $senha){
	$cliente = new ps_clientes();
	$cliente->extras_select= "where email_cliente = '$email'";
	$cliente->selecionaTudo($cliente);

	if ($cliente->linhasafetadas > 0) {
		$usLogado = $cliente->retornaDados();
		$pass_hash = $usLogado->senha_cliente;

		if (verify_password($senha, $pass_hash)) { 
			loginCookie('clientename', $usLogado->nome_cliente);
			loginCookie('logadocliente', 'yes');
			loginCookie('clienteid', $usLogado->id_cliente);
			loginCookie('clienteemail', $usLogado->email_cliente);
			loginCookie('clientecpf', $usLogado->cpf_cliente);
			return true;
		}else{
			return false;
		}

	}else{
		echo 'Usuário não encontrado';
	}
}

function loginCookie($persona, $nome){
	$cookieName = "meuCookie";
	$cookieValue = "Olá, este é o meu valor de cookie!";
	$cookieExpiration = time() + (100000 * 1); 
	$cookiePath = "/";
	$cookieDomain = "";
	$cookieSecure = false;
	$cookieHttpOnly = false;
	return setcookie($persona, $nome, $cookieExpiration, $cookiePath, $cookieDomain, $cookieSecure, $cookieHttpOnly);
}

// RECUPERAR SENHA (EMAIL NÃO ESTÁ FUNCINANDO)
function recuperarSenha($email) {
	$user = new usuarios();
	$user->extras_select= "where email_usuario = '$email'";
	$user->selecionaTudo($user);
	$resUsuario = $user->retornaDados();

	if (!$resUsuario->email_usuario) {
		return false;
	}


	$token = substr(md5(time()), 0, 8);

	$user = new usuarios(array(
		"recuperar_senha_usuario" => $token
	));
	$user->valorpk= 1;
	$user->atualizar($user);

	


// 	$mail = new PHPMailer;
// 	$mail->isSMTP();
// 	$mail->SMTPDebug = 2;
// 	$mail->Host = 'smtp.gmail.com';
// 	$mail->Port = 587;
// 	$mail->SMTPAuth = true;
// 	$mail->Username = 'fernando.pufe@gmail.com';
// 	$mail->Password = 'FG@772844';
// 	$mail->setFrom('fernando.pufe@gmail.com', 'Fernando');
// 	//$mail->addReplyTo('test@hostinger-tutorials.com', 'Your Name');
// 	$mail->addAddress('efpufe@gmail.com', 'Receiver Name');
// 	$mail->Subject = 'Alterar Senha? - Náutica Mario Timm';
// 	$mail->msgHTML(file_get_contents('message.html'), __DIR__);
// 	$mail->Body = 'Olá, você solicitou uma nova senha de acesso, clique no link ou copie e cole no navegador, só será possível alterar a senha por esse link uma vez.'.'<br><br><a href="'.BASEURL.'"token=".$token.">"'.BASEURL.'"token="'.$token.'"</a>';
// //$mail->addAttachment('test.txt');
// 	if (!$mail->send()) {
// 		echo 'Mailer Error: ' . $mail->ErrorInfo;
// 	} else {
// 		echo 'The email message was sent.';
// 	}
}

// VALIDAÇÃO DE E-MAIL
function validarEmail($email) {

	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return $email; 
	}
	else {
		return false; 
	}
}

function converterData($data){
	$data_formatada = date('Y-m-d', strtotime($data));
	return $data_formatada;
}
function converterDataBr($data){
	$data_formatada = date('d-m-Y', strtotime($data));
	return $data_formatada;
}

function duracao($id){
	

	$apiKey = 'AIzaSyALETJJMeWykqxuqpDgwqENP2Yj5wxcKUs';
	$videoId = $id;
	$apiUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=$videoId&key=$apiKey";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($response);

	$videoTitle = $data->items[0]->snippet->title;
	$videoDescription = $data->items[0]->snippet->description;
	$videoThumbnail = $data->items[0]->snippet->thumbnails->high->url;
	$videoEmbedUrl = "https://www.youtube.com/embed/$videoId?controls=0&modestbranding=1&rel=0";
	return $videoDuration = $data->items[0]->contentDetails->duration;

}
function youtube($id){
	

	$apiKey = 'AIzaSyALETJJMeWykqxuqpDgwqENP2Yj5wxcKUs';
	$videoId = $id;
	$apiUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=$videoId&key=$apiKey";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($response);

	$videoTitle = $data->items[0]->snippet->title;
	$videoDescription = $data->items[0]->snippet->description;
	$videoThumbnail = $data->items[0]->snippet->thumbnails->high->url;
	return $videoEmbedUrl = "https://www.youtube.com/embed/$videoId?controls=0&modestbranding=1&rel=0";
	$videoDuration = $data->items[0]->contentDetails->duration;

}


//verifica se usuario esta logado
//sair do painel
function sairPainel(){
	$sessao = new sessao();
	$sessao->setVar('logado', false);
	$sessao->destroy(TRUE);
	redireciona('https://www.cofracarmo.com.br/');
	
}
//mostra mensagens
function printMSG($msg=null, $tipo=null){
	if ($msg!=null){
		switch ($tipo){
			case 'erro':
			echo '<div class="erro">'.$msg.'</div>';
			break;
			case 'alerta':
			echo '<div class="alerta">'.$msg.'</div>';
			break;
			case 'pergunta':
			echo '<div class="pergunta">'.$msg.'</div>';
			break;
			case 'sucesso':
			echo '<div class="sucesso">'.$msg.'</div>';
			break;
			default:
			echo '<div class="sucesso">'.$msg.'</div>';
			break;
		}
	}
}
//verifica se o usuario é admin
function isAdmin(){
	//verificaLogin();
	$sessao = new sessao();
	$user = new usuarios();
	$iduser = $sessao->getVar('tipo_usuarios_id');
	echo $user->extras_select = "WHERE tipo_usuarios_id = $iduser";
	$user->selecionaTudo($user);
	$res = $user->retornaDados();
	if ($res->tipo_usuarios_id == 1 ){
		return TRUE;
	}else{
		return FALSE;
	}
}
//funcao anti-inject
function antiInject($string){
	// remove palavras que contenham sintaxe sql
	$string = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i","",$string);
	$string = trim($string);//limpa espacos vazios
	$string = strip_tags($string);//tira tags html e php
	if(!get_magic_quotes_gpc())
	$string = addslashes($string);//Adiciona barras invertidas a uma string
return $string;
}
/*
//uploads de imagem com recorte para thumbnails
function Move($img, $w = "150", $h = "150"){
	if($img) {
		$imgArray = array();
		$numFile	= count(array_filter($img['name']));
		for($i = 0; $i < $numFile; $i++){
			$name 	= $img['name'][$i];
			$error	= $img['error'][$i];
			$tmp	= $img['tmp_name'][$i];
			$dir = 'images/'; 
			$dir2 = 'images/thumbs/'; 
			$par = date('YmdHis');
			$name= $par.$name;
			@$ext = end(explode(".", $name));
			$name = md5($name);
			$name = $name.'.'.$ext;
			move_uploaded_file($tmp, $dir . $name);
			$imgArray[] = $novoNome = $dir.$name;
			require_once(BASEPATH.'/wideimage/lib/WideImage.php');
			$image = WideImage::load($novoNome); 
			$image = $image->resize($w, $h); 
			$nome = strtolower(substr($novoNome,7));
			$image->saveToFile($dir2.$nome);
			echo $dir2.$nome;
		}
		return $imgArray;
	}
} 
*/
//uploads de imagem com recorte para thumbnails
function Anexos($img, $w, $h){
	if($img) {
		$imgArray = array();
		$numFile	= count(array_filter($img['name']));
		for($i = 0; $i < $numFile; $i++){
			$name 	= $img['name'][$i];
			$error	= $img['error'][$i];
			$tmp	= $img['tmp_name'][$i];
			$dir = '/home/papelariastatus/public_html/pdv/images/'; 
			$par = date('YmdHis');
			$name= $par.$name;
			@$ext = end(explode(".", $name));
			$name = md5($name);
			$name = $name.'.'.$ext;
			if(move_uploaded_file($tmp, $dir . $name)){
				$imgArray[] = $novoNome = $name;
				
			}
		}
		return $imgArray;
	}
	
}

//UPLOAD DE ARQUIVOS ZIP
function anexoMaterial($img){
	if($img) {
		$imgArray = array();
		$numFile	= count(array_filter($img['name']));
		for($i = 0; $i < $numFile; $i++){
			$name 	= $img['name'][$i];
			$error	= $img['error'][$i];
			$tmp	= $img['tmp_name'][$i];
			$dir = '/home/papelariastatus/public_html/pdv/images/material/'; 
			@$ext = end(explode(".", $name));
			$name = $name.'-'.date('YmdHis').'-'.rand(0,10000000);
			$name = $name.'.'.$ext;
			if(move_uploaded_file($tmp, $dir . $name)){
				$imgArray[] = $novoNome = $name;
			}
		}
		return $imgArray;
	}
}
//uploads de pdf
function MovePDF($img){
	if($img) {
		$imgArray = array();
		$numFile	= count(array_filter($img['name']));
		for($i = 0; $i < $numFile; $i++){
			$name 	= $img['name'][$i];
			$error	= $img['error'][$i];
			$tmp	= $img['tmp_name'][$i];
			$dir = '/home/mariotimm/public_html/~status/images/pdf/'; 
			@$ext = end(explode(".", $name));
			$name = $name.'-'.date('YmdHis').'-'.rand(0,10000000);
			$name = $name.'.'.$ext;
			if(move_uploaded_file($tmp, $dir . $name)){
				$imgArray[] = $novoNome = $name;
			}
		}
		return $imgArray;
	}
}
//enviar email quando uma imagem é upada em tarefas(nao usado nesse site)
function email($idTarefa){
	$id = $idTarefa;
	$t = new tarefas(array(
		"u.nome"=>null,
		"c.empresa"=>null,
		"a.nome as imagem"=>null,
		"tarefas.projetos_id as idprojeto"=>null,
	));
	$t->extras_select = "join usuarios u on u.id = tarefas.usuarios_id
	join projetos p on p.id = tarefas.projetos_id
	join clientes c on c.id = p.clientes_id
	join anexos a on a.tarefas_id = tarefas.id and tarefas.id = $id order by a.id desc";
	$t->selecionaCampos($t); 
	$rest = $t->retornaDados();
	
	$emaildestinatario  =  'fernando@agenciastatus.com.br, alan@agenciastatus.com.br, fernando-pufe@hotmail.com';
	$nomedestinatario       = 'Fernando, Alan'; 
	$emailremetente         = 'contato@agenciastatus.com.br';    
	$nomeremetente         = $rest->nome;    
	$assunto       = 'A tarefa da "'.$rest->empresa.'" foi finalizada!';   
	$assunto = '=?UTF-8?B?'.base64_encode($assunto).'?=';
	$mensagemHTML =  "
	<center>
	<img style='width:100%; max-width:600px; ' src='". BASEURL."/".$rest->imagem."'> 
	<br>
	<a href='".BASEURL."/painel.php?m=tarefas&t=listar&id=".$rest->idprojeto."'>Ver projeto</a>
	</center>
	";
	$headers = "MIME-Version: 1.1\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: $nomeremetente <$emailremetente>\r\n";
	$headers .= "Return-Path:   <$emailremetente> \r\n";
	$envio = mail($emaildestinatario, $assunto, $mensagemHTML, $headers); 
} 
		//limitar texto 
function limitarTexto($texto, $limite){
	$contador = strlen($texto);
	if ( $contador >= $limite ) {      
		$texto = substr(strip_tags($texto), 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
		return $texto;
	}
	else{
		return $texto;
	}
} 
		//recuperar categorias salvas para edição m=posts
function selecionaCategoria($id){
	$cp = new ps_produtos_has_ps_categorias();
	$cp->extras_select="where produto_id = ".$_GET['id'];
	$cp->selecionaTudo($cp);
	$check = "";
	while($resCP = $cp->retornaDados()){
		$categorias  = new categorias();
		$categorias->extras_select = "where id = $resCP->categoria_id ";
		$categorias->selecionaTudo($categorias);
		$resCategorias = $categorias->retornaDados();
		if($resCategorias->id_categoria == $id){
			$check = "checked";
			return $check;
			break;
		}
	}          
} 

	
function selecionaLinks($id){

	$cp = new servicos_has_links();
	$cp->extras_select="where servicos_id = ".$_GET['id']." and status = 1";
	$cp->selecionaTudo($cp);
	$check = "";
	while($resSl = $cp->retornaDados()){

		$categorias  = new links();
		$categorias->extras_select = "where id = $resSl->links_id";
		$categorias->selecionaTudo($categorias);
		$resCategorias = $categorias->retornaDados();
		if($resCategorias->id == $id){
			$check = "checked";
			return $check;
			break;
		}
	}          
} 
		//recuperar categorias salvas para edição m=posts
function selecionaLinks2($id){

	$cp = new servicos_has_links();
	$cp->extras_select="where servicos_id = ".$_GET['id']." and status = 2";
	$cp->selecionaTudo($cp);
	$check = "";
	while($resSl = $cp->retornaDados()){

		$categorias  = new links();
		$categorias->extras_select = "where id = $resSl->links_id";
		$categorias->selecionaTudo($categorias);
		$resCategorias = $categorias->retornaDados();
		if($resCategorias->id == $id){
			$check = "checked";
			return $check;
			break;
		}
	}          
} 
function Show($tabela, $categoria = null, $ordem = 'asc', $limite = null, $offset = 0){
	if (($tabela == 'post') or ($tabela == 'posts')) {
		$show = new posts();
	}else if($tabela == 'categoria' or $tabela == 'categorias'){
		$show = new categorias();
	}else if($tabela == 'pagina' or $tabela == 'paginas'){
		$show = new paginas();
	}
	$show->selecionaTudo($show);
	return $obj = $show->retornaDados();

}  
setlocale(LC_ALL, 'en_US.UTF8');
function UrlA($str, $replace=array(), $delimiter='-') {
	if(!empty($replace)) {
		$str = str_replace((array)$replace, ' ', $str);
	}
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	return $clean;
}
function slug($tabela=null, $slug=null, $id = null){
	$tab = new $tabela(array(
		"COUNT($id) as num" => null, 
		"$id as id" => null
	));
	$tab->extras_select = "where 'slug_$tabela' LIKE '%$slug%'";
	$tab->selecionaCampos($tab);
	$res = $tab->retornaDados();
	if ($res->num > 0 and $id == $res->id) {
		return $slug;
	}else if($res->num > 0){
		$slug = $slug.'-'.$res->num;
		return $slug;
	}else{
		return $slug;
	}
}

function criarUrlAmigavel($string) {
    // Converter para minúsculas
    $string = strtolower($string);

    // Remover caracteres especiais e espaços em branco
    $string = preg_replace('/[^a-z0-9\-]/', ' ', $string);

    // Substituir espaços em branco por hífens
    $string = preg_replace('/\s+/', '-', $string);

    // Remover hífens duplicados
    $string = preg_replace('/-+/', '-', $string);

    // Remover hífens no início e no final da string
    $string = trim($string, '-');

    // Remover acentos
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

    // Retornar a URL amigável
    return $string;
}
function notificarCliente($cliente, $pedido, $conteudo){
	$destino = $cliente;
	$assunto = 'BabyLune - Pedido: '.$pedido;
	$conteudo = "<img src='https://babylune.com.br/~status/images/header_email.jpg'><br><BR>".$conteudo;
	$conteudo .= "<br><br><img src='https://babylune.com.br/~status/images/footer_email.jpg'><BR><BR>Este e-mail foi enviado em <b>".date('d/m/Y h:i')."</b>";
	$header = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=utf-8\r\n";
	$header .= "From: BabyLune <babylune@hotmail.com>\r\n";
	$header .= "X-Mailer: PHP/" . phpversion();
	$enviaremail = mail($destino, $assunto, $conteudo, $header);
}
function notificar(){
	echo "notificar";
}
function mostrarArquivo($pasta){
	$arquivos = array();
	$path = "../".$pasta;
	$diretorio = dir($path);
	while($arquivo = $diretorio -> read()){
		$arquivos[] = $arquivo;
	}
	$diretorio -> close();
}

function consultarCidadePorCEP($cep) {
    // URL da API dos Correios para consulta de CEP
    $url = "https://viacep.com.br/ws/{$cep}/json/";

    // Inicializa a sessão cURL
    $curl = curl_init();

    // Configura as opções da requisição cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    // Executa a requisição e obtém a resposta
    $response = curl_exec($curl);

    // Verifica se houve algum erro na requisição
    if (curl_errno($curl)) {
        echo 'Erro ao consultar o CEP: ' . curl_error($curl);
        return null;
    }

    // Fecha a sessão cURL
    curl_close($curl);

    // Decodifica a resposta JSON
    $data = json_decode($response, true);

    // Verifica se o CEP foi encontrado
    if (isset($data['erro'])) {
        echo 'CEP não encontrado.';
        return null;
    }

    // Retorna a cidade associada ao CEP
    return $data;
}

ob_clean();