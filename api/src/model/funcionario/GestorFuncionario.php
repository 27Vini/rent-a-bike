<?php

class GestorFuncionario {
    public function __construct(private RepositorioFuncionario $repositorioFuncionario)
    {}

    public function coletarFuncionarios() : array {
        try{
            $funcionarios = $this->repositorioFuncionario->coletarTodos();

            return $funcionarios;
        }catch(Exception $e){
            throw $e;
        }
    }
}