<?php

class Funcionario{
    const TAM_MAX_NOME = 100;
    const TAM_MIN_NOME = 2;


    private int $id;
    private string $nome;

    public function __construct(int|null $id, string $nome){
        $this->id = $id;
        $this->nome = $nome;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNome(string $nome){
        $this->nome = $nome;
    }

    public function getNome(){
        return $this->nome;
    }

    public function validar() : array{
        $problemas = [];
        if(mb_strlen($this->nome) > self::TAM_MAX_NOME || mb_strlen($this->nome) < self::TAM_MIN_NOME){
            $problemas[] = "O tamanho do nome deve estar entre ". self::TAM_MAX_NOME ." e ".self::TAM_MIN_NOME;
        }

        return $problemas;
    }
}