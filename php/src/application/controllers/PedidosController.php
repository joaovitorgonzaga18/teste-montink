<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Entity\Pedidos;
use Entity\Produtos;
use Entity\Estoque;

class PedidosController extends CI_Controller{ 
	function __construct(){
		parent::__construct();
	}

    // Função responsável por criar e persistir um novo Pedido no banco de dados
	public function create() {

		$post = $_POST;

        $post = [
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
        ];

		$data = [];

        $valor_total = array_sum($post['pedidos']['precos']);
        $frete = 20;

        if ($valor_total >= 52 && $valor_total <= 166.59) {
            $frete = 15;
        } else if ($valor_total > 200) {
            $frete = 0;
        }

		// Instanciando nova entidade Pedido
		$newPedido = new Entity\Pedidos;
        $cupom = $this->doctrine->em->find('Entity\Cupons', $post['cupom']);

        $desconto = $cupom->getDesconto();
		
		// Definindo os valores do novo Pedido com base nos parametros passados
        $newPedido->setIdCupom($cupom);
		$newPedido->setPreco($valor_total - $desconto + $frete);
        $newPedido->setDesconto($desconto);
        $newPedido->setFrete($frete);
        $newPedido->setCep($post['cep']);
        $newPedido->setDataPedido(date('Y-m-d h:i:s'));
		
		// Persistir o novo Pedido no banco de dados com EntityManager
		($this->doctrine->em->persist($newPedido));
		$this->doctrine->em->flush();

		// Verificar se o pedido foi realmente persistido
		if ($this->doctrine->em->contains($newPedido)) {

            // Para cada produto no pedido, criar um cadastro em pedido_produto
            foreach($post['pedidos']['produtos'] as $k => $row) {
                $produto = $this->doctrine->em->find('Entity\Produtos', $row['id']);

                $pedido_produto = new Entity\PedidoProduto;

                $pedido_produto->setIdPedido($newPedido);
                $pedido_produto->setIdProduto($produto);
                $pedido_produto->setQtdProduto($row['qtd']);

                $this->doctrine->em->persist($pedido_produto);

            }

            $this->doctrine->em->flush();
			
		}

	}

	public function get($idPedido = 0) {
		
		$data = [];

		// Verificar se o id do produto é válido
		if ($idPedido > 0) {
			$produto = $this->doctrine->em->find('Entity\Pedidos', $idPedido);
			
			// Verificar se o produto foi encontrado
			if ($produto) {
				$data['produto'] = $produto;
			}
		}

		// Carregar view principal
		$this->load->view('principal_view', $data);
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
    

}