<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Doctrine\ORM\Mapping\Entity\Atividades;

class Atividade extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
	}

	// Funcao responsável por buscar as atividades filtrando pelo projeto associado	
	public function projeto() {

		$id = $this->input->post('id');

		$data = [];

		// Recuperando um repositório filtrando pelo id do projeto com EntityManager
		$atividades = $this->doctrine->em->getRepository("Entity\Atividade")
									 ->findBy(array("idProjeto"=>$id),array("dataCadastro"=>"asc"));

		

		// Caso não seja encontrado nenhum registro
		if ( !$atividades ) {
			
			//Retornando objeto json vazio
			echo json_encode($data);
			return;

		}
									 
		// Alimentando array para retorno
		foreach($atividades as $atividade){
			$data[] = [
				"id"=>$atividade->getId(),
				"data"=>$atividade->getDataCadastro(),
				"descricao"=>$atividade->getDescricao()
			];
		}
		
		// Retornando objeto json das atividades recuperadas pela função
		echo json_encode($data);

    }

	// Função responsável por criar e persistir uma nova atividade no banco de dados
	public function create() {

		$data = [];

		$descricao = $this->input->post('descricao');
		$id_projeto = $this->input->post('id_projeto');
		
		if ( strlen($descricao) <= 1 || $id_projeto == NULL || $id_projeto == '' ) {

			echo json_encode($data);
			return;

		 }
		
		// Instanciando nova entidade Atividade
		$newAtividade = new Entity\Atividade;

		// Recuperando o projeto a ser associado à atividade
		$projeto = $this->doctrine->em->find("Entity\Projeto",$id_projeto);
		
		// Definindo os valores da nova Atividade com base nos parametros passados
		$newAtividade->setDescricao($descricao);
		$newAtividade->setIdProjeto($projeto);
		$newAtividade->setDataCadastro(date("Y-m-d H:i:s"));
		
		// Persistindo a nova atividade no banco de dados com EntityManager
		$this->doctrine->em->persist($newAtividade);
		$this->doctrine->em->flush();

		// Alimentando array para retorno
		$data[] = [
			"id"=>$newAtividade->getId(),
			"data"=>$newAtividade->getDataCadastro(),
			"descricao"=>$newAtividade->getDescricao(),
			"projeto"=>$newAtividade->getIdProjeto()
		];

		// Retornando objeto json da nova Atividade persistida no banco de dados
		echo json_encode($data);

	}

	// Função responsável por recuperar uma atividade a partir do id
    public function get(){

		$data = [];

		$id = $this->input->post('id');

		// Recuperando a Atividade com EntityManager
		$atividade = $this->doctrine->em->find("Entity\Atividade",$id);
		
		// Caso não seja encontrado nenhum registro
		if ( !$atividade ) {

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		$id_projeto = $atividade->getIdProjeto()->getId();

		$projeto = $this->doctrine->em->find("Entity\Projeto",$id_projeto);

		// Alimentando array para retorno
		$data = [
            "id"=>$atividade->getId(),
            "data"=>$atividade->getDataCadastro(),
            "descricao"=>$atividade->getDescricao(),
			"projeto"=>$projeto
        ];
		
		// Retornando objeto json da Atividade encontrada
		echo json_encode($data);

    }

	// Função responsável por recuperar uma atividade a partir do id
    public function getall(){

		$data = [];

		// Recuperando Atividades com EntityManager
		$atividades = $this->doctrine->em->getRepository("Entity\Atividade")->findAll();
		
		// Caso não seja encontrado nenhum registro
		if ( !$atividades ) {

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Alimentando array para retorno
		foreach($atividades as $atividade) {
			
			$id_projeto = $atividade->getIdProjeto()->getId();

			$projeto = $this->doctrine->em->find("Entity\Projeto",$id_projeto);

			$data [] = [
				"id"=>$atividade->getId(),
				"data"=>$atividade->getDataCadastro(),
				"descricao"=>$atividade->getDescricao(),
				"projeto"=>$projeto
			];
		}
		
		// Retornando objeto json da Atividade encontrada
		echo json_encode($data);

    }

	// Função responsável por atualizar registros de Atividades
	public function update() {

		$data = [];

		$id = $this->input->post('id');
		$descricao = $this->input->post('descricao');
		$id_projeto = $this->input->post('id_projeto');
		
		// Recuperando Atividade a ser atualizada
		$newAtividade = $this->doctrine->em->find("Entity\Atividade", $id);

		// Recuperando novo Projeto a ser associado
		$projeto = $this->doctrine->em->find("Entity\Projeto",$id_projeto);

		// Caso não seja encontrada a Atividade ou Projeto
		if (!$newAtividade || !$projeto || strlen($descricao) <= 1 || $id_projeto == NULL || $id_projeto == '' ) {			

			// Retornando objeto json vazio
			echo json_encode($data);
			return;

		}

		// Definindo novos atributos da Atividade
		$newAtividade->setDescricao($descricao);
		$newAtividade->setIdProjeto($projeto);

		// Alimentando array para retorno
		$data[] = [
			"id"=>$newAtividade->getId(),
			"data"=>$newAtividade->getDataCadastro(),
			"descricao"=>$newAtividade->getDescricao(),
			"projeto"=>$projeto
		];
		
		// Atualizando efetivamente a Atividade
		$this->doctrine->em->flush();

		// Retornando objeto json da Atividade atualizada
		echo json_encode($data);

	}
	
	// Função responsável por deletar uma Atividade
	public function delete() {	
		
		$data = [];

		$id = $this->input->post('id');

		//Recuperando a Atividade a ser deletada
		$atividade = $this->doctrine->em->find("Entity\Atividade",$id);

		// Caso não seja encontrado nenhuma atividade
		if ( !$atividade ) {

			//Retornando objeto json vazio
			echo json_encode($data);
			return;

		}		

		// Alimentando array para retorno
		$data[] = [
            "id"=>$atividade->getId(),
            "data"=>$atividade->getDataCadastro(),
            "descricao"=>$atividade->getDescricao()
        ];
		
		// Removendo Efetivamente a Atividade do banco de daodos com EntityManager
		$this->doctrine->em->remove($atividade);
		$this->doctrine->em->flush();

		// Retornando objeto json da Atividade deletada
		echo json_encode($data);

	}
    
}