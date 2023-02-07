<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

//domPDF
use Dompdf\Dompdf;
use Dompdf\Options;


class AppController extends Action {

	public function geraRifa() {

		$this->render('geraRifa');
	}

	public function realizarSorteio() {

		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
		$rifa = Container::getModel('rifa');
		$rifa->__set('codigo', $codigo); 

		$lista = array();
		if ($codigo) {
			$lista =  $rifa->getResultado();
			$this->view->sorteados = $lista;
		}
		
		$this->view->sorteados = $lista;

		$this->render('realizarSorteio');
	}

	public function sortear() {
		$rifa = Container::getModel('rifa');
		$rifa->__set('senha', $_POST['senha']); 
		$rifa->__set('codigo', $_POST['codigo']); 

		$lista =  $rifa->sortear();

		
		header('Location: /realizarSorteio?codigo='.$_POST['codigo'].'&senha='.$_POST['senha']);
		//$this->render('realizarSorteio');
	}

	public function verificarResultado() {

		$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
		$rifa = Container::getModel('rifa');
		$rifa->__set('codigo', $codigo); 

		
		$novoarray = array();
		if ($codigo) {
			$novoarray =  $rifa->getResultadoSorteio();	
			
			$datacriacao = isset($novoarray[0]['datacriacao']) ? $novoarray[0]['datacriacao'] : '';
			$donoRifa = isset($novoarray[0]['donoRifa']) ? $novoarray[0]['donoRifa'] : '';
			$resultado = isset($novoarray[1]) ? $novoarray[1] : $novoarray = array(2=>'Sorteio ainda não realizado');

			
			//$this->view->resultado = $resultado;
			$this->view->data =  $datacriacao;	
			$this->view->donoRifa = $donoRifa;	
			$this->view->resultado = $novoarray;	
		}
		

		
		$this->render('verificarResultado');
	}

	public function baixar() {
	    
	    $lastId = isset($_GET['id']) ? $_GET['codigo'] : $lastId;

		//var_dump($_POST);
		$rifa = Container::getModel('rifa');
		$rifa->__set('nome', $_POST['nome']);
		$rifa->__set('premio', $_POST['premio']);
		$rifa->__set('tipo', $_POST['tipo']);
		$rifa->__set('tipoQuantidade', $_POST['tipoQuantidade']); 
		$rifa->__set('quantidade', $_POST['quantidade']); 
		$rifa->__set('valor', $_POST['valor']); 
		$rifa->__set('senha', $_POST['senha']); 
		$rifa->__set('email', $_POST['email']); 
        
        
		$lastId =  $rifa->salvar();
        $numero = $_POST['lastId'] = $lastId;
        $lastId = isset($_GET['id']) ? $_GET['id'] : $lastId;

        
        require './app_send_mail/processa_envio.php';
        

        //o pdf
        
        $nomeRifa = "rifa".$_POST['quantidade'].$_POST['tipoQuantidade'].".pdf";
        //instacia do pdf
		$dompdf = new Dompdf();
		//carregar o html para o pdf da classe
		$dompdf->load_html(file_get_contents("http://gerarifa.online/teste.php?id=".$lastId));
		//rederizar o arquivo pdf
		
    	$dompdf->render();
		
        
        
		header('Content-type: application/pdf');
		echo $dompdf->output();
	}

}


?>