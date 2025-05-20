<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Entity\Cupons;

class CuponsController extends CI_Controller{ 
	function __construct(){
		parent::__construct();
	}

	// Função responsável por encontrar um único cupom baseado no seu código
	public function getByCode($codigoCupom = '', $json = 0) {
		
		$data = [];

		// Verificar se o id do cupom é válido
		if ($codigoCupom != '') {
			$cupom = $this->doctrine->em->getRepository('Entity\Cupons')->findOneBy(array('codigo' => $codigoCupom));
			
			// Verificar se o cupom foi encontrado
			if ($cupom) {

				$data['cupom'] = [
                    'id' => $cupom->getId(),
                    'codigo' => $codigoCupom,
                    'desconto' => $cupom->getDesconto(),
                    'valido' => $cupom->getValido(),
				];
			}
		}

		if ($json == 1) {
			echo json_encode($data);
		} else {
			// Carregar view principal
			// $this->load->view('cupom_view', $data);
		}
	}
    

}