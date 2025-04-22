<?php

class Item {
    const TAM_CODIGO = 8;
    const TAM_MIN_STRING = 2;
    const TAM_MAX_DESCRICAO = 200;
    const TAM_MAX_MODELO = 60;
    const TAM_MAX_FABRICANTE = 60;

    private int    $id;
    private string $codigo;
    private string $descricao;
    private string $modelo;
    private string $fabricante;
    private float  $valorPorHora;
    private string $avarias;
    private bool   $disponibilidade;
    private string $tipo;

    public function  __construct(int | null $id, string $codigo, string $descricao, string $modelo, string $fabricante, float $valorPorHora, string $avarias, bool $disponibilidade, string $tipo){
        $this->id = $id;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->modelo = $modelo;
        $this->fabricante = $fabricante;
        $this->valorPorHora = $valorPorHora;
        $this->avarias = $avarias;
        $this->disponibilidade = $disponibilidade;
        $this->tipo = $tipo;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setCodigo(string $codigo){
        $this->codigo = $codigo;
    }

    public function getCodigo(){
        return $this->codigo;
    }
    public function setDescricao(string $descricao){
        $this->descricao = $descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }
    public function setModelo(string $modelo){
        $this->modelo = $modelo;
    }

    public function getModelo(){
        return $this->modelo;
    }

    public function setFabricante(string $fabricante){
        $this->fabricante = $fabricante;
    }

    public function getFabricante(){
        return $this->fabricante;
    }

    public function setValorPorHora(float $valorPorHora){
        $this->valorPorHora = $valorPorHora;
    }

    public function getValorPorHora(){
        return $this->valorPorHora;
    }

    public function setAvarias(string $avarias){
        $this->avarias = $avarias;
    }

    public function getAvarias(){
        return $this->avarias;
    }

    public function setDisponibilidade(bool $disponibilidade){
        $this->disponibilidade = $disponibilidade;
    }

    public function getDisponibilidade(){
        return $this->disponibilidade;
    }

    public function setTipo(string $tipo){
        $this->tipo = $tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function validar() : array {
        $problemas = [];
        $problemas = validarId($this->id);

        if($this->valorPorHora <= 0){
            $problemas[] = "Valor por hora inválido. O valor deve ser maior do que R$0,00.";
        }

        if(mb_strlen($this->codigo) != self::TAM_CODIGO){
            $problemas[] = "O código deve ter 8 caracteres.";
        }
        
        if(mb_strlen($this->modelo) < self::TAM_MIN_STRING || mb_strlen($this->modelo) > self::TAM_MAX_MODELO){
            $problemas[] = "O modelo deve ter entre ".self::TAM_MIN_STRING." e ".self::TAM_MAX_MODELO." caracteres.";
        }

        if(mb_strlen($this->descricao) < self::TAM_MIN_STRING || mb_strlen($this->descricao) > self::TAM_MAX_DESCRICAO){
            $problemas[] = "A descrição deve ter entre ".self::TAM_MIN_STRING." e ".self::TAM_MAX_DESCRICAO." caracteres.";
        }

        if(mb_strlen($this->fabricante) < self::TAM_MIN_STRING || mb_strlen($this->fabricante) > self::TAM_MAX_FABRICANTE){
            $problemas[] = "O fabricante deve ter entre ".self::TAM_MIN_STRING." e ".self::TAM_MAX_FABRICANTE." caracteres.";
        }

        if($this->tipo != TipoItem::BICICLETA || $this->tipo != TipoItem::EQUIPAMENTO){
            $problemas[] = "Tipo inválido.";
        }

        return $problemas;
    }
}