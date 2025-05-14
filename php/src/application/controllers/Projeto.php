<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Doctrine\ORM\Mapping\Entity\Projetos;

class Projeto extends CI_Controller{ 
	function __construct(){
		parent::__construct();
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
	}

    // Função responsável por criar e persistir uma nova projeto no banco de dados
	public function create() {
		
		$descricao = $this->input->post('descricao');

		// Instanciando nova entidade projeto
		$newprojeto = new Entity\Projeto;
		
		// Definindo os valores da nova projeto com base nos parametros passados
		$newprojeto->setDescricao($descricao);
		
		// Persistindo a nova projeto no banco de dados com EntityManager
		$this->doctrine->em->persist($newprojeto);
		$this->doctrine->em->flush();

		// Alimentando array para retorno
		$data[] = [
			"id"=>$newprojeto->getId(),
			"descricao"=>$newprojeto->getDescricao(),
        ];
		// Retornando objeto json da nova projeto persistida no banco de dados
		echo json_encode($data);

	}

	// Função responsável por recuperar uma projeto a partir do id
    public function get(){

		$data = [];

		$id = $this->input->post('id');

		// Recuperando a projeto com EntityManager
		$projeto = $this->doctrine->em->find("Entity\Projeto",$id);
		
		// Caso não seja encontrado nenhum registro
		if ( !$projeto ) {

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Alimentando array para retorno
		$data = [
            "id"=>$projeto->getId(),
            "descricao"=>$projeto->getDescricao(),
        ];
		
		// Retornando objeto json da projeto encontrada
		echo json_encode($data);

    }

	// Função responsável por recuperar uma projeto a partir do id
    public function getall(){

		$data = [];

		// Recuperando projetos com EntityManager
		$projetos = $this->doctrine->em->getRepository("Entity\Projeto")->findAll();
		
		// Caso não seja encontrado nenhum registro
		if ( !$projetos ) {

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Alimentando array para retorno
		foreach($projetos as $projeto) {
 
			$atividades = $this->doctrine->em->getRepository("Entity\Atividade")
										 ->findBy(array("idProjeto"=>$projeto->getId()));

			$data[] = [
				"id"=>$projeto->getId(),
				"descricao"=>$projeto->getDescricao(),
				"atividades"=>count($atividades)
			];
		}
		
		// Retornando objeto json da projeto encontrada
		echo json_encode($data);

    }

	// Função responsável por atualizar registros de projetos
	public function update() {

		$data[] = [];

		$id = $this->input->post('id');
		$descricao = $this->input->post('descricao');
		
		// Recuperando projeto a ser atualizada
		$newprojeto = $this->doctrine->em->find("Entity\Projeto", $id);

		// Caso não seja encontrada a projeto ou Projeto
		if (!$newprojeto) {			

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Definindo novos atributos da projeto
		$newprojeto->setDescricao($descricao);

		// Alimentando array para retorno
		$data[] = [
			"id"=>$newprojeto->getId(),
			"descricao"=>$newprojeto->getDescricao(),
		];
		
		// Atualizando efetivamente a projeto
		$this->doctrine->em->flush();

		// Retornando objeto json da projeto atualizada
		echo json_encode($data);

	}
	
	// Função responsável por deletar uma projeto
	public function delete() {	
		
		$data = [];

		$id = $this->input->post('id');

		//Recuperando a projeto a ser deletada
		$projeto = $this->doctrine->em->find("Entity\Projeto",$id);

		// Caso não seja encontrado nenhuma projeto
		if ( !$projeto ) {

			//Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Alimentando array para retorno
		$data[] = [
            "id"=>$projeto->getId(),
            "descricao"=>$projeto->getDescricao()
        ];
		
		// Removendo Efetivamente a projeto do banco de daodos com EntityManager
		$this->doctrine->em->remove($projeto);
		$this->doctrine->em->flush();

		// Retornando objeto json da projeto deletada
		echo json_encode($data);

	}
    

}