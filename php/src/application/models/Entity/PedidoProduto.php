<?php
namespace Entity;

/**
* PedidoProduto
*
* @Entity
* @Table(name="pedido_produto")
* @author João Jota <joaovitorgonzaga18@gmail.com>
*/

class PedidoProduto{

	/**
	* @Id
	* @Column(type="integer", nullable=false)
	* @GeneratedValue(strategy="IDENTITY")
	*/
	private $id;

	/**
	* @OneToOne(targetEntity="Pedidos")
	* @JoinColumn(name="id_pedido", referencedColumnName="id")
	*/
	private $idPedido;

	/**
	* @OneToOne(targetEntity="Produtos")
	* @JoinColumn(name="id_produto", referencedColumnName="id")
	*/
	private $idProduto;

	/**
	* @Column(name="qtd_produto", type="integer", nullable=false)
	*/
    private $qtdProduto;

    public function getId(): int {
        return $this->id;
    }

    public function getIdPedido() {
        return $this->idPedido;
    }

    public function setIdPedido(\Entity\Pedidos $pedido): void {
        $this->idPedido = $pedido;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto(\Entity\Produtos $produto): void {
        $this->idProduto = $produto;
    }

    public function getQtdProduto(): int {
        return $this->qtdProduto;
    }

    public function setQtdProduto(int $qtdProduto): void {
        $this->qtdProduto = $qtdProduto;
    }	
}