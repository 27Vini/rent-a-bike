<?php

class GestorItem{
    public function __construct(private RepositorioItem $repositorioItem){}

    public function coletarComCodigo(string $codigo) : Item{
        try{
            $item = $this->repositorioItem->coletarComCodigo($codigo);

            return $item;
        }catch(Exception $e){
            throw $e;
        }
    }
}