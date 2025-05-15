<?php
namespace Entity;

/**
* Estoque
*
* @Entity
* @Table(name="estoque")
* @author João Jota <joaovitorgonzaga18@gmail.com>
*/

class Estoque{

	/**
	* @Id
	* @Column(type="integer", nullable=false)
	* @GeneratedValue(strategy="IDENTITY")
	*/
	private $id;

	/**
	* @OneToOne(targetEntity="Produtos")
	* @JoinColumn(name="id_produto", referencedColumnName="id")
	*/
	private $idProduto;

	/**
	* @Column(name="qtd_estoque", type="integer", nullable=false)
	*/
    private $qtdEstoque;

    public function getId(): int {
        return $this->id;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto(\Entity\Produtos $produto): void {
        $this->idProduto = $produto;
    }

    public function getQtdEstoque(): int {
        return $this->qtdEstoque;
    }

    public function setQtdEstoque(int $qtdEstoque): void {
        $this->qtdEstoque = $qtdEstoque;
    }	
}