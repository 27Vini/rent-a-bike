<?php

class RepositorioClienteEmBDR extends RepositorioGenericoEmBDR{

    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Cliente $cliente) : void{
        $comando = "INSERT INTO cliente (codigo,nome,cpf,foto) VALUES (:codigo,:nome,:cpf,:foto)";
        $this->executarComandoSql($comando, ["nome" => $cliente->getNome(), "codigo" => $cliente->getCodigo(),
                                                              "cpf" => $cliente->getCpf(), "foto" => $cliente->getFoto()
                                                            ]);
    
        $cliente->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId($id): null | Cliente{
        $comando = "SELECT * FROM cliente WHERE id=:id";
        $ps = $this->executarComandoSql($comando, ["id" => $id ]);
        $c = $ps->fetchObject(Cliente::class) ?: null;
        return $c;
    }

}