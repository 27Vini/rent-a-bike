<?php

interface RepositorioFuncionario {
    /**
     * @param int $id
     * @return Funcionario
     */
    public function coletarComId(int $id) : Funcionario;

    /**
     * @return array<Funcionario>
     */
    public function coletarTodos() : array;
}