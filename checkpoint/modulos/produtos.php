<?php 
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
switch ($tela){
	case 'incluir':
	
	if (isset($_POST['publicar'])) {
		$produtos = new produtos(array(
			"codigo_barras" => $_POST['codigo_produto'],
			"nome" => $_POST['nome_produto'],
			"preco" => $_POST['valor_venda_produto'],
		));
		$produtos->inserir($produtos);
		if ($produtos->linhasafetadas==1) {
			$lastid = mysqli_insert_id($produtos->conexao);
			
			if (($_FILES['destaques']['size'][0] != 0) && ($_FILES['destaques']['tmp_name'][0] != '')) {
				$mover = Anexos($_FILES['destaques'], '500', '500');
				foreach ($mover as $value) {
					$anexos = new anexos(array(
						'nome_anexo'=>$value,
					));
					$anexos->inserir($anexos);
				}
				if ($anexos->linhasafetadas > 0) {
					$idAnexo = mysqli_insert_id($anexos->conexao);
					$p_a = new produtos_has_anexos(array(
						"produtos_id_produto" => $lastid,
						"anexos_id_anexo" => $idAnexo,
					));
					$p_a->inserir($p_a);
					if ($p_a->linhasafetadas > 0) {
					}else{
						popup(null, null, 'Erro ao anexar imagem ao post', 'erro');
					}
				}else{
					popup(null, null, 'Erro ao fazer uploads', 'erro');
				}
			}
			echo "<script>window.location.href = '?m=produtos&t=incluir&status=save';</script>";
		}
	}
	?>
	<div class="row">
		<form class="col s12 bg_fff" id="my-form" action=""   method="post" enctype="multipart/form-data">
			<?php 
			
			?>
			<div class="col s12 m12 l9 border-right" >
				<h5>Produto</h5>
				<div class="col s12 input-field">
					<input type="text" id="codigo_produto" autofocus class="naoenviar" name="codigo_produto">
					<label for="codigo_produto">Código de barra</label>
				</div>
				<div class="col s12 input-field">
					<input type="text" id="nome_produto" name="nome_produto">
					<label for="nome_produto">Nome do produto</label>
				</div>
				<div class="col l4 m6 s12 input-field">
					<input type="text" id="preco" name="valor_venda_produto">
					<label for="valor_produto">Valor do produto</label>
				</div>
				
				<div class="col s12 input-field file-field">
					<div class="btn bg_color">
						<span>Imagem destaque</span>
						<input type="file" name="destaques[]" id="destaque">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<div class="col s3">
					<div class="image center-align">
						<i class="fa fa-picture-o" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			<div class="col s12 m12 l3">
				<div class="col s12  input-field">
					<input type="submit" name="publicar" value="Publicar" class="btn bg_color" >
				</div>
				<div class="col s12 input-field categorias_imoveis">
					<h6>Categorias</h6>
					<?php /*
					$categorias  = new categorias();
					$categorias->extras_select = "where status_produto=1";
					$categorias->selecionaTudo($categorias);
					while ($res = $categorias->retornaDados()) {
						?>
						<p>
							<label class="checkbox">
								<input type="checkbox" id="chk<?php echo $res->id; ?>" name="categorias[]" value="<?php echo $res->id_categorias; ?>">
								<span><?php echo $res->nome_categoria; ?></span>
							</label>
						</p>
						<?php
					} */
					?>
				</div>
			</div>
			
		</form>
	</div>
	<?php 
	break;
	//LISTAR
	case 'listar':
	?>
	<div class="row">
		<div class="col s12 ">
		</div>
		<div class="col s12 bg_fff">
			<div class="col s6">
				<h5>Todos os produtos</h5>
			</div>
			<div class="col s6 right-align">
				<a href="<?= BASEURL  ?>painel.php?m=produtos&t=incluir">
					<div class="bt-new ">
						<i class="fas fa-plus"></i> Novo produtos
					</div>
				</a>
			</div>
			<div class="col s12">
				<table class="responsive-table striped highlight">
					<thead>
						<tr>
							<th>Código de Barras</th>
							<th>Nome do Produto</th>
							<th>Quantidade</th>
							<th>Preço</th>
							<th class="center-align">Editar/Excluir</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$produtos = new produtos();
						$produtos->extras_select = "order by codigo_barras desc";
						$produtos->selecionaTudo($produtos);
						while ($res = $produtos->retornaDados()){
							?>
							<tr>
								<td><?= $res->codigo_barras; ?></td>
								<td><?= $res->nome; ?></td>
								<td><?= $res->quantidade_estoque; ?></td>
								<td><?= $res->preco; ?></td>
								<td class="center-align">
									<a href="<?= BASEURL ?>painel.php?m=produtos&t=editar&id=<?= $res->produto_id ?>" class="btn bg_color">
										<i class="far fa-edit"></i>
									</a>
								</td>
							</tr>
							<?php 
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php 
	break;
	//-EDITAR
	case 'editar':
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if (isset($_POST['atualizar'])) {
			//atualizar post
			$produtos = new produtos(array(
				"nome" => $_POST['nome_produto'],
				"codigo_barras" => $_POST['codigo_produto'],
				"preco" => $_POST['valor_venda_produto'],
			));
			$produtos->valorpk=$id;
			$produtos->atualizar($produtos);
			
			echo "<script>window.location.href = '?m=produtos&t=editar&id=".$id."&status=update';</script>";
		}
		//dados do banco
		$produtos = new produtos();
		$produtos->extras_select = "where produto_id = $id";
		$produtos->selecionaTudo($produtos);
		$res = $produtos->retornaDados();
		?>
		<div class="row">
			<form class="col s12 bg_fff" id="my-form" action=""   method="post" enctype="multipart/form-data">
				<div class="col s12 m12 l9 border-right" >
					<h5>Alterar Produto</h5>
					<div class="col s12 input-field">
						<input type="text" id="codigo_produto" autofocus class="naoenviar" name="codigo_produto" value="<?= $res->codigo_barras ?>">
						<label for="codigo_produto">Código de barra</label>
					</div>
					<div class="col s12 input-field">
						<input type="text" id="nome_produto" name="nome_produto" value="<?= $res->nome ?>">
						<label for="nome_produto">Nome do produto</label>
					</div>
					<div class="col l4 m6 s12 input-field">
						<input type="text" id="preco" name="valor_venda_produto" value="<?= $res->preco ?>">
						<label for="valor_produto">Valor do produto</label>
					</div>					
				
					<div class="col s12  input-field">
						<input type="submit" name="atualizar" value="Atualizar" class="btn bg_color" >
					</div>	
								
					
				</div>
			</form>
		</div>
		<?php
	}else{
		echo "Aluno não encontrado	";
	}
	break;
	case 'trash':
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$produtos = new produtos(array(
			"status_produto" => '0',
		));
		$produtos->valorpk=$id;
		$produtos->atualizar($produtos);
		$produtos->selecionaTudo($produtos);
		$resprodutos = $produtos->retornaDados();
		if ($produtos->linhasafetadas > 0) {
			?>
			<div class="row">
				<div class="container">
					<div id="modal2" class="modal" style="display: block;">
						<div class="modal-content">
							<h5>Curso inativo</h5>
							<p>O curso <?= $resprodutos->nome_produto; ?> foi marcado como inativo no sistema. Isso significa que o curso não estará mais disponivel em nosso site.</p>
							<div class="modal-footer">
								<a href="<?= BASEURL ?>painel.php?m=produtos&t=listar" class="modal-close waves-effect waves-green btn-flat bg_escuro right-float">Ok</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
		<?php 
	}
	break;
	case 'excluir':
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
						//salvar alterações
		$produtos = new produtos(array("status"=>'2'));
		$produtos->valorpk=$id;
		$produtos->atualizar($produtos);
		if ($produtos->linhasafetadas==1) {
			popup(null, null, 'Post Excluido com sucesso', 'sucesso');
		}else{
			popup(null, null, 'Não foi possível excluir o post', 'erro');
		}
		break;
	}
	default:
	echo "default";
	break;
}
/*
		$imoves = new imoves();		
		$show = $imoves->ShowAll(array('Blog','Clientes'), 'desc', 1);
		foreach ($show as $key => $value) {
			$link = UrlA($value->titulo);
			echo "<a href=".BASEURL.$link.">".$value->titulo."</a>";
			echo $value->textarea;
			echo $value->created;
			$imoves->destaque($value->imoves_id);
			$galeria = $imoves->galeria($value->produtos_id);
			foreach ($galeria as $key => $value) {
				echo $value->imagem;
			}
		} 
*/
		?>
