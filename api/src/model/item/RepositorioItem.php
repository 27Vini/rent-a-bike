<?php

interface RepositorioItem{

    /**
     * @param string codigo
     * @return Item
     */
    public function coletarComCodigo(string $codigo) : Item;
}