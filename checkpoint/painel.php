<?php
include('header.php');
if (isset($_GET['m'])) $modulo = $_GET['m'];
if (isset($_GET['t'])) $tela = $_GET['t'];
if (isset($_GET['id'])) $id = $_GET['id'];
?>
<div class="row">
	<div class="col <?= ($modulo == 'pdv')? 's1' : 's2'; ?>">
		<a href="#" data-target="menu-mobile" class="sidenav-trigger button-collapse right-float icon-menu  <?= ($modulo == 'pdv')? 'hide-on-med-and-down' : 'hide-on-large-only'; ?>">
			<i class="fas fa-bars"></i>
		</a>
		<ul class="sidenav section " id="menu-mobile">
			<li>
				<a href="<?= URL ?>painel.php?m=pdv&t=novo">
					<i class="fas fa-home"></i><b>PDV</b>
				</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == '') ? 'active': ''; ?>" >Início</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=tarefas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'tarefas') ? 'active': '' ; ?>">Tarefas</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=contas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'contas') ? 'active': '' ; ?>">Contas a pagar</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=saidas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'saidas') ? 'active': '' ; ?>">Saídas</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=clientes&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'clientes') ? 'active': '' ; ?>">Clientes</a>
			</li>

			<li>
				<a href="<?= BASEURL ?>painel.php?m=compra&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'compra') ? 'active': '' ; ?>">Compra</a>
			</li>	
			<li>
				<a href="<?= BASEURL ?>painel.php?m=produtos&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'produtos') ? 'active': '' ; ?>">Produtos</a>
			</li>		
			<li>
				<a href="<?= BASEURL ?>painel.php?m=usuarios&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'usuarios') ? 'active': ''; ?>">Usuários</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=relatorios&t=relatorios" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'relatorios') ? 'active': ''; ?>">Relatorios</a>
			</li>
			
		</ul>
		<ul class="table-of-contents  <?= ($modulo == 'pdv')? 'hide-on-large-only' : 'hide-on-med-and-down'; ?>">
			<li class="center-align">
				<a href="<?= URL ?>painel.php?m=pdv&t=novo">
					<b>Papelaria Status</b>
				</a>
			</li>
			<li><a href="<?= BASEURL ?>painel.php" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == '') ? 'active': ''; ?>" >Início</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=tarefas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'tarefas') ? 'active': '' ; ?>">Tarefas</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=contas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'contas') ? 'active': '' ; ?>">Contas a pagar</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=saidas&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'saidas') ? 'active': '' ; ?>">Saídas</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=clientes&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'clientes') ? 'active': '' ; ?>">Clientes</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=compra&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'compra') ? 'active': '' ; ?>">Compra</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=produtos&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'produtos') ? 'active': '' ; ?>">Produtos</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=usuarios&t=listar" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'usuarios') ? 'active': ''; ?>">Usuários</a>
			</li>
			<li>
				<a href="<?= BASEURL ?>painel.php?m=relatorios&t=relatorios" class="<?= $retVal = (isset($_GET['m']) and $_GET['m'] == 'usuarios') ? 'active': ''; ?>">Relatorios</a>
			</li>
		</ul>
	</div>
	<div class="col s12 m12  <?= ($modulo == 'pdv')? 'l11' : 'l8'; ?> ">
		<?php
		if (isset($modulo) && isset($tela)){ 
			loadmodulo($modulo,$tela); 
		}else{
			?>
			<div class="row">
				<div class="col s12 bg_fff">
					olá
				</div>
			</div>
			
			<?php 
		}
		?>
	</div>


</div>
<?php include('footer.php'); ?>