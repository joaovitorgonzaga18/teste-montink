<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Entity\Produtos;
use Entity\Estoque;

class ProdutosController extends CI_Controller{ 
	function __construct(){
		parent::__construct();
	}

    // Função responsável por criar e persistir um novo Produto no banco de dados
	public function create() {

		$post = $_POST;

		$data = [];

		// Instanciando nova entidade Produto
		$newProduto = new Entity\Produtos;
		
		// Definindo os valores do novo Produto com base nos parametros passados
		$newProduto->setNome($post['nome']);
		$newProduto->setPreco($post['preco']);
		$newProduto->setVariacao(1);		
		
		// Persistir o novo Produto no banco de dados com EntityManager
		($this->doctrine->em->persist($newProduto));
		$this->doctrine->em->flush();

		// Verificar se o produto foi realmente persistido
		if ($this->doctrine->em->contains($newProduto)) {			

			// Carregar dados do produto para retorno das informações caso necessário
			$data['produto'] = $newProduto;

			// Instanciar nova entidade Estoque
			$newEstoque = new Entity\Estoque;

			// Definir os valores do produto com base nos parametros passados
			$newEstoque->setIdProduto($newProduto); // Vinculo com o produto criado
			$newEstoque->setQtdEstoque($post['estoque']);

			//Persistir o novo Estoque no banco de dados com EntityManager
			$this->doctrine->em->persist($newEstoque);
			$this->doctrine->em->flush();

			if ($this->doctrine->em->contains($newEstoque))
				$data['estoque'] = $newEstoque;
			
		}

		// Carregar view principal
		// $this->load->view('principal_view', $data);

	}

	// Função responsável por encontrar um único produto baseado no seu ID
	public function get($idProduto = 0, $json = 0) {
		
		$data = [];

		// Verificar se o id do produto é válido
		if ($idProduto > 0) {
			$produto = $this->doctrine->em->find('Entity\Produtos', $idProduto);
			
			// Verificar se o produto foi encontrado
			if ($produto) {
				
                $estoque = $this->doctrine->em->getRepository('Entity\Estoque')->findOneBy(array('idProduto' => $idProduto));

				$data['produto'] = [
                    'id' => $idProduto,
                    'nome' => $produto->getNome(),
                    'preco' => $produto->getPreco(),
                    'variacao' => $produto->getVariacao(),
                    'estoque' => ($estoque) ? $estoque->getQtdEstoque() : 0,
				];
			}
		}

		if ($json == 1) {
			echo json_encode($data);
		} else {
			// Carregar view principal
			$this->load->view('produto_view', $data);
		}
	}

	// Função responsável por encontrar e listar todos os produtos armazenados
	public function getAll() {

		$data = [];

		//Buscar todos os produtos armazenados em banco
		$produtos =  $this->doctrine->em->getRepository('Entity\Produtos')->findAll();

		// Verificar se encontrou produtos
		if ($produtos)
			$data['produtos'] = $produtos;

		// Carregar view principal
		$this->load->view('principal_view', $data);

	}

	public function update($idProduto = 0) {
		
		$data = [];

		$post = $_POST;

		// Verificar se o id do produto é válido
		if ($idProduto > 0) {
			$produto = $this->doctrine->em->find('Entity\Produtos', $idProduto);
			
			// Verificar se o produto foi encontrado
			if ($produto) {
				// Atualizar os dados
				$produto->setNome((isset($post['nome']) && $post['nome'] != $produto->getNome()) ? $post['nome'] : $produto->getNome());
				$produto->setPreco((isset($post['preco']) && $post['preco'] != $produto->getPreco()) ? $post['preco'] : $produto->getPreco());
				$produto->setVariacao((isset($post['variacao']) && $post['variacao'] != $produto->getVariacao()) ? $post['variacao'] : $produto->getVariacao());

				$estoque = $this->doctrine->em->getRepository('Entity\Estoque')->findOneBy(array('idProduto' => $idProduto));

				if ($estoque)
					$estoque->setQtdEstoque((isset($post['estoque']) && $post['estoque'] != $estoque->getQtdEstoque()) ? $post['estoque'] : $estoque->getQtdEstoque());
					$this->doctrine->em->persist($estoque);

				// Persistir os novos dados do Produto e do Estoque no banco de dados com EntityManager
				$this->doctrine->em->persist($produto);
				$this->doctrine->em->flush();

				if ($produto)
					$data['produto'] = $produto;
			}
		}

		// Carregar view principal
		// $this->load->view('principal_view', $data);
	}
    

}