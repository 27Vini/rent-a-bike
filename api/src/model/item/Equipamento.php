<?php
require_once './infra/util/validarId.php';

class Equipamento extends Item{
    private int $id;
    private int $idItem;

    public function __construct(null | int $id, null | int $idItem, string $codigo, string $descricao, string $modelo, string $fabricante, float $valorPorHora, string $avarias, bool $disponibilidade, string $tipo){
        $this->id = $id;
        $this->idItem = $idItem;
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

    public function validar() : array {
        $problemas = [];
        $problemas = validarId($this->id);

        return $problemas;
    }
}