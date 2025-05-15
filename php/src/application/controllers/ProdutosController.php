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
		$newProduto->setNome('teste');
		$newProduto->setPreco(10);
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

			var_dump($newProduto);

			// Definir os valores do produto com base nos parametros passados
			$newEstoque->setIdProduto($newProduto); // Vinculo com o produto criado
			$newEstoque->setQtdEstoque(100);

			//Persistir o novo Estoque no banco de dados com EntityManager
			$this->doctrine->em->persist($newEstoque);
			$this->doctrine->em->flush();

			if ($this->doctrine->em->contains($newEstoque))
				$data['estoque'] = $newEstoque;
			
		}

		// Carregar view principal
		$this->load->view('principal_view', $data);

	}

	public function get($idProduto = 0) {
		
		$data = [];

		// Verificar se o id do produto é válido
		if ($idProduto > 0) {
			$produto = $this->doctrine->em->find('Entity\Produtos', $idProduto);
			
			// Verificar se o produto foi encontrado
			if ($produto) {
				$data['produtos'] = $produto;
			}
		}

		// Carregar view principal
		$this->load->view('principal_view', $data);
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

				$estoque = $this->doctrine->em->findBy('Entity\Estoque', array('id_produto' => $produto->getId()));
				
				if ($estoque)
					$estoque->setQtdEstoque((isset($post['variacao']) && $post['variacao'] != $estoque->getQtdEstoque()) ? $post['variacao'] : $estoque->getQtdEstoque());

				// Persistir os novos dados do Produto e do Estoque no banco de dados com EntityManager
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