// Inicialize o carrinho com os dados do localStorage ou uma matriz vazia
const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
const pagamentos = JSON.parse(localStorage.getItem('dados_pagamentos')) || [];

// Obtenha o campo de busca pelo ID
const campoBusca = document.getElementById('codigo-barra');
const campoQuantidade = document.getElementById('quantidade');


// Adicione um ouvinte de evento "keydown" ao campo de busca
campoBusca.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        buscarProdutoPorCodigoDeBarras();
        campoBusca.value="";

    }
});

// Função para adicionar um produto ao carrinho
function adicionarProduto(id, nome, codigoBarra, preco) {
    const produtoExistente = carrinho.find(item => item.id === id);
    const quantidade = parseFloat(campoQuantidade.value); // Converter para número

    if (produtoExistente) {
        // Se o produto já estiver no carrinho, atualize a quantidade
        produtoExistente.quantidade += quantidade; // Somar a quantidade convertida
    } else {
        // Caso contrário, adicione o produto ao carrinho
        carrinho.push({ id, nome, codigoBarra, preco, quantidade });
    }

    campoQuantidade.value = 1;
    // Atualize o carrinho na interface do usuário e no localStorage
    atualizarCarrinho();
}

// Função para remover um produto do carrinho com base no ID
function removerProdutoDoCarrinho(id) {
	const index = carrinho.findIndex(item => item.id === id);

	if (index !== -1) {
        carrinho.splice(index, 1); // Remove o item do carrinho
        atualizarCarrinho(); // Atualize o carrinho na interface do usuário e no localStorage
    }
}

// Função para buscar um produto pelo código de barras e adicionar ao carrinho
function buscarProdutoPorCodigoDeBarras() {
	const codigoBarraInput = document.getElementById('codigo-barra');
	const codigoBarra = codigoBarraInput.value;

    // Simule a busca do produto no banco de dados com base no código de barras
    // Aqui, você pode usar um objeto ou uma função para encontrar o produto correspondente
    const produtoEncontrado = encontrarProdutoPorCodigoDeBarras(codigoBarra);

    
}

    // Função para buscar um produto por código de barras usando AJAX
    function encontrarProdutoPorCodigoDeBarras(codigoBarra) {
        $.ajax({
        type: 'POST', // Você pode usar POST ou GET, dependendo do seu arquivo produto.php
        url: 'buscarproduto.php', // Nome do arquivo PHP que buscará o produto
        data: { codigoBarra: codigoBarra }, // Dados a serem enviados para o servidor (código de barras)
        dataType: 'json', // Tipo de dados esperados na resposta (JSON no exemplo)
        success: function (produto) {
            if (produto) {
                // Produto encontrado, faça algo com ele
                console.log('Produto encontrado:', produto);
                // Chame uma função para adicionar o produto ao carrinho aqui
                adicionarProduto(produto.produto_id, produto.nome, produto.codigo_barras, produto.preco);

            } else {
                // Produto não encontrado
                alert('Produto não encontrado.');
            }
        },
        error: function () {
            // Erro na requisição AJAX
            alert('Erro na busca do produto.');
        }
    });
    }

// Função para criar um botão "Remover" para cada item do carrinho na interface
function criarBotaoRemover(id) {
	const button = document.createElement('button');
	button.textContent = 'Remover';
	button.addEventListener('click', () => {
		removerProdutoDoCarrinho(id);
	});
	return button;
}
// Função para atualizar o carrinho na interface do usuário
function atualizarCarrinho() {
    const tabelaCarrinho = document.getElementById('tabela-carrinho');
    const totalCarrinho = document.getElementById('total-carrinho');

    const pagamentos = JSON.parse(localStorage.getItem('dados_pagamentos')) || [];
    
    // Limpa a tabela de produtos no carrinho, mantendo o cabeçalho
    while (tabelaCarrinho.rows.length > 1) {
        tabelaCarrinho.deleteRow(1);
    }

    let total = 0;

    carrinho.forEach(item => {
        const row = tabelaCarrinho.insertRow(-1); // Insira uma nova linha na tabela
        const cellQuantidade = row.insertCell(0);
        const cellNome = row.insertCell(1);
        const cellPrecoUnitario = row.insertCell(2);
        const cellPrecoTotal = row.insertCell(3);
        const cellRemove = row.insertCell(4);

        cellQuantidade.textContent = item.quantidade;
        cellNome.textContent = item.nome;
        cellPrecoUnitario.textContent = `R$ ${parseFloat(item.preco).toFixed(2)}`;
        cellPrecoTotal.textContent = `R$ ${parseFloat(item.preco * item.quantidade).toFixed(2)}`;

        total += parseFloat(item.preco) * item.quantidade;

        cellRemove.innerHTML = `<a href="javascript:removerProdutoDoCarrinho('${item.id}');">x</a>`;

    });

    totalCarrinho.textContent = `${total.toFixed(2)}`;

    // Atualize o localStorage com o carrinho
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarTabela();
}

