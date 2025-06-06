<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use BcMath\Number;
use Entity\Pedidos;
use Entity\Produtos;
use Entity\Estoque;

class PedidosController extends CI_Controller{ 
	function __construct(){
		parent::__construct();
	}

    // Função responsável por criar e persistir um novo Pedido no banco de dados
	public function create() {

        $post = json_decode(file_get_contents('php://input'), true);

        /* $post = [
            'pedidos' => [
                'precos' => [10, 10, 10, 20],
                'produtos' => [
                    ['id' => 1, 'qtd' => 1,],
                    ['id' => 2, 'qtd' => 1,],
                    ['id' => 3, 'qtd' => 1,],
                    ['id' => 4, 'qtd' => 1,]
                ]
            ],
            'cupom' => 1,
            'cep' => '32340120',
        ]; */

		$data = [];

		$pedidos = json_decode($post['pedidos'], true);

        $valor_total = $post['preco'];
        $frete = 20;

        if ($valor_total >= 52 && $valor_total <= 166.59) {
            $frete = 15;
        } else if ($valor_total > 200) {
            $frete = 0;
        }

		// Instanciando nova entidade Pedido
		$newPedido = new Entity\Pedidos;
        $cupom = $this->doctrine->em->find('Entity\Cupons', $post['cupom']);

        $desconto = ($cupom) ? $cupom->getDesconto() : 0;
		
		// Definindo os valores do novo Pedido com base nos parametros passados
        $newPedido->setIdCupom(($cupom) ? $cupom : null);
		$newPedido->setPreco($valor_total + $frete - $desconto);
        $newPedido->setDesconto($desconto);
        $newPedido->setFrete($frete);
        $newPedido->setCep(($post['cep'] != '') ? $post['cep'] : null);
        $newPedido->setDataPedido(date('Y-m-d h:i:s'));
		
		// Persistir o novo Pedido no banco de dados com EntityManager
		($this->doctrine->em->persist($newPedido));
		$this->doctrine->em->flush();

		// Verificar se o pedido foi realmente persistido
		if ($this->doctrine->em->contains($newPedido)) {

            // Para cada produto no pedido, criar um cadastro em pedido_produto
            foreach($pedidos['produtos'] as $k => $row) {
                $produto = $this->doctrine->em->find('Entity\Produtos', $row['id']);
				$estoque = $this->doctrine->em->getRepository('Entity\Estoque')->findOneBy(array('idProduto' => $row['id']));

                $pedido_produto = new Entity\PedidoProduto;

                $pedido_produto->setIdPedido($newPedido);
                $pedido_produto->setIdProduto($produto);
                $pedido_produto->setQtdProduto($row['qtd']);

				$estoque->setQtdEstoque($estoque->getQtdEstoque() - $row['qtd']);

                $this->doctrine->em->persist($pedido_produto);

            }

            $this->doctrine->em->flush();
			
		}

	}

	// Função responsável por encontrar um único pedido baseado no seu ID
	public function get($idPedido = 0) {
		
		$data = ["pedido" => [], "produtos" => []];

		// Verificar se o id do pedido é válido
		if ($idPedido > 0) {
			$pedido = $this->doctrine->em->find('Entity\Pedidos', $idPedido);
			
			// Verificar se o pedido foi encontrado
			if ($pedido) {

				$pedido_produto = $this->doctrine->em->getRepository('Entity\PedidoProduto')->findBy(array('idPedido' => $pedido->getId()));

				// Verificar se há produtos no pedido
				if ($pedido_produto) {

					// Criar um array com os produtos do pedido
					foreach($pedido_produto as $k => $row) {

						$produto = $row->getIdProduto();

						$data['produtos'][] = [
							"nome" => $produto->getNome(),
							"preco" => $this->format_price($produto->getPreco()) . ' (' . $this->format_price($produto->getPreco() * $row->getQtdProduto()) . ')',
							"qtd" => $row->getQtdProduto(),
						];
					}
				}

				$cupom = $pedido->getIdCupom();

				$data['pedido'] = [
                    'id' => $pedido->getId(),
                    'cupom' => ($cupom) ? $cupom->getCodigo() : '---',
                    'preco' => 'R$ ' . number_format($pedido->getPreco(), 2, ',', '.'),
                    'desconto' => 'R$ ' . number_format($pedido->getDesconto(), 2, ',', '.'),
                    'frete' => 'R$ ' . number_format($pedido->getFrete(), 2, ',', '.'),
                    'cep' => $pedido->getCep(),
                    'data_pedido' => date('d/m/Y h:i:s', strtotime($pedido->getDataPedido())),
				];
			}
		}

		// Carregar view principal
		$this->load->view('pedido_view', $data);
	}

	// Função responsável por encontrar e listar todos os produtos armazenados
	public function getAll() {

		$data = [];

		//Buscar todos os produtos armazenados em banco
		$produtos =  $this->doctrine->em->getRepository('Entity\Pedidos')->findAll();

		// Verificar se encontrou produtos
		if ($produtos)
			$data['produtos'] = $produtos;

		// Carregar view principal
		$this->load->view('principal_view', $data);

	}

	public function update($idPedido = 0) {
		
		$data = [];

		$post = $_POST;

		// Verificar se o id do produto é válido
		if ($idPedido > 0) {
			$produto = $this->doctrine->em->find('Entity\Pedidos', $idPedido);
			
			// Verificar se o produto foi encontrado
			if ($produto) {
				// Atualizar os dados
				$produto->setNome((isset($post['nome']) && $post['nome'] != $produto->getNome()) ? $post['nome'] : $produto->getNome());
				$produto->setPreco((isset($post['preco']) && $post['preco'] != $produto->getPreco()) ? $post['preco'] : $produto->getPreco());
				$produto->setVariacao((isset($post['variacao']) && $post['variacao'] != $produto->getVariacao()) ? $post['variacao'] : $produto->getVariacao());

				$estoque = $this->doctrine->em->findBy('Entity\Estoque', array('id_produto' => $produto->getId()));
				
				if ($estoque)
					$estoque->setQtdEstoque((isset($post['variacao']) && $post['variacao'] != $estoque->getQtdEstoque()) ? $post['variacao'] : $estoque->getQtdEstoque());

				// Persistir os novos dados do Pedido e do Estoque no banco de dados com EntityManager
				$this->doctrine->em->persist($produto);
				$this->doctrine->em->persist($estoque);
				$this->doctrine->em->flush();

				if ($produto)
					$data['produto'] = $produto;
			}
		}

		// Carregar view principal
		$this->load->view('principal_view', $data);
	}

	private function format_price($preco) {
		return 'R$ ' . number_format($preco, 2, ',' , '.');
	}
    

}