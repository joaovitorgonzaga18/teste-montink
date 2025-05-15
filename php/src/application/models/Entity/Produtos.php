<?php
namespace Entity;

/**
* Produtos
*
* @Entity
* @Table(name="produtos")
* @author João Jota <joaovitorgonzaga18@gmail.com>
*/

class Produtos{


	/**
	* @Id
	* @Column(type="integer", nullable=false)
	* @GeneratedValue(strategy="IDENTITY")
	*/
	private $id;

	/**
	* @Column(name="nome", type="string", length=255, nullable=false)
	*/
	private $nome;	

	/**
	* @Column(name="preco", type="float", nullable=false)
	*/
    private $preco;

	/**
	* @Column(name="variacao", type="integer", nullable=false)
	*/
    private $variacao;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getPreco(): float {
        return $this->preco;
    }

    public function setPreco(float $preco): void {
        $this->preco = $preco;
    }

    public function getVariacao(): int {
        return $this->variacao;
    }

    public function setVariacao(int $variacao): void {
        $this->variacao = $variacao;
    }	
}