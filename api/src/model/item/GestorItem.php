<?php

class GestorItem{
    public function __construct(private RepositorioItem $repositorioItem){}

    /**
     * Coleta um item com o código informado
     * @param string $codigo
     * @return Item
     * @throws Exception
     */
    public function coletarComCodigo(string $codigo) : Item{
        try{
            $item = $this->repositorioItem->coletarComCodigo($codigo);

            return $item;
        }catch(Exception $e){
            throw $e;
        }
    }
}