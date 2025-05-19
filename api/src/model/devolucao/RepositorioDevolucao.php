<?php

interface RepositorioDevolucao{
    /**
     * Adiciona devolução ao BD.
     * @param Devolucao $devolucao
     * @throws RepositorioException
     * @return void
     */
    public function adicionar(Devolucao $devolucao) : void;

    /**
     * Coleta devolução com o ID.
     * @param int $id
     * @throws RepositorioException
     * @return void
     */
    public function coletarComId(int $id): null | Devolucao;

    /**
     * Retorna todas as devoluções.
     * @return array<Devolucao>
     */
    public function coletarTodos():array;
}