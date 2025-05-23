<?php

interface RepositorioLocacao {
    /** 
     * @return void
     * @throws RepositorioException
     */
    public function adicionar(Locacao $locacao) : void;
    public function coletarTodos();
    public function coletarComParametros(array $parametros): array;
    public function marcarComoDevolvida(Locacao $locacao) : void;

}