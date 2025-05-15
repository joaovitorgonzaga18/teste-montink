<?php
namespace Entity;

/**
* Pedidos
*
* @Entity
* @Table(name="pedidos")
* @author João Jota <joaovitorgonzaga18@gmail.com>
*/

class Pedidos{

	/**
	* @Id
	* @Column(type="integer", nullable=false)
	* @GeneratedValue(strategy="IDENTITY")
	*/
	private $id;

	/**
	* @OneToOne(targetEntity="Cupons")
	* @JoinColumn(name="id_cupom", referencedColumnName="id", nullable=true)
	*/
	private $idCupom;

	/**
	* @Column(name="preco", type="float", nullable=false)
	*/
	private $preco;	

	/**
	* @Column(name="desconto", type="float", nullable=true)
	*/
    private $desconto;

	/**
	* @Column(name="frete", type="float", nullable=false)
	*/
	private $frete;	

	/**
	* @Column(name="cep", type="string", nullable=false)
	*/
	private $cep;	

	/**
	* @Column(name="data_pedido", type="string", nullable=false)
	*/
    private $dataPedido;

    public function getId(): int {
        return $this->id;
    }
    
    public function getIdCupom() {
        return $this->idCupom;
    }
    
    public function setIdCupom(\Entity\Cupons $cupom): void {
        $this->idCupom = $cupom;
    }
    
    public function getPreco(): float {
        return $this->preco;
    }
    
    public function setPreco(float $preco): void {
        $this->preco = $preco;
    }
    
    public function getDesconto(): float {
        return $this->desconto;
    }
    
    public function setDesconto(float $desconto): void {
        $this->desconto = $desconto;
    }
    
    public function getFrete(): float {
        return $this->frete;
    }
    
    public function setFrete(float $frete): void {
        $this->frete = $frete;
    }
    
    public function getCep(): string {
        return $this->cep;
    }
    
    public function setCep(string $cep): void {
        $this->cep = $cep;
    }
    
    public function getDataPedido(): string {
        return $this->dataPedido;
    }
    
    public function setDataPedido(string $dataPedido): void {
        $this->dataPedido = $dataPedido;
    }
}