localStorage.clear()

if (localStorage.length === 0) {
	localStorage.preco = 0.0;
	localStorage.frete = 20.0;
	localStorage.desconto = 0.0;
	localStorage.pedidos = JSON.stringify({
		'produtos': []
	});
	localStorage.cupom = 0;
	localStorage.cep = '';
}

// Função responsável por carregar o conteúdo de uma rota em uma div específica
function abrir_div(url, div, index, callBack, obj) {
	
	$("body").LoadingOverlay("show")

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

	jQuery.post(url, { index: index }, function (resposta) {
		jQuery('#' + div).html(resposta);	
		$("body").LoadingOverlay("hide")
	}).done(function () {
		if (typeof (callBack) == 'undefined') {
			setTimeout(callBack, 200);
		}
	});

}

// Função responsável por calcular e salvar as informações dos pedidos adicionados em LocalStorage
function add_pedido(id) {
	
	$("body").LoadingOverlay("show")
	$.get("/index.php/produtoscontroller/get/" + id + "/1", function (data) {


		// Calcular frete e valor total do pedido, atualizando em LocalStorage
		var produto = (data.produto)
		var valor_total = parseFloat(localStorage.preco) + produto.preco
		var frete = 20 // Valor padrão do frete
		var desconto = parseFloat(localStorage.desconto)

		// Verificar regras do valor do frete
		if (valor_total >= 52 && valor_total <= 166.59) {
			frete = 15;
		} else if (valor_total >= 200) {
			frete = 0;
		}

		// Guardar o valor total e o frete em LocalStorage
		localStorage.preco = valor_total
		localStorage.frete = frete

		// Convertar em objeto as informações de produtos em já guardados em LocalStorage
		pedidos = JSON.parse(localStorage.pedidos)

		// O index do array funciona como controle para a remoção de produtos através do ID do produto
		produtos_pedido = pedidos.produtos
		produtos_pedido[produto.id] = { 'id': produto.id, 'nome': produto.nome, 'estoque': produto.estoque, 'qtd': 1, 'preco': produto.preco }

		// Guardar as informações dos produtos em localStorage
		pedidos.produtos = produtos_pedido
		localStorage.pedidos = JSON.stringify(pedidos)

		// Mostrar os valores na tela
		$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
		$("#valor-frete").html(frete.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

		// Adicionar o produto adicionado à tabela
		$(".lista-pedido").append('<tr class="text-center" id="compra-' + produto.id + '" data-preco="' + produto.preco + '" data-produto-id="' + produto.id + '">' +
			'<th>' + produto.id + '</th>' +
			'<td>' + produto.nome + '</td>' +
			'<td>' + produto.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) + '</td>' +
			'<td><input type="number" class="form-control form-control-sm" id="qtd-produto-'+produto.id+'" style="text-align: center;" name="qtd[]" value="1" min="1" max="' + produto.estoque + '" onchange="update_qtd(this)"></td>' +
			'<td>' +
			'<button type="button" class="btn btn-danger" onclick="remove_pedido(' + produto.id + ')"><i class="fa-solid fa-trash"></i></button>' +
			'</td>' +
			'</tr>');
		
		$("body").LoadingOverlay("hide")

	}, "json");
}

