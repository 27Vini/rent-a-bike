<?php

interface RepositorioLocacao {
    /** 
     * @return void
     * @throws RepositorioException
     */
    public function adicionar(Locacao $locacao) : void;

    /**
     * @return null | Locacao
     */
    public function coletarComId(int $id): null | Locacao;

    public function coletarTodos();

}