<?php

class GestorFuncionario {
    public function __construct(private RepositorioFuncionario $repositorioFuncionario)
    {}

    /**
     * Coleta todos os funcionários
     * @return array<Funcionario>
     * @throws Exception
     */
    public function coletarFuncionarios() : array {
        try{
            $funcionarios = $this->repositorioFuncionario->coletarTodos();

            return $funcionarios;
        }catch(Exception $e){
            throw $e;
        }
    }
}