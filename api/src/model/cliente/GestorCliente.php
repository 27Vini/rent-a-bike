<?php

class GestorCliente {
    public function __construct(private RepositorioCliente $repositorioCliente){}

    public function coletarComCodigoOuCpf($parametro){
        try{
            $cliente = $this->repositorioCliente->coletarComCodigoOuCpf($parametro);

            return $cliente;
        } catch(Exception $e){
            throw $e;
        }
    }
}