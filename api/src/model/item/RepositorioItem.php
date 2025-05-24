<?php

interface RepositorioItem{
    /**
     * Salva um item no banco de dados
     * @param Item @item
     * @return void
     */
    public function adicionar(Item $item) : void;

    /**
     * Coleta um item com o id informado
     * @param int $id
     * @return Item
     * @throws DominioException
     */
    public function coletarComId(int $id) : Item;

    /**
     * Coleta um item com o código informado
     * @param string $codigo
     * @return Item
     * @throws DominioException
     */
    public function coletarComCodigo(string $codigo) : Item;

    /**
     * Altera a disponibilidade do item salvo 
     * @param Item $item
     * @return void
     */
    public function atualizarDisponibilidade(Item $item) : void;
}