<?php
require_once './infra/util/validarId.php';

class Bicicleta extends Item{
    private int $id;
    private int $idItem;
    private string $numeroSeguro;

    public function __construct(null | int $id, null | int $idItem, string $numeroSeguro, string $codigo, string $descricao, string $modelo, string $fabricante, float $valorPorHora, string $avarias, bool $disponibilidade, string $tipo){
        $this->id = $id;
        $this->idItem = $idItem;
        $this->numeroSeguro = $numeroSeguro;
        parent::__construct($idItem, $codigo, $descricao, $modelo, $fabricante, $valorPorHora, $avarias, $disponibilidade, $tipo);
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setIdItem(int $idItem){
        $this->idItem = $idItem;
    }

    public function getIdItem(){
        return $this->idItem;
    }

    public function setNumeroSeguro(string $numeroSeguro){
        $this->numeroSeguro = $numeroSeguro;
    }

    public function getNumeroSeguro(){
        return $this->numeroSeguro;
    }

    public function validar(): array{
        $problemas = [];
        $problemas = validarId($this->id);

        return $problemas;
    }
}