<?php

class Devolucao{
    private DateTime $dataDeDevolucao;
    private float $valorPago;

    public function  __construct(DateTime $data, float $valor) {
        $this->dataDeDevolucao = $data;
        $this->valorPago = $valor;
    }

    public function getDataDeDevolucao(){
        return $this->dataDeDevolucao;
    }

    public function getValorPago(){
        return $this->valorPago;
    }

    public function setDataDeDevolucao(DateTime $dataDeDevolucao){
        $this->dataDeDevolucao = $dataDeDevolucao;
    }

    public function setValorPago(float $valorPago){
        $this->valorPago = $valorPago;
    }

    
}
