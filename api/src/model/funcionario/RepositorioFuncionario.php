<?php

interface RepositorioFuncionario {
    /**
     * @param int id
     * @return Funcionario | null 
     */
    public function coletarComId(int $id) : Funcionario;

    /**
     * @return array<Funcionario>|[]
     */
    public function coletarTodos() : array;
}