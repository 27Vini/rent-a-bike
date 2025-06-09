<?php

interface RepositorioAvaria{
    /**
     * Adiciona avaria ao BD.
     * @param Avaria $avaria
     * @throws RepositorioException
     * @throws Exception
     * @return void
     */
    public function adicionar(Avaria $avaria) : void;

    /**
     * Coleta avaria com o ID.
     * @param int $id
     * @throws RepositorioException
     * @throws DominioException
     * @return Avaria
     */
    public function coletarComId(int $id): Avaria;

    /**
     * Retorna todas as avarias.
     * @return array<Avaria>
     * @throws RepositorioException
     */
    public function coletarTodos():array;
}