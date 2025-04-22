<?php

class Devolucao{
    private string | int $id;
    private string | int $locacaoId;
    private DateTime $dataDeDevolucao;
    private float $valorPago;

    public function  __construct(string | int $id, string|int $locacaoId ,DateTime $data, float $valor) {
        $this->id = $id;
        $this->locacaoId = $locacaoId;
        $this->dataDeDevolucao = $data;
        $this->valorPago = $valor;
    }

    public function getId() : int | string{
        return $this->id;
    }

    public function getLocacaoId(): int|string{
        return $this->locacaoId;
    }

    public function getDataDeDevolucao(){
        return $this->dataDeDevolucao;
    }

    public function getValorPago(){
        return $this->valorPago;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setLocacaoId($id){
        $this->locacaoId = $id;
    }

    public function setDataDeDevolucao(DateTime $dataDeDevolucao){
        $this->dataDeDevolucao = $dataDeDevolucao;
    }

    public function setValorPago(float $valorPago){
        $this->valorPago = $valorPago;
    }

    public function validar():array{
        $problemas = validarId($this->id);

        if($this->dataDeDevolucao > new DateTime()->format("Y-m-d H:i:s")){
            array_push($problemas, "A data de devolução deve ser inferior ou igual a data atual.");
        }

        if($this->valorPago < 0.0){
            array_push($problemas, "O valor pago deve ser maior que 0.");
        }

        return $problemas;
    }

    
}
