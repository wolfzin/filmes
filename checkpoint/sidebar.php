<?php  require_once("funcoes.php"); 
protegeArquivo(basename(__FILE__));
?>
<div id="sidebar">
	<ul id="">
		
		<li class="bg1_hover"><a href="<?php echo BASEURL; ?>painel.php " <?php if(!isset($_GET['m'])) { echo 'class="bg1"';}?>><i class="fas fa-home" aria-hidden="true" title="Home"></i></a></li>

		<li class="bg1_hover">
			<a href="javascript:;" <?php if($_GET['m']=='posts' || $_GET['m']=='categorias') { echo 'class="bg1"';} ?> title="Posts"><i class="far fa-newspaper"></i>Posts</a>
			<ul class="submenu">
				<li class="bg1"><a href="?m=posts&t=listar ">Todos os Posts</a></li>
				<li class="bg1"><a href="?m=posts&t=incluir ">Adicionar Novo</a></li>
				<li class="bg1"><a href="?m=categorias&t=listar ">Categorias</a></li>
			</ul>
		</li>


		<li class="bg1_hover">
			<a href="javascript:;" <?php if($_GET['m']=='imoveis' || $_GET['m']=='categorias') { echo 'class="bg1"';} ?> title="imoveis"><i class="far fa-newspaper"></i>Imóveis</a>
			<ul class="submenu">
				<li class="bg1"><a href="?m=imoveis&t=listar ">Todos os Imóveis</a></li>
				<li class="bg1"><a href="?m=imoveis&t=incluir ">Adicionar Novo</a></li>
				<li class="bg1"><a href="?m=categorias&t=listar ">Categorias</a></li>
			</ul>
		</li>


		<li class="bg1_hover">
			<a href="javascript:;" <?php if($_GET['m']=='area-atuacao') { echo 'class="bg1"';} ?> title="Serviços"><i class="far fa-newspaper"></i>Serviços</a>
			<ul class="submenu">
				<li class="bg1"><a href="?m=area-atuacao&t=listar ">Todos os Serviços</a></li>
				<li class="bg1"><a href="?m=area-atuacao&t=incluir ">Adicionar Novo</a></li>
			</ul>
		</li>
		
		
		<li class="bg1_hover">
			<a href="javascript:;" <?php if($_GET['m']=='paginas') { echo 'class="bg1"';} ?> title="Páginas"><i class="fas fa-newspaper"></i>Páginas</a>
			<ul class="submenu">
				<li class="bg1"><a href="?m=paginas&t=listar ">Todos as Páginas</a></li>
				<li class="bg1"><a href="?m=paginas&t=incluir ">Adicionar Novo</a></li>
			</ul>
		</li>
		<li class="bg1_hover">
			<a href="?m=slide&t=listar" <?php if($_GET['m']=='slide') { echo 'class="bg1"';} ?> title="Slides"><i class="fas fa-layer-group"></i>Slides</a>
			<ul class="submenu">
				<li class="bg1"><a href="?m=slide&t=listar ">Todos os Slides</a></li>
				<li class="bg1"><a href="?m=slide&t=incluir ">Adicionar Novo</a></li>
			</ul>
		</li>
	
		<!--
		<li class="bg1_hover">
			<a href="?m=cantonfair&t=visualizar" <?php if($_GET['m']=='cantonfair') { echo 'class="bg1"';} ?> title="Páginas"><i class="fas fa-store"></i>CantonFair</a>
			
		</li>-->

		<li class="bg1_hover">
			<a href="?m=fale_conosco&t=listar" <?php if($_GET['m']=='fale_conosco') { echo 'class="bg1"';} ?> title="Fale Conosco"><i class="fas fa-file-contract"></i>Fale Conosco</a>
		</li>

		<li class="bg1_hover"><a href="?m=config&t=listar" <?php if($_GET['m']=='config') { echo 'class="bg1"';} ?> title="Configuração"><i class="fa fa-cog" aria-hidden="true"></i>Config</a>
		</li>
	</ul>
</div>