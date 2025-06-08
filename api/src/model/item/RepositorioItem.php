<?php

interface RepositorioItem{
    /**
     * Salva um item no banco de dados
     * @param Item $item
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
     * Coleta dados dos itens para o relatório
     * @param string $dataInicial
     * @param string $dataFinal
     * @return array{itens:list<array{descricao:string,qtdVezesAlugado:string}>,totalLocacoes:string}     
     * @throws DominioException
     * @throws RepositorioException
     */
    public function coletarDadosParaRelatorio(string $dataInicial, string $dataFinal) : array;

    /**
     * Altera a disponibilidade do item salvo 
     * @param Item $item
     * @return void
     */
    public function atualizarDisponibilidade(Item $item) : void;
}