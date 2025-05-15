<?php
namespace Entity;

/**
* Cupons
*
* @Entity
* @Table(name="cupons")
* @author João Jota <joaovitorgonzaga18@gmail.com>
*/

class Cupons{

	/**
	* @Id
	* @Column(type="integer", nullable=false)
	* @GeneratedValue(strategy="IDENTITY")
	*/
	private $id;

	/**
	* @Column(name="codigo", type="string", length=255, nullable=false)
	*/
    private $codigo;

	/**
	* @Column(name="desconto", type="float", nullable=false)
	*/
    private $desconto;

	/**
	* @Column(name="valido", type="integer", nullable=false)
	*/
    private $valido;

    public function getId(): int {
        return $this->id;
    }

    public function getCodigo(): string {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): void {
        $this->codigo = $codigo;
    }

    public function getDesconto(): float {
        return $this->desconto;
    }

    public function setDesconto(float $desconto): void {
        $this->desconto = $desconto;
    }	

    public function getValido(): int {
        return $this->valido;
    }

    public function setValido(int $valido): void {
        $this->valido = $valido;
    }	
}