function remove_pedido(id) {

	var row = $("#compra-" + id)

	var valor_total = parseFloat(localStorage.preco) - row.data("preco")
	var frete = 20 // Valor padrão do frete
	var desconto = parseFloat(localStorage.desconto)

	// Verificar regras do valor do frete
	if (valor_total >= 52 && valor_total <= 166.59) {
		frete = 15;
	} else if (valor_total >= 200) {
		frete = 0;
	}

	localStorage.preco = valor_total
	localStorage.frete = frete

	pedidos = JSON.parse(localStorage.pedidos)

	produtos_pedido = pedidos.produtos
	produtos_pedido.splice(id, 1)

	pedidos.produtos = produtos_pedido

	localStorage.pedidos = JSON.stringify(pedidos)

	$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
	$("#valor-frete").html(frete.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

	$("#compra-" + id).remove();
}

// Função responsável por alterar as informações do alerta
function change_alert(hidden, status, message) {
	if (hidden) { $('#alert').addClass('hidden') } else { $('#alert').removeClass('hidden') }
	$('#alert').removeClass('alert-success alert-danger')
	$('#alert').addClass('alert-' + status)
	$('#alert').html(message)
}

function check_cupom() {

	$("body").LoadingOverlay("show")

	$.get("/index.php/cuponscontroller/getbycode/" + $('#cupom').val() + "/1", function (data) {

		if (data == 'undefined' || data.length === 0 || data.cupom.valido === 0) {

			var valor_total = parseFloat(localStorage.preco)
			var frete = parseFloat(localStorage.frete)
			var desconto = 0

			// Verificar regras do valor do frete
			if (valor_total >= 52 && valor_total <= 166.59) {
				frete = 15;
			} else if (valor_total >= 200) {
				frete = 0;
			}

			$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
			$("#desconto").html((desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

			localStorage.cupom = 0
			localStorage.desconto = desconto

			change_alert(false, 'danger', 'Cupom não encontrado/inválido')
			$("body").LoadingOverlay("hide")

		} else {

			cupom = data.cupom
			console.log("/index.php/cuponscontroller/getbycode/" + $('#cupom').val() + "/1")
			console.log(data, cupom, $('#cupom').val())

			var valor_total = parseFloat(localStorage.preco)
			var frete = parseFloat(localStorage.frete)
			var desconto = cupom.desconto

			// Verificar regras do valor do frete
			if (valor_total >= 52 && valor_total <= 166.59) {
				frete = 15;
			} else if (valor_total >= 200) {
				frete = 0;
			}

			$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
			$("#desconto").html((desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

			localStorage.cupom = cupom.id
			localStorage.desconto = desconto

			change_alert(false, 'success', 'Cupom aplicado')
			$("body").LoadingOverlay("hide")
		}

	}, 'json')
			
}

function load_pedido() {

	if (localStorage.length > 0) {

		var local_json = JSON.parse(localStorage.pedidos)
		var produtos = jQuery.grep(local_json.produtos, function (n, i) {
			return (n !== "" && n != null);
		});

		var html = ''

		var valor_total = parseFloat(localStorage.preco)
		var frete = parseFloat(localStorage.frete)
		var desconto = parseFloat(localStorage.desconto)

		for (const row of produtos) {
			console.log(row.id, produtos)

			html += ('<tr class="text-center" id="compra-' + row.id + '" data-preco="' + row.preco + '" data-produto-id="' + row.id + '">' +
				'<th>' + row.id + '</th>' +
				'<td>' + row.nome + '</td>' +
				'<td>' + row.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) + '</td>' +
				'<td><input type="number" class="form-control form-control-sm" id="qtd-produto-'+row.id+'" style="text-align: center;" value="' + row.qtd + '" min="1" max="' + row.estoque + '" onchange="update_qtd(this)"></td>' +
				'<td>' +
				'<button type="button" class="btn btn-danger" onclick="remove_pedido(' + row.id + ')"><i class="fa-solid fa-trash"></i></button>' +
				'</td>' +
				'</tr>');

		}

		$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
		$("#valor-frete").html(frete.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
		$("#desconto").html((desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

		$(".lista-pedido").html(html)

	}

}

function remove_special_char(str) {
	return str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').replace(/[a-zA-Z]/g, '')
}

function check_cep() {
	var cep = remove_special_char($("#cep").val())
	$.get("https://viacep.com.br/ws/" + cep + "/json/", function (data) {
		change_alert(false, 'success', 'CEP Encontrado')
		localStorage.cep = cep
	}, 'json').fail(function () {
		change_alert(false, 'danger', 'CEP Inválido')
	}, 'json')
}

function update_qtd(btn) {	

	var qtd_produto = $("#"+btn.id)

	var pedidos = JSON.parse(localStorage.pedidos)
	const produto_id = remove_special_char(qtd_produto.parent().parent().attr('id'))
	const preco_produto = qtd_produto.parent().parent().data("preco")
	const new_qtd = qtd_produto.val()
	const old_qtd = pedidos.produtos[produto_id].qtd

	pedidos.produtos[produto_id].preco = new_qtd * preco_produto

	// Calcular frete e valor total do pedido, atualizando em LocalStorage
	if (new_qtd >= pedidos.produtos[produto_id].qtd) {
		var valor_total = parseFloat(localStorage.preco) + (preco_produto * (new_qtd - old_qtd))
	} else {
		var valor_total = parseFloat(localStorage.preco) - (preco_produto * (old_qtd - new_qtd))
	}

	var frete = 20 // Valor padrão do frete
	var desconto = parseFloat(localStorage.desconto)

	pedidos.produtos[produto_id].qtd = new_qtd

	// Verificar regras do valor do frete
	if (valor_total >= 52 && valor_total <= 166.59) {
		frete = 15;
	} else if (valor_total >= 200) {
		frete = 0;
	}

	// Guardar o valor total e o frete em LocalStorage
	localStorage.preco = valor_total
	localStorage.frete = frete

	// Convertar em objeto as informações de produtos em já guardados em LocalStorage
	// pedidos = JSON.parse(localStorage.pedidos)

	localStorage.pedidos = JSON.stringify(pedidos)

	$("#valor-total").html((valor_total + frete - desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
	$("#valor-frete").html(frete.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

}

$(document).ready(function () {

	$("#confirma-pedido").click(function () {

		$("body").LoadingOverlay("show")
		var local_json = JSON.parse(localStorage.pedidos)
		var arr = jQuery.grep(local_json.produtos, function (n, i) {
			return (n !== "" && n != null);
		});

		if (arr.length === 0) {
			change_alert(false, 'danger', 'Não é possível confirmar um pedido sem nenhum produto adicionado')
			return
		}

		local_json.produtos = arr
		localStorage.pedidos = JSON.stringify(local_json)

		if ($("#cep").val() === '') {
			change_alert(false, 'danger', 'O campo CEP não pode estar vazio')
			return
		}

		$.ajax({
			url: "/index.php/pedidoscontroller/create", // Replace with your server URL
			type: "POST",
			headers: {
				'Content-Type': 'application/json'
			},
			data: JSON.stringify(localStorage),
			cache: false,
			dataType: "json",
			contentType: "application/json",
			success: function (response) {
				alert('certo!!');
				localStorage.clear()
				location.reload();
			},
			error: function (xhr, status, error) {
				// console.error("Error:", status, error);
				// Handle errors during the AJAX request
			}
		});
		
		$("body").LoadingOverlay("hide")
	});
});