<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {
     
	public function index()
	{
		$produtos = $this->doctrine->em->getRepository('Entity\Produtos')->findAll();
        $pedidos = $this->doctrine->em->getRepository('Entity\Pedidos')->findAll();

        $data = [
            'produtos' => [],
            'pedidos' => []
        ];

        if ($produtos) {
            foreach($produtos as $produto) {

                $idProduto = $produto->getId();
                $estoque = $this->doctrine->em->getRepository('Entity\Estoque')->findOneBy(array('idProduto' => $idProduto));
                
                $data['produtos'][] = [
                    'id' => $idProduto,
                    'nome' => $produto->getNome(),
                    'preco' => 'R$ ' . number_format($produto->getPreco(), 2, ',', '.'),
                    'variacao' => $produto->getVariacao(),
                    'estoque' => ($estoque) ? $estoque->getQtdEstoque() : 0,
                ];
            }
        }

        if($pedidos) {
            foreach($pedidos as $pedido) {

                $idPedido = $pedido->getId();
                $cupom = $this->doctrine->em->find('Entity\Cupons', $pedido->getIdCupom());
                
                $data['pedidos'][] = [
                    'id' => $idPedido,
                    'cupom' => ($cupom) ? $cupom->getCodigo() : '---',
                    'preco' => 'R$ ' . number_format($pedido->getPreco(), 2, ',', '.'),
                    'desconto' => 'R$ ' . number_format($pedido->getDesconto(), 2, ',', '.'),
                    'frete' => 'R$ ' . number_format($pedido->getFrete(), 2, ',', '.'),
                    'cep' => $pedido->getCep(),
                    'data_pedido' => date('d/m/Y h:i:s', strtotime($pedido->getDataPedido())),
                ];
            }
        }

		$this->load->view('principal_view', $data);
	}

	public function teste(){
		echo "teste";
	}
}