// Chame a função atualizarCarrinho para exibir o carrinho ao carregar a página
atualizarCarrinho();


function definirPagamento(formaPagamento, valorPago) {
    pagamento.formaPagamento = formaPagamento;
    pagamento.valorPago = parseFloat(valorPago); // Converter para número
}
// Função para confirmar o recebimento do pagamento
function confirmarRecebimento(formaPagamento) {
    // Obtenha a tabela onde os itens da venda são exibidos
    const tabelaItens = document.getElementById('tabela-carrinho');
    const valorPago = parseFloat(valorPagoInput.value);
    const totalVenda = calcularTotal().toFixed(2);
    // Crie uma nova linha (tr) para mostrar o valor pago e o troco
    const novaLinha = tabelaItens.insertRow(-1);

    // Crie células (td) para a nova linha
    const celulaFormaPagamento = novaLinha.insertCell(0);
    const celulaDescricao = novaLinha.insertCell(1);
    const celulaValor = novaLinha.insertCell(2);



     // Exemplo de lógica para confirmar o pagamento
     let forma = '';
     if (formaPagamento === 1) {
        forma = 'Dinheiro';
    } else if (formaPagamento === 2) {
        forma = 'Pix';
    } else if (formaPagamento === 3) {
        forma = 'Cartão de Crédito';
    } else if (formaPagamento === 4) {
        forma = 'Cartão de Débito';
    } else if (formaPagamento === 5) {
        forma = 'Vale';
    } else {
        alert('Escolha uma forma de pagamento válida.');
        return; // Saia da função se a forma de pagamento for inválida
    }

    // Adicione texto às células
    celulaFormaPagamento.textContent = forma;
    celulaDescricao.textContent = "Valor Pago:";
    celulaValor.textContent = `R$ ${parseFloat(valorPago).toFixed(2)}`;

    // Calcule o troco
    const troco = valorPago - totalVenda;

    // Se houver troco, crie uma linha adicional para exibi-lo
    if (troco > 0) {
        const linhaTroco = tabelaItens.insertRow(-1);
        const celulaDescricaoTroco = linhaTroco.insertCell(0);
        const celulaValorTroco = linhaTroco.insertCell(1);
        celulaDescricaoTroco.textContent = "Troco:";
        celulaValorTroco.textContent = `R$ ${troco.toFixed(2)}`;
    }

    // Adicione os dados de pagamento ao objeto vendaData
    vendaData.pagamentos.push({ forma: forma, valor: valorPago });
    // Limpe o valor pago
    valorPagoInput.value = ''; // Limpe o campo de valor pago
    // Atualize o carrinho na interface do usuário e no localStorage
    
    atualizarCarrinho();
}
// // Função para confirmar o pagamento e enviar os dados da venda via AJAX
function confirmaPagamento() {
    const resumoPedido = document.getElementById('resumo-pedido');
    const totalVenda = calcularTotal().toFixed(2);
    const desconto = parseFloat(campoDesconto.value);

    // Verifique se o carrinho está vazio
    if (carrinho.length === 0) {
        alert('Seu carrinho está vazio. Adicione itens ao carrinho antes de continuar.');
        return; // Impede o envio se o carrinho estiver vazio
    }
    
    const cliente = document.getElementById('cliente').value;

    // Montar um objeto com os dados da venda

    const vendaData = {
        produtos: carrinho,
        pagamentos:  pagamentos,
        totalVenda: totalVenda,
        desconto: desconto
        
    };
    
    var checkbox = document.getElementById("pendente");
    var estaMarcado = checkbox.checked;

    if (cliente) {
     vendaData.cliente = cliente;
     if (estaMarcado) {
         vendaData.situacao = "Pendente";
     }else{
         vendaData.situacao = "Pago";
     }
 } else {
     vendaData.cliente = "1"; 
     if (estaMarcado){
         alert("Selecione um cliente!");
         return;
     }else{
         vendaData.situacao = "Pago";
     }

 }

    // Enviar os dados da venda via AJAX
    $.ajax({
        type: 'POST',
        url: 'venda.php',
        data: JSON.stringify(vendaData), // Enviar os dados da venda como JSON
        contentType: 'application/json',
        dataType: 'json',
        success: function (resposta) {
            if (resposta.status === 'sucesso') {

                preencherPopup(resposta);

                // Exiba a popup
                exibirPopup();
                imprimirPopup();

                // Limpar o carrinho após a venda
                carrinho.length = 0;
                pagamentos.length = 0;
                atualizarCarrinho();
                $('#desconto').val("");
            } else {
                // Erro no pagamento
                alert('Erro ao confirmar o pagamento.');

            }
        },
        error: function () {
            // Erro na requisição AJAX
            alert('Erro na requisição AJAX.');
            console.log(carrinho);
            console.log(pagamentos);
        }
    });
    document.getElementById("meuLink").classList.add("disabled");   
}

