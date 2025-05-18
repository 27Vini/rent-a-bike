<?php

interface RepositorioFuncionario {
    /**
     * @param int id
     * @return Funcionario | null 
     */
    public function coletarComId(int $id) : Funcionario | null;
}