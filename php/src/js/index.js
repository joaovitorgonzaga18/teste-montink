localStorage.clear()

if (localStorage.length === 0) {
	localStorage.preco = 0.0;
	localStorage.frete = 0.0;
	localStorage.desconto = 0.0;
	localStorage.pedidos = JSON.stringify({
		'precos': [],
		'produtos': []
	});
	localStorage.cupom = 0;
	localStorage.cep = '';
}

// Função responsável por carregar o conteúdo de uma rota em uma div específica
function abrir_div(url, div, index, callBack, obj) {

	jQuery('#' + div).html('');

	if (typeof (div) == 'undefined') {
		div = 'container';
	}

	$('.side-menu li').each(function (index, el) {
		a = $(this).find('a').attr('href');
		if (a != $(obj).attr('href')) {
			$(this).removeClass('active');
			$(this).removeClass('current-page');
		} else {
			u = $(this).parent('ul').parent('li');
			$(u).addClass('active');
		}
	});

	console.log("abrir_div('" + url + "', '" + div + "', '" + index + "');");

	jQuery.post(url, { index: index }, function (resposta) {
		jQuery('#' + div).html(resposta);
	}).done(function () {
		if (typeof (callBack) == 'undefined') {
			setTimeout(callBack, 200);
		}
	});

}

// Função responsável por calcular e salvar as informações dos pedidos adicionados em LocalStorage
function add_pedido(id) {

	$.get("/index.php/produtoscontroller/get/" + id + "/1", function (data) {


		// Calcular frete e valor total do pedido, atualizando em LocalStorage
		var produto = data.produto
		var valor_total = parseFloat(localStorage.preco) + produto.preco
		var frete = 20 // Valor padrão do frete

		// Verificar regras do valor do frete
		if (valor_total >= 52 && valor_total <= 166.59) {
            frete = 15;
        } else if (valor_total > 200) {
            frete = 0;
        }
		
		// Guardar o valor total mais o frete em LocalStorage
		localStorage.preco = valor_total 
		localStorage.frete = frete
		console.log(valor_total, frete, localStorage.preco)

		// Convertar em objeto as informações de produtos em já guardados em LocalStorage
		pedidos = JSON.parse(localStorage.pedidos)
		pedidos.precos.push(produto.preco)

		// O index do array funciona como controle para a remoção de produtos através do ID do produto
		produtos_pedido = pedidos.produtos
		produtos_pedido[produto.id] = { 'id': produto.id, 'nome': produto.nome, 'estoque': produto.estoque }

		// Guardar as informações dos produtos em localStorage
		pedidos.produtos = produtos_pedido
		localStorage.pedidos = JSON.stringify(pedidos)

		// Mostrar os valores na tela
		$("#valor-total").html((valor_total + frete).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));
		$("#valor-frete").html(frete.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));

		// Adicionar o produto adicionado à tabela
		$(".lista-pedido").append('<tr class="text-center" id="compra-' + produto.id + '" data-preco="' + produto.preco + '" data-produto-id="' + produto.id + '">' +
			'<th>' + produto.id + '</th>' +
			'<td>' + produto.nome + '</td>' +
			'<td>' + produto.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) + '</td>' +
			'<td><input type="number" class="form-control form-control-sm" style="text-align: center;" name="qtd[]" value="1" min="1" max="' + produto.estoque + '"></td>' +
			'<td>' +
			'<button type="button" class="btn btn-danger" onclick="remove_pedido(' + produto.id + ')"><i class="fa-solid fa-trash"></i></button>' +
			'</td>' +
			'</tr>');



	}, "json");
}

function remove_pedido(id) {

	var row = $("#compra-" + id)

	preco = parseFloat(localStorage.preco) - row.data("preco")
	localStorage.preco = preco

	pedidos = JSON.parse(localStorage.pedidos)

	const index = pedidos.precos.indexOf(row.data("preco"));
	if (index > -1) {
		pedidos.precos.splice(index, 1);
	}

	produtos_pedido = pedidos.produtos
	produtos_pedido.splice(id, 1)

	pedidos.produtos = produtos_pedido

	localStorage.pedidos = JSON.stringify(pedidos)

	formatado = preco.toLocaleString('pt-BR', {
		style: 'currency',
		currency: 'BRL'
	});

	$("#valor-total").html(formatado);

	$("#compra-" + id).remove();
}