// Função para calcular o total do carrinho
function calcularTotal() {
    let total = 0;
    carrinho.forEach(item => {
        total += parseFloat(item.preco) * item.quantidade;
    });

    
    return total;
}

// Seletor para o campo de valor pago
const valorPagoInput = document.getElementById('valor-pago');

// Evento 'input' para detectar mudanças no valor pago
//valorPagoInput.addEventListener('input', calcularTroco);

// Função para formatar o valor como dinheiro
function formatarDinheiro(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// Função para calcular e exibir o troco
function calcularTroco() {
    const valorPago = parseFloat(valorPagoInput.value);

    // Verifique se o valor pago é válido
    if (isNaN(valorPago)) {
        return;
    }

    // Calcule o troco
    const troco = valorPago - calcularTotal();

    // Exiba o troco formatado como dinheiro
    const trocoElement = document.getElementById('troco');
    trocoElement.textContent = formatarDinheiro(troco);
}
// Função para exibir a popup
function exibirPopup() {
    const popup = document.getElementById('popup');
    popup.style.display = 'block';
}

// Função para fechar a popup
function fecharPopup() {
    const popup = document.getElementById('popup');
    popup.style.display = 'none';
}

// Função para preencher a popup com dados
function preencherPopup(dados) {
    const popupConteudo = document.querySelector('.popup-conteudo');
    produtos = dados.produtos;
    if(dados.cliente.nome != 'Padrão'){
        dadosCliente = `<br><br>Dados do cliente:
        <p>Cliente: ${dados.cliente.nome}</p>
        <p>CNPJ: ${dados.cliente.cnpj}</p>
        <p>Endereço: ${dados.cliente.endereco}</p>
        <p>Telefone: ${dados.cliente.telefone}</p>
        
        `;
    }else{
       dadosCliente = ``; 
   }
   popupConteudo.innerHTML = `
   <span class="fechar" onclick="fecharPopup()">&times;</span>

   <p style="font-size: 26px;margin: 0;">Papelaria Status</p>
   30.433.491/0001-25<br>
   (41) 99777-2844<br>
   Av. Guaíra, 1173, Piçarras, Guaratuba - Pr
   <hr>
   ${dadosCliente}
   <p>Número do recibo: ${dados.recibo}</p>
   <h6>Itens</h6>

   ${(() => {
    let html = '';
    html += `<table>
    <thead>
    <th>Qtd</th>
    <th>Nome</th>
    <th>Preço</th>
    <th>SubTotal</th>
    </thead>
    <tbody>`;
            for (const produto of produtos) { // Usar uma variável separada (produto) para iterar pela matriz produtos
               html += `
               <tr>
               <td>${produto.quantidade}</td>
               <td>${produto.nome}</td>
               <td>${produto.preco}</td>
               <td>${(produto.quantidade * produto.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</td>
               </tr>
               `;
           }


           html += `</tbody></table>`;
           return html;
       })()}
       <p style="text-align: right;font-weight:bold;">SubTotal: R$ ${dados.totalVenda}</p>
       <p style="text-align: right;font-weight:bold;">Desconto: R$ ${dados.desconto}</p>
       <p style="text-align: right;font-weight:bold;">Total: R$ ${(dados.totalVenda - dados.desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</p>
       <br>
       <p style="text-align: center;font-size: 19px;">Obrigado!</p>
       <br><br><br><br>
       <p></p>
       `;

   }
// Função para imprimir o conteúdo da popup
function imprimirPopup() {
    // Exibir a popup para que o conteúdo seja carregado
    //exibirPopup();

    // Esperar um curto período de tempo para garantir que o conteúdo seja carregado
    setTimeout(function () {
        // Abrir uma nova janela ou aba com o conteúdo da popup
        const popup = document.getElementById('popup');
        const popupConteudo = popup.querySelector('.popup-conteudo');
        const conteudoParaImprimir = popupConteudo.innerHTML;

        const novaJanela = window.open('', '', 'width=600,height=600');
        novaJanela.document.open();
        novaJanela.document.write(`
            <html>
            <head>
            <title>Imprimir Popup</title>
            </head>
            <body>
            ${conteudoParaImprimir}
            <script>
            // Acionar a função de impressão após um breve atraso
            setTimeout(function () {
                window.print();
                window.close();
            }, 1000); // Atraso de 1 segundo para garantir que o conteúdo seja carregado
            </script>
            </body>
            </html>
            `);
        novaJanela.document.close();
    }, 1000); // Atraso de 1 segundo para garantir que o conteúdo seja carregado
}



////////////////////////////////////////////////////////////
//SALVANDO DADOS DE PAGAMENTO


// Função para lidar com o clique no botão "Enviar" para adicionar um pagamento
document.getElementById('confirmaPagamento').addEventListener('click', function () {
    event.preventDefault();
    // Obtenha o valor selecionado do tipo de pagamento
    const tipoPagamento = document.getElementById('forma-pagamento').value;
    // Obtenha o valor inserido
    const valorInserido = parseFloat(document.getElementById('valor-pago').value);

    // Verifique se o valor inserido é válido
    if (isNaN(valorInserido)) {
        alert('Insira um valor válido.');
        return;
    }

    // Crie um objeto de pagamento com o tipo e valor
    const pagamento = {
        tipo: tipoPagamento,
        valor: valorInserido
    };

    // Adicione o novo pagamento ao array de pagamentos
    pagamentos.push(pagamento);

    // Atualize o armazenamento local com o array atualizado de pagamentos
    localStorage.setItem('dados_pagamentos', JSON.stringify(pagamentos));
    document.getElementById("meuLink").classList.remove("disabled");
    calcularTroco();

    // Chame a função para atualizar a tabela de pagamentos
    atualizarTabela();
    // Limpe os campos de seleção e inserção
    document.getElementById('forma-pagamento').value = '';
    document.getElementById('valor-pago').value = '';

    // Exiba uma mensagem de sucesso ou execute ações adicionais, se necessário
    console.log(pagamentos);
});

/////////////////////////////////////
// ATUALIZAR TABELA
// Função para atualizar a tabela com os dados de pagamento
function atualizarTabela() {
    const tabela = document.getElementById('tabela-carrinho');
    const tbody = tabela.querySelector('tbody');
    console.log("aqui"+tbody);
    // Limpa o corpo da tabela antes de atualizar
    tbody.innerHTML = '';

    // Recupera os dados de pagamento do localStorage
    const dadosArmazenados = localStorage.getItem('dados_pagamentos');
    const pagamentos = dadosArmazenados ? JSON.parse(dadosArmazenados) : [];

    // Itera sobre os pagamentos e adiciona cada um à tabela
    pagamentos.forEach(function (pagamento, index) {
        const row = tbody.insertRow(-1);
        const formaPagamentoCell = row.insertCell(0);
        const valorCell = row.insertCell(1);
        const colunaVazia1 = row.insertCell(2);
        const colunaVazia2 = row.insertCell(3);
        const removerCell = row.insertCell(4);

        formaPagamentoCell.textContent = pagamento.tipo;
        valorCell.textContent = pagamento.valor;

        // Botão de remoção com evento de clique para remover a linha
        const removerButton = document.createElement('button');
        removerButton.textContent = 'Remover';
        removerButton.addEventListener('click', function () {
            // Remove a linha da tabela
            tbody.deleteRow(index);

            // Remove o pagamento do array
            pagamentos.splice(index, 1);

            // Atualize o armazenamento local
            localStorage.setItem('dados_pagamentos', JSON.stringify(pagamentos));
        });

        removerCell.appendChild(removerButton);

    });

}
atualizarTabela();


const campoDesconto = document.getElementById('desconto');

campoDesconto.addEventListener('input', function () {
    calcularTotal(); // Chame a função para recalcular o total sempre que o desconto for alterado
});



function atualizarBarraDeProgresso() {
   const metaMensal = 10000; 
   const totalVenda = document.getElementById('totalMesProgresso').value;
   const barraDeProgresso = document.getElementById('progress-bar');

   const progresso = (totalVenda / metaMensal) * 100;

   $('#progress-bar-porcentagem').text(progresso.toFixed(2)+'%');

   barraDeProgresso.style.width = progresso + '%';
}



