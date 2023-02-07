<?php

namespace App\Models;

use MF\Model\Model;


class rifa extends  Model{

    private $codigo;
    private $senha;
    private $nome;
    private $email;
    private $premio;
    private $valor;
    private $tipo;
    private $tipoQuantidade;
    private $quantidade;
    private $listaDados;
        

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    //salvar
    public function salvar()
    {   
        $nomes = array();
        $lista = null;
        if ($this->__get('tipo') == 'nomes') {   
            $query = "SELECT nome FROM ".filter_var($this->__get('tipoQuantidade'),FILTER_SANITIZE_STRING)." ORDER BY RAND() limit ".filter_var($this->__get('quantidade'), FILTER_SANITIZE_NUMBER_INT);
            $stmt = $this->db->prepare($query);       
            $stmt->execute();
            $nomes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($nomes as $nomes)  {
              $lista .= $nomes['nome'].',';
            }
        }

        if ($this->__get('tipo') == 'numeros') {   
            for ($i=1; $i < $this->__get('quantidade')+1; $i++) {
                $lista .= $i.',';
            }
        }


        $query = "INSERT INTO `rifas`(`senha`, `nome`, `premio`, `valor`, `tipo`, `tipoQuantidade`, `quantidade`, `listaDados`, `email`) VALUES (:senha , :nome , :premio , :valor , :tipo, :tipoQuantidade , :quantidade , :listaDados , :email)";

        $stmt = $this->db->prepare($query);       
        $stmt->bindValue('senha', $this->__get('senha'));
        $stmt->bindValue('nome', $this->__get('nome'));
        $stmt->bindValue('premio', $this->__get('premio'));
        $stmt->bindValue('valor', $this->__get('valor'));
        $stmt->bindValue('tipo', $this->__get('tipo'));
        $stmt->bindValue('tipoQuantidade', $this->__get('tipoQuantidade'));
        $stmt->bindValue('quantidade', $this->__get('quantidade'));
        $stmt->bindValue('email', $this->__get('email'));
        $stmt->bindValue('listaDados', $lista);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    //salvar
    public function sortear()
    {
        $query = "SELECT count(*) as quantidade FROM `rifas` WHERE codigo = :codigo and senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('codigo', $this->__get('codigo'));
        $stmt->bindValue('senha', $this->__get('senha'));
        $stmt->execute();
        $login = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($login['quantidade'] == 0) {
            return 'erro';
        }


        $lista = array();
        $query = "SELECT listaDados , listaSorteado FROM `rifas` WHERE codigo = :codigo and senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('codigo', $this->__get('codigo'));
        $stmt->bindValue('senha', $this->__get('senha'));
        $stmt->execute();
        $listaDados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $arraylista = explode(',', $listaDados[0]['listaDados'], -1);
        $tamanhoArray = count($arraylista);
        $sorteados = 0;

        $sorteio = $arraylista[rand(0, $tamanhoArray - 1)];



        if ($listaDados[0]['listaSorteado'] == NULL) {

            $query = "UPDATE `rifas` SET `listaSorteado` = :listaDados WHERE `codigo` =  :codigo";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue('codigo', $this->__get('codigo'));
            $stmt->bindValue('listaDados', $sorteio);
            $stmt->execute();
        }

        if ($listaDados[0]['listaSorteado'] != NULL) {

            $sorteados = $listaDados[0]['listaSorteado'] . ',' . $sorteio;

            $query = "UPDATE `rifas` SET `listaSorteado` = :listaDados WHERE `codigo` =  :codigo";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue('codigo', $this->__get('codigo'));
            $stmt->bindValue('listaDados', $sorteados);
            $stmt->execute();
        }

        $arraylista = explode(',', $sorteados);
        return $arraylista;
    }

    public function getResultado() {

        $query = "SELECT listaDados , listaSorteado FROM `rifas` WHERE codigo = :codigo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('codigo', $this->__get('codigo'));
        $stmt->execute();
        $listaDados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $arraylista = explode(',', $listaDados[0]['listaSorteado']);
        $tamanhoArray = count($arraylista);
        $sorteados = 0;

        return $arraylista;
    }

    public function getResultadoSorteio() {
        $arraylista = array();
        $novoarray = array();
        $query = "SELECT listaDados , listaSorteado , datacriacao ,email as donoRifa FROM `rifas` WHERE codigo = :codigo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('codigo', $this->__get('codigo'));
        $stmt->execute();
        $listaDados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        
        if (!empty($listaDados)) {
            
            if ($listaDados[0]['listaSorteado'] == '') {
                return $novoarray = array(0=>'Rifa ainda nao xx sorteada');
                var_dump($novoarray);
            }

            $arraylista = explode(',', $listaDados[0]['listaSorteado']);
            $tamanhoArray = count($arraylista);
            $sorteados = 0;
            $novoarray = array_merge($listaDados, $arraylista);
            return $novoarray;
        }

 
            return $novoarray = array(0=>'Rifa ainda nao xx sorteada');

    }
}

?>