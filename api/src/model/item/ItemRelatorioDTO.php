<?php

class ItemRelatorioDTO implements \JsonSerializable{
    public function __construct(private int $qtdVezesAlugado,  private string $descricao, private float $porcentagem){
        
    }

    public function jsonSerialize(): mixed{
        return [
            "descricao" => $this->descricao,
            "qtdVezesAlugado" => $this->qtdVezesAlugado,
            "porcentagem" => $this->porcentagem
        ];
    